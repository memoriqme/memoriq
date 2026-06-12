<template>
  <memoriq-app-layout>
    <section class="share-page">
      <div class="settings-card share-card">
        <p class="eyebrow">Mobile share</p>
        <h1>Save to Memoriq</h1>

        <p v-if="!hasContent" class="muted">
          No shared text was received. Share a chat excerpt from your AI app, then choose Memoriq from the Android share menu.
        </p>

        <template v-else-if="!encryption.configured">
          <p>Set up your encrypted vault on the dashboard before saving shared chats.</p>
          <router-link class="button button-primary" to="/dashboard">Open dashboard</router-link>
        </template>

        <template v-else-if="!encryption.isUnlocked">
          <p>Unlock your vault to encrypt and save this shared chat.</p>
          <form @submit.prevent="unlockVault">
            <label class="form-field">
              Encryption password
              <input v-model="unlockPassword" type="password" autocomplete="current-password" required />
            </label>
            <label class="check-field">
              <input v-model="rememberDevice" type="checkbox" />
              Keep unlocked on this browser
            </label>
            <div v-if="unlockError" class="form-alert">{{ unlockError }}</div>
            <button class="button button-primary" type="submit" :disabled="encryption.loading">Unlock vault</button>
          </form>
        </template>

        <template v-else>
          <p class="muted">Review what was shared, then save it encrypted to your vault.</p>

          <div class="share-preview">
            <div class="share-preview-row">
              <span class="share-preview-label">Title</span>
              <strong>{{ preview.title }}</strong>
            </div>
            <div class="share-preview-row">
              <span class="share-preview-label">Source</span>
              <span class="badge" :class="preview.provider === 'unknown' ? '' : `source-${preview.provider}`">
                {{ sourceLabel(preview.provider) }}
              </span>
            </div>
            <div v-if="preview.sourceUrl" class="share-preview-row">
              <span class="share-preview-label">Link</span>
              <a :href="preview.sourceUrl" target="_blank" rel="noopener noreferrer">{{ preview.sourceUrl }}</a>
            </div>
            <div class="share-preview-body">{{ preview.content }}</div>
          </div>

          <div v-if="saveError" class="form-alert">{{ saveError }}</div>
          <div v-if="saveSuccess" class="form-success">Saved to your encrypted vault.</div>

          <div class="share-actions">
            <button class="button button-primary" type="button" :disabled="saving" @click="saveSharedChat">
              {{ saving ? 'Saving…' : 'Save to Memoriq' }}
            </button>
            <router-link class="button button-ghost" to="/dashboard">Open dashboard</router-link>
          </div>
        </template>
      </div>
    </section>
  </memoriq-app-layout>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import MemoriqAppLayout from '../layouts/MemoriqAppLayout.vue';
import { sources } from '../memoriq/demoData';
import { useEncryptionStore } from '../stores/encryptionStore';
import {
  buildEncryptedBodyPayload,
  buildEncryptedHeaderPayload,
  buildShareConversation,
  hasShareContent,
} from '../utils/shareCapture';

const encryption = useEncryptionStore();
const route = useRoute();
const router = useRouter();

const unlockPassword = ref('');
const rememberDevice = ref(false);
const unlockError = ref('');
const saveError = ref('');
const saveSuccess = ref(false);
const saving = ref(false);

const shareInput = computed(() => ({
  title: typeof route.query.title === 'string' ? route.query.title : '',
  text: typeof route.query.text === 'string' ? route.query.text : '',
  url: typeof route.query.url === 'string' ? route.query.url : '',
}));

const hasContent = computed(() => hasShareContent(shareInput.value));

const preview = computed(() => {
  const payload = buildShareConversation(shareInput.value);
  return {
    title: payload.title,
    provider: payload.provider,
    sourceUrl: safeExternalUrl(payload.sourceUrl),
    content: payload.messages[0]?.content || '',
  };
});

onMounted(async () => {
  await encryption.fetchStatus();
});

function safeExternalUrl(value) {
  try {
    const url = new URL(value);
    return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
  } catch (error) {
    return '';
  }
}

function sourceLabel(id) {
  return sources.find((source) => source.id === id)?.label || 'Unknown source';
}

async function unlockVault() {
  unlockError.value = '';

  try {
    await encryption.unlock(unlockPassword.value, rememberDevice.value);
    unlockPassword.value = '';
  } catch (error) {
    unlockError.value = encryption.error || 'Could not unlock vault.';
  }
}

async function saveSharedChat() {
  if (!hasContent.value || saving.value) return;

  saveError.value = '';
  saveSuccess.value = false;
  saving.value = true;

  try {
    const payload = buildShareConversation(shareInput.value);
    const encryptedHeader = await encryption.encrypt(buildEncryptedHeaderPayload(payload));
    const encryptedBody = await encryption.encrypt(buildEncryptedBodyPayload(payload));

    await axios.post('/api/conversations', {
      encrypted_header: encryptedHeader,
      encrypted_body: encryptedBody,
    });

    saveSuccess.value = true;
    setTimeout(() => {
      router.push({ name: 'Dashboard' });
    }, 700);
  } catch (error) {
    saveError.value = error.response?.data?.message || error.message || 'Unable to save this shared chat.';
  } finally {
    saving.value = false;
  }
}
</script>
