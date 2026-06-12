import DOMPurify from 'dompurify';
import { marked } from 'marked';

marked.setOptions({ breaks: true, gfm: true });

const PURIFY_OPTIONS = {
  ADD_TAGS: ['img', 'thead', 'tbody', 'tr', 'th', 'td', 'details', 'summary'],
  ADD_ATTR: ['target', 'rel', 'class', 'data-language', 'data-memoriq-broken-image', 'data-memoriq-rich-type', 'colspan', 'rowspan', 'align'],
};

function flattenContent(content) {
  if (Array.isArray(content)) return content.join('\n');
  return content || '';
}

function escapeHtml(text) {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function plainTextToHtml(text) {
  const trimmed = (text || '').trim();
  if (!trimmed) return '';

  return trimmed
    .split(/\n{2,}/)
    .map((paragraph) => `<p>${escapeHtml(paragraph).replace(/\n/g, '<br>')}</p>`)
    .join('');
}

function looksLikeMarkdown(text) {
  if (!text || typeof text !== 'string') return false;
  return /(^|\n)(#{1,6}\s|[-*+]\s|\d+\.\s|```|>\s|\|.+\|)/m.test(text);
}

function isArtifactLabelText(text) {
  const normalized = (text || '').replace(/\s+/g, ' ').trim();
  if (!normalized) return false;

  return normalized === 'V'
    || /^visualize\s+show_widget$/i.test(normalized)
    || /^show_widget$/i.test(normalized);
}

function isArtifactLabelBlock(text) {
  const lines = (text || '').split('\n').map((line) => line.replace(/\s+/g, ' ').trim()).filter(Boolean);
  if (!lines.length) return false;

  return lines.every((line) => isArtifactLabelText(line));
}

function isProviderSpeakerLabel(text) {
  return /^(you|gemini|grok)\s+said$/i.test((text || '').replace(/\s+/g, ' ').trim());
}

function stripArtifactLabelsFromHtml(html) {
  if (!html) return html;

  const doc = new DOMParser().parseFromString(`<div id="memoriq-root">${html}</div>`, 'text/html');
  const root = doc.getElementById('memoriq-root');
  if (!root) return html;

  root.querySelectorAll('p, div, span, pre, code, li, h1, h2, h3, h4, h5, h6').forEach((element) => {
    if (element.closest('.memoriq-rich-placeholder')) return;
    if (element.querySelector('.memoriq-rich-placeholder, table, img')) return;

    const text = element.textContent || '';
    if (isArtifactLabelText(text) || isArtifactLabelBlock(text) || isProviderSpeakerLabel(text)) {
      element.remove();
    }
  });

  return root.innerHTML;
}

function stripProviderSpeakerLabelsFromText(text) {
  return (text || '')
    .split('\n')
    .filter((line) => !isProviderSpeakerLabel(line))
    .join('\n')
    .trim();
}

export function messagePlainText(message) {
  if (!message) return '';
  if (message.html) return flattenContent(message.content);
  if (message.markdown) return message.markdown;
  return flattenContent(message.content);
}

export function renderMessageHtml(message) {
  if (!message) return '';

  let raw = '';

  if (message.html) {
    raw = stripArtifactLabelsFromHtml(message.html);
  } else if (message.markdown) {
    raw = marked.parse(message.markdown);
  } else {
    const text = stripProviderSpeakerLabelsFromText(flattenContent(message.content));
    if (!text) return '';
    raw = looksLikeMarkdown(text) ? marked.parse(text) : plainTextToHtml(text);
  }

  return DOMPurify.sanitize(raw, PURIFY_OPTIONS);
}

export function messageIsRich(message) {
  if (!message) return false;
  return !!(message.html || message.markdown || looksLikeMarkdown(flattenContent(message.content)));
}
