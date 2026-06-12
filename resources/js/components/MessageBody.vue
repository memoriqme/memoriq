<template>
  <div v-if="rendered" class="markdown-body" v-html="rendered"></div>
</template>

<script setup>
import DOMPurify from 'dompurify';
import { computed } from 'vue';
import { renderMessageHtml } from '../utils/renderMessage';
import { highlightHtml } from '../utils/searchText';

const PURIFY_OPTIONS = {
  ADD_TAGS: ['img', 'thead', 'tbody', 'tr', 'th', 'td', 'details', 'summary', 'mark'],
  ADD_ATTR: ['target', 'rel', 'class', 'data-language', 'data-memoriq-broken-image', 'data-memoriq-rich-type', 'colspan', 'rowspan', 'align'],
};

const props = defineProps({
  message: {
    type: Object,
    required: true,
  },
  highlight: {
    type: String,
    default: '',
  },
});

const rendered = computed(() => {
  let html = renderMessageHtml(props.message);
  const query = props.highlight?.trim();

  if (query) {
    html = highlightHtml(html, query);
    html = DOMPurify.sanitize(html, PURIFY_OPTIONS);
  }

  return html;
});
</script>
