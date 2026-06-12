function flattenContent(content) {
  if (Array.isArray(content)) return content.join('\n');
  return content || '';
}

export function stripHtml(html) {
  if (!html) return '';
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return (doc.body.textContent || '').replace(/\s+/g, ' ').trim();
}

export function messageSearchText(message) {
  if (!message) return '';

  const parts = [
    flattenContent(message.content),
    message.markdown || '',
    message.html ? stripHtml(message.html) : '',
  ];

  return parts.filter(Boolean).join(' ').replace(/\s+/g, ' ').trim();
}

export function conversationHaystack(conversation) {
  if (!conversation) return '';

  return [
    conversation.title,
    conversation.project,
    conversation.source,
    conversation.snippet,
    conversation.searchText,
    ...(conversation.tags || []),
    ...(conversation.messages || []).map((message) => messageSearchText(message)),
  ]
    .join(' ')
    .toLowerCase();
}

export function messageMatchesQuery(message, query) {
  const q = (query || '').trim().toLowerCase();
  if (!q) return true;
  return messageSearchText(message).toLowerCase().includes(q);
}

export function escapeHtml(text) {
  return (text || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

export function highlightPlainText(text, query) {
  const source = text || '';
  const trimmed = query?.trim();
  if (!trimmed) return escapeHtml(source);

  const escapedQuery = trimmed.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const regex = new RegExp(`(${escapedQuery})`, 'gi');

  return escapeHtml(source).replace(regex, '<mark class="search-highlight">$1</mark>');
}

export function buildSnippetHtml(text, query, matchIndex, radius = 100) {
  const q = query.trim();
  const start = Math.max(0, matchIndex - radius);
  const end = Math.min(text.length, matchIndex + q.length + radius);
  let snippet = text.slice(start, end);

  if (start > 0) snippet = `…${snippet}`;
  if (end < text.length) snippet = `${snippet}…`;

  return highlightPlainText(snippet, q);
}

export function collectSearchResults(conversations, query, maxResults = 80) {
  const q = query.trim().toLowerCase();
  if (!q) return [];

  const results = [];

  for (const conversation of conversations) {
    let conversationHasMessageMatch = false;
    const headerText = [conversation.searchText, conversation.snippet].filter(Boolean).join('\n\n');
    const headerMatchIndex = headerText.toLowerCase().indexOf(q);

    if (headerMatchIndex !== -1) {
      conversationHasMessageMatch = true;
      results.push({
        id: `${conversation.id}-header-${headerMatchIndex}`,
        conversationId: conversation.id,
        title: conversation.title || 'Untitled conversation',
        source: conversation.source,
        archivedAt: conversation.archivedAt,
        project: conversation.project,
        messageIndex: null,
        role: null,
        snippetHtml: buildSnippetHtml(headerText, q, headerMatchIndex),
      });
    }

    if (results.length >= maxResults) break;

    for (let messageIndex = 0; messageIndex < (conversation.messages || []).length; messageIndex += 1) {
      if (results.length >= maxResults) break;

      const message = conversation.messages[messageIndex];
      const text = messageSearchText(message);
      const lower = text.toLowerCase();
      const matchIndex = lower.indexOf(q);

      if (matchIndex === -1) continue;

      conversationHasMessageMatch = true;
      results.push({
        id: `${conversation.id}-${messageIndex}-${matchIndex}`,
        conversationId: conversation.id,
        title: conversation.title || 'Untitled conversation',
        source: conversation.source,
        archivedAt: conversation.archivedAt,
        project: conversation.project,
        messageIndex,
        role: message.role,
        snippetHtml: buildSnippetHtml(text, q, matchIndex),
      });
    }

    if (results.length >= maxResults) break;

    const title = conversation.title || '';
    if (!conversationHasMessageMatch && title.toLowerCase().includes(q)) {
      results.push({
        id: `${conversation.id}-title`,
        conversationId: conversation.id,
        title: title || 'Untitled conversation',
        source: conversation.source,
        archivedAt: conversation.archivedAt,
        project: conversation.project,
        messageIndex: null,
        role: null,
        snippetHtml: highlightPlainText(title, q),
        isTitleMatch: true,
      });
    }
  }

  return results;
}

export function highlightHtml(html, query) {
  if (!html || !query?.trim()) return html;

  const doc = new DOMParser().parseFromString(`<div id="memoriq-highlight-root">${html}</div>`, 'text/html');
  const root = doc.getElementById('memoriq-highlight-root');
  if (!root) return html;

  const escaped = query.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const regex = new RegExp(escaped, 'gi');
  const walker = doc.createTreeWalker(root, NodeFilter.SHOW_TEXT);
  const textNodes = [];

  let node = walker.nextNode();
  while (node) {
    textNodes.push(node);
    node = walker.nextNode();
  }

  for (const textNode of textNodes) {
    const text = textNode.nodeValue || '';
    if (!regex.test(text)) {
      regex.lastIndex = 0;
      continue;
    }

    regex.lastIndex = 0;
    const fragment = doc.createDocumentFragment();
    let lastIndex = 0;
    let match;

    while ((match = regex.exec(text)) !== null) {
      if (match.index > lastIndex) {
        fragment.appendChild(doc.createTextNode(text.slice(lastIndex, match.index)));
      }

      const mark = doc.createElement('mark');
      mark.className = 'search-highlight';
      mark.textContent = match[0];
      fragment.appendChild(mark);
      lastIndex = regex.lastIndex;
    }

    if (lastIndex < text.length) {
      fragment.appendChild(doc.createTextNode(text.slice(lastIndex)));
    }

    textNode.parentNode?.replaceChild(fragment, textNode);
  }

  return root.innerHTML;
}
