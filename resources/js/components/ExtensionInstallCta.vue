<template>
  <div class="extension-cta" :class="[`extension-cta--${variant}`, { 'extension-cta--dismissible': dismissible }]">
    <button
      v-if="dismissible"
      type="button"
      class="extension-cta-dismiss"
      aria-label="Dismiss"
      @click="$emit('dismiss')"
    >
      ×
    </button>

    <div v-if="showIcon" class="extension-cta-icon" aria-hidden="true">🧩</div>

    <div class="extension-cta-copy">
      <p v-if="eyebrow" class="extension-cta-eyebrow">{{ eyebrow }}</p>
      <h3 v-if="title" class="extension-cta-title">{{ title }}</h3>
      <p class="extension-cta-desc">{{ description }}</p>
    </div>

    <div class="extension-cta-actions">
      <a
        :href="installUrl"
        class="extension-cta-btn extension-cta-btn-primary"
        target="_blank"
        rel="noopener noreferrer"
      >
        {{ installLabel }}
      </a>
      <a
        v-if="showConnect"
        :href="EXTENSION_CONNECT_URL"
        class="extension-cta-btn extension-cta-btn-ghost"
      >
        Connect extension
      </a>
    </div>
  </div>
</template>

<script setup>
import {
  EXTENSION_CONNECT_URL,
  extensionInstallLabel,
  extensionInstallUrl,
} from '../memoriq/links';

defineProps({
  variant: {
    type: String,
    default: 'card',
  },
  eyebrow: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    default: 'Save chats from ChatGPT, Claude, Gemini, and Grok',
  },
  description: {
    type: String,
    default: 'Install the Memoriq Chrome extension to capture conversations in one click. Everything is encrypted on your device before upload.',
  },
  showConnect: {
    type: Boolean,
    default: false,
  },
  showIcon: {
    type: Boolean,
    default: true,
  },
  dismissible: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['dismiss']);

const installUrl = extensionInstallUrl();
const installLabel = extensionInstallLabel();
</script>

<style scoped>
.extension-cta {
  display: grid;
  gap: 14px;
  border: 1px solid rgba(16, 163, 127, 0.28);
  border-radius: 14px;
  background: var(--accent-soft);
  padding: 18px 20px;
}

.extension-cta--dismissible {
  position: relative;
  padding-right: 44px;
}

.extension-cta-dismiss {
  position: absolute;
  top: 10px;
  right: 10px;
  display: grid;
  width: 28px;
  height: 28px;
  place-items: center;
  border: 0;
  border-radius: 8px;
  background: transparent;
  color: var(--text-muted);
  cursor: pointer;
  font-size: 20px;
  line-height: 1;
}

.extension-cta-dismiss:hover {
  background: rgba(255, 255, 255, 0.06);
  color: var(--text);
}

.extension-cta-icon {
  font-size: 22px;
  line-height: 1;
}

.extension-cta-eyebrow {
  margin: 0 0 6px;
  color: var(--accent);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.extension-cta-title {
  margin: 0 0 6px;
  color: var(--text);
  font-size: 16px;
  font-weight: 600;
  letter-spacing: -0.01em;
  line-height: 1.3;
}

.extension-cta-desc {
  margin: 0;
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.6;
}

.extension-cta-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.extension-cta-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  padding: 10px 16px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.15s, box-shadow 0.15s, transform 0.12s;
}

.extension-cta-btn:active {
  transform: scale(0.98);
}

.extension-cta-btn-primary {
  border: 0;
  background: var(--accent);
  color: #fff;
}

.extension-cta-btn-primary:hover {
  background: var(--accent-hover);
  box-shadow: 0 4px 20px rgba(16, 163, 127, 0.25);
}

.extension-cta-btn-ghost {
  border: 1px solid var(--border);
  background: var(--surface);
  color: var(--text-secondary);
}

.extension-cta-btn-ghost:hover {
  background: var(--bg-elevated);
  color: var(--text);
}

.extension-cta--banner {
  grid-template-columns: auto 1fr auto;
  align-items: center;
}

.extension-cta--banner .extension-cta-copy {
  min-width: 0;
}

.extension-cta--banner .extension-cta-actions {
  justify-content: flex-end;
}

.extension-cta--inline {
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 16px;
  padding: 14px 18px;
}

.extension-cta--inline .extension-cta-title {
  font-size: 15px;
}

.extension-cta--inline .extension-cta-desc {
  font-size: 13px;
}

.extension-cta--compact {
  gap: 10px;
  padding: 14px 16px;
}

.extension-cta--compact .extension-cta-title {
  font-size: 14px;
}

.extension-cta--compact .extension-cta-desc {
  font-size: 13px;
}

@media (max-width: 768px) {
  .extension-cta--banner,
  .extension-cta--inline {
    grid-template-columns: 1fr;
  }

  .extension-cta--banner .extension-cta-actions,
  .extension-cta--inline .extension-cta-actions {
    justify-content: flex-start;
  }
}
</style>
