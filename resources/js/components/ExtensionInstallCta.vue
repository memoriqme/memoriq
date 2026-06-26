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
        :href="CHROME_EXTENSION_URL"
        class="extension-cta-btn extension-cta-btn-primary"
        target="_blank"
        rel="noopener noreferrer"
      >
        <svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
          <path fill="currentColor" d="M12 0C8.21 0 4.831 1.757 2.632 4.501l3.953 6.848A5.454 5.454 0 0 1 12 6.545h10.691A12 12 0 0 0 12 0zM1.931 5.47A11.943 11.943 0 0 0 0 12c0 6.012 4.42 10.991 10.189 11.864l3.953-6.847a5.45 5.45 0 0 1-6.865-2.29zm13.342 2.166a5.446 5.446 0 0 1 1.45 7.09l.002.001h-.002l-5.344 9.257c.206.01.413.016.621.016 6.627 0 12-5.373 12-12 0-1.54-.29-3.011-.818-4.364zM12 16.364a4.364 4.364 0 1 1 0-8.728 4.364 4.364 0 0 1 0 8.728Z"/>
        </svg>
        Chrome
      </a>
      <a
        :href="FIREFOX_EXTENSION_URL"
        class="extension-cta-btn extension-cta-btn-ghost"
        target="_blank"
        rel="noopener noreferrer"
      >
        <svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
          <path fill="currentColor" d="M20.452 3.445a11.002 11.002 0 00-2.482-1.908C16.944.997 15.098.093 12.477.032c-.734-.017-1.457.03-2.174.144-.72.114-1.398.292-2.118.56-1.017.377-1.996.975-2.574 1.554.583-.349 1.476-.733 2.55-.992a10.083 10.083 0 013.729-.167c2.341.34 4.178 1.381 5.48 2.625a8.066 8.066 0 011.298 1.587c1.468 2.382 1.33 5.376.184 7.142-.85 1.312-2.67 2.544-4.37 2.53-.583-.023-1.438-.152-2.25-.566-2.629-1.343-3.021-4.688-1.118-6.306-.632-.136-1.82.13-2.646 1.363-.742 1.107-.7 2.816-.242 4.028a6.473 6.473 0 01-.59-1.895 7.695 7.695 0 01.416-3.845A8.212 8.212 0 019.45 5.399c.896-1.069 1.908-1.72 2.75-2.005-.54-.471-1.411-.738-2.421-.767C8.31 2.583 6.327 3.061 4.7 4.41a8.148 8.148 0 00-1.976 2.414c-.455.836-.691 1.659-.697 1.678.122-1.445.704-2.994 1.248-4.055-.79.413-1.827 1.668-2.41 3.042C.095 9.37-.2 11.608.14 13.989c.966 5.668 5.9 9.982 11.843 9.982C18.62 23.971 24 18.591 24 11.956a11.93 11.93 0 00-3.548-8.511z"/>
        </svg>
        Firefox
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
  CHROME_EXTENSION_URL,
  EXTENSION_CONNECT_URL,
  FIREFOX_EXTENSION_URL,
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
    default: 'Install the Memoriq browser extension to capture conversations in one click. Everything is encrypted on your device before upload.',
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

.extension-cta-browser-icon {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
  margin-right: 6px;
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
