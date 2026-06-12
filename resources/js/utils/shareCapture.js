const PROVIDERS = [
  { id: 'chatgpt', hostnames: ['chatgpt.com', 'chat.openai.com'] },
  { id: 'claude', hostnames: ['claude.ai'] },
  { id: 'gemini', hostnames: ['gemini.google.com'] },
  { id: 'grok', hostnames: ['grok.com', 'x.com'] },
];

function cleanText(value) {
  return (value || '').replace(/\s+\n/g, '\n').replace(/\n{3,}/g, '\n\n').trim();
}

function cleanTitle(value) {
  return cleanText(value).replace(/\s+/g, ' ').slice(0, 200);
}

function truncate(value, maxLength) {
  if (!value || value.length <= maxLength) return value || '';
  return `${value.slice(0, maxLength).trim()}…`;
}

export function detectProvider(sourceUrl) {
  if (!sourceUrl) return 'unknown';

  try {
    const hostname = new URL(sourceUrl).hostname.replace(/^www\./, '');
    return PROVIDERS.find((provider) => provider.hostnames.includes(hostname))?.id || 'unknown';
  } catch {
    return 'unknown';
  }
}

export function buildShareConversation({ title = '', text = '', url = '' } = {}) {
  const sourceUrl = cleanText(url);
  const provider = detectProvider(sourceUrl);
  const sharedText = cleanText(text);
  const sharedTitle = cleanTitle(title);

  const parts = [];
  if (sharedText) parts.push(sharedText);
  if (sourceUrl && sourceUrl !== sharedText) parts.push(sourceUrl);

  const content = parts.join('\n\n').trim() || sharedTitle || 'Shared chat excerpt';
  const conversationTitle = sharedTitle || truncate(content.split('\n')[0], 120) || 'Shared chat';

  const messages = [
    {
      role: 'assistant',
      content,
      format: 'text',
    },
  ];

  return {
    provider,
    title: conversationTitle,
    sourceUrl,
    capturedAt: new Date().toISOString(),
    messages,
  };
}

export function buildEncryptedHeaderPayload(payload) {
  const messageTexts = payload.messages.map((message) => message.content || '').filter(Boolean);
  const searchText = messageTexts.join('\n\n');

  return {
    version: 2,
    provider: payload.provider,
    title: payload.title,
    sourceUrl: payload.sourceUrl,
    capturedAt: payload.capturedAt,
    project: null,
    tags: [],
    messageCount: payload.messages.length,
    snippet: truncate(messageTexts[0] || '', 280),
    searchText: truncate(searchText, 20000),
  };
}

export function buildEncryptedBodyPayload(payload) {
  return {
    version: 2,
    provider: payload.provider,
    title: payload.title,
    sourceUrl: payload.sourceUrl,
    capturedAt: payload.capturedAt,
    messages: payload.messages,
  };
}

export function hasShareContent({ title = '', text = '', url = '' } = {}) {
  return !!(cleanText(title) || cleanText(text) || cleanText(url));
}
