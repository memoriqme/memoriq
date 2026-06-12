<template>
  <memoriq-app-layout>
    <div v-if="recoveryModalOpen" class="modal-backdrop" role="presentation">
      <section class="recovery-modal" role="dialog" aria-modal="true" aria-labelledby="recovery-title">
        <memoriq-logo class="modal-mark" :size="48" />
        <p class="eyebrow">Encrypted vault created</p>
        <h1 id="recovery-title">Save your recovery key</h1>
        <p>
          Store this in your password manager. It is the only backup if you forget your Memoriq encryption password.
        </p>

        <textarea
          ref="recoveryKeyTextarea"
          class="recovery-key-box"
          readonly
          :value="recoveryKey"
          @click="selectRecoveryKeyTextarea"
          @focus="selectRecoveryKeyTextarea"
        ></textarea>

        <div class="modal-actions">
          <button class="button button-primary" type="button" @click="closeRecoveryModal">
            I saved it, continue
          </button>
        </div>
        <p class="recovery-extension-note">
          Next step: install the
          <a :href="extensionInstallUrl()" target="_blank" rel="noopener noreferrer">{{ extensionInstallLabel() }}</a>
          to start saving chats from ChatGPT, Claude, Gemini, or Grok.
        </p>
      </section>
    </div>

    <div v-if="pasteModalOpen" class="modal-backdrop" role="presentation">
      <section class="paste-reply-modal" role="dialog" aria-modal="true" aria-labelledby="paste-reply-title">
        <memoriq-logo class="modal-mark" :size="48" />
        <p class="eyebrow">Manual save</p>
        <h1 id="paste-reply-title">Paste an AI reply</h1>
        <p>
          Save a single assistant response as an encrypted Memoriq chat. Choose the source, paste the reply, and adjust the title if needed.
        </p>

        <form class="paste-reply-form" @submit.prevent="savePastedReply">
          <label class="form-field">
            AI source
            <select v-model="pasteForm.source" required>
              <option v-for="source in sources" :key="source.id" :value="source.id">{{ source.label }}</option>
            </select>
          </label>

          <label class="form-field">
            Title
            <input
              v-model="pasteForm.title"
              type="text"
              maxlength="200"
              required
              @input="pasteTitleTouched = true"
            />
          </label>

          <label class="form-field">
            AI reply
            <textarea
              v-model="pasteForm.content"
              class="paste-reply-textarea"
              placeholder="Paste the AI reply here..."
              required
            ></textarea>
          </label>

          <div v-if="pasteError" class="form-alert">{{ pasteError }}</div>

          <div class="modal-actions">
            <button class="button button-primary" type="submit" :disabled="pasteSaving">
              {{ pasteSaving ? 'Saving...' : 'Save reply' }}
            </button>
            <button class="button button-ghost" type="button" :disabled="pasteSaving" @click="closePasteModal">
              Cancel
            </button>
          </div>
        </form>
      </section>
    </div>

    <div v-if="projectModal.open" class="modal-backdrop" role="presentation">
      <section class="project-modal" role="dialog" aria-modal="true" aria-labelledby="project-modal-title">
        <div class="modal-mark">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          </svg>
        </div>
        <p class="eyebrow">Projects</p>
        <h1 id="project-modal-title">{{ projectModalTitle }}</h1>
        <p>{{ projectModalDescription }}</p>

        <form class="project-form" @submit.prevent="confirmProjectModal">
          <label v-if="projectModal.mode === 'create' || projectModal.mode === 'rename'" class="form-field">
            Project name
            <input v-model="projectModal.name" type="text" maxlength="80" required />
          </label>

          <template v-if="projectModal.mode === 'move'">
            <label class="form-field">
              Existing project
              <select v-model="projectModal.targetProject">
                <option value="">Unsorted</option>
                <option v-for="project in projects" :key="project" :value="project">{{ project }}</option>
                <option value="__new__">Create new project...</option>
              </select>
            </label>

            <label v-if="projectModal.targetProject === '__new__'" class="form-field">
              New project name
              <input v-model="projectModal.name" type="text" maxlength="80" required />
            </label>
          </template>

          <div v-if="projectModal.mode === 'delete'" class="form-alert">
            {{ projectCount(projectModal.projectName) }} chats will move to Unsorted. The chats will not be deleted.
          </div>

          <div v-if="projectModal.error" class="form-alert">{{ projectModal.error }}</div>

          <div class="modal-actions">
            <button class="button" :class="projectModal.mode === 'delete' ? 'button-danger' : 'button-primary'" type="submit" :disabled="!!projectUpdating || !!movingId">
              {{ projectModalConfirmLabel }}
            </button>
            <button class="button button-ghost" type="button" :disabled="!!projectUpdating || !!movingId" @click="closeProjectModal">
              Cancel
            </button>
          </div>
        </form>
      </section>
    </div>

    <div class="dashboard-grid" :class="{ 'dashboard-grid--conversation-active': activeSearchQuery || selectedConversation || isSettingsRoute || !encryption.isUnlocked }">
      <section class="memory-panel">
        <template v-if="!encryption.isUnlocked">
          <div class="panel-header">
            <div class="panel-header-top">
              <app-brand />
              <app-menu-button />
            </div>
          </div>
          <div class="vault-sidebar-notice">
            <p class="eyebrow">{{ encryption.configured ? 'Vault locked' : 'Vault setup' }}</p>
            <h2>{{ encryption.configured ? 'Unlock required' : 'Create your vault' }}</h2>
            <p>
              {{ encryption.configured
                ? 'Your chat list is encrypted on this device. Unlock the vault to show conversations here.'
                : 'Set up encryption before saving and browsing conversations.' }}
            </p>
          </div>
        </template>

        <template v-else>
        <div class="panel-header">
          <div class="panel-header-top">
            <app-brand />
            <app-menu-button />
          </div>
          <div class="panel-header-copy">
            <p class="eyebrow">Your AI Memory</p>
            <div class="stats-row panel-stats-row">
              <span>{{ pluralize(pagination.total || conversations.length, 'conversation', 'conversations') }}</span>
              <span>{{ pluralize(projects.length, 'project', 'projects') }}</span>
              <span>{{ pluralize(sources.length, 'source', 'sources') }}</span>
            </div>
          </div>
        </div>

        <extension-install-cta
          v-if="showExtensionBanner"
          class="dashboard-extension-banner"
          variant="compact"
          eyebrow="Get started"
          title="Install the Chrome extension"
          description="Save conversations from ChatGPT, Claude, Gemini, or Grok in one click."
          show-connect
          dismissible
          @dismiss="dismissExtensionBanner"
        />

        <label class="search-field">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input v-model="searchQuery" type="search" placeholder="Search your saved chats..." />
        </label>

        <button class="button button-ghost paste-reply-button" type="button" @click="openPasteModal">
          Paste AI reply
        </button>

        <div class="projects-block">
          <div class="projects-heading">
            <p>Projects</p>
            <button class="text-button project-new-button" type="button" @click="openCreateProjectModal">New</button>
          </div>
          <div class="project-list">
            <button class="project-item" :class="{ active: activeProject === NO_PROJECT }" type="button" @click="setProjectFilter(NO_PROJECT)">
              <span class="project-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 5h14v14H5z"/>
                  <circle cx="8" cy="12" r="1.4" fill="currentColor" stroke="none"/>
                  <circle cx="12" cy="12" r="1.6" fill="currentColor" stroke="none"/>
                  <circle cx="16" cy="12" r="1.4" fill="currentColor" stroke="none"/>
                </svg>
              </span>
              <span>Unsorted</span>
              <small>{{ noProjectCount }}</small>
            </button>
            <div
              v-for="project in projects"
              :key="project"
              class="project-item-row"
              :class="{ active: activeProject === project }"
            >
              <button class="project-item" :class="{ active: activeProject === project }" type="button" @click="setProjectFilter(project)">
                <span class="project-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                  </svg>
                </span>
                <span>{{ project }}</span>
                <small>{{ projectCount(project) }}</small>
              </button>
              <div class="project-item-actions">
                <button
                  class="project-action"
                  type="button"
                  title="Rename project"
                  aria-label="Rename project"
                  :disabled="!!projectUpdating || !!movingId"
                  @click.stop="openRenameProjectModal(project)"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                  </svg>
                </button>
                <button
                  class="project-action project-action--danger"
                  type="button"
                  title="Delete project"
                  aria-label="Delete project"
                  :disabled="!!projectUpdating || !!movingId"
                  @click.stop="openDeleteProjectModal(project)"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 6h18M8 6V4h8v2M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                    <path d="M10 11v6M14 11v6"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="filter-block">
          <p>AI Sources</p>
          <div class="chip-row">
            <button v-for="source in sources" :key="source.id" class="chip source-chip" :class="{ active: activeSource === source.id }" type="button" @click="toggleSource(source.id)">
              <span class="source-dot" :class="source.id"></span>
              {{ source.label }}
            </button>
          </div>
        </div>

        <button v-if="hasFilters" class="text-button clear-button" type="button" @click="clearFilters">Clear filters</button>

        <div v-if="deleteError" class="form-alert conversation-delete-alert">{{ deleteError }}</div>

        <div class="conversation-list">
          <div
            v-for="conversation in filteredConversations"
            :key="conversation.id"
            class="conversation-item-row"
            :class="{ active: selectedId === conversation.id }"
              role="button"
              tabindex="0"
              @click="selectConversation(conversation.id)"
              @keydown.enter.prevent="selectConversation(conversation.id)"
              @keydown.space.prevent="selectConversation(conversation.id)"
          >
            <button
              class="conversation-item"
              :class="{ active: selectedId === conversation.id }"
              type="button"
              @click="selectConversation(conversation.id)"
            >
              <span>{{ conversation.title }}</span>
            </button>
            <div class="conversation-item-meta">
              <span>
                <span class="source-dot" :class="conversation.source"></span>
                {{ sourceLabel(conversation.source) }} · {{ formatDate(conversation.archivedAt) }}
              </span>
              <div class="conversation-item-actions">
                <button
                  class="conversation-move"
                  type="button"
                  title="Move to project"
                  aria-label="Move to project"
                  :disabled="movingId === conversation.id"
                  @click.stop="openMoveProjectModal(conversation)"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <path d="M12 11v5M9.5 13.5 12 11l2.5 2.5"/>
                  </svg>
                </button>
                <button
                  class="conversation-delete"
                  type="button"
                  title="Delete chat"
                  aria-label="Delete chat"
                  :disabled="deletingId === conversation.id"
                  @click.stop="deleteConversation(conversation)"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 6h18M8 6V4h8v2M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                    <path d="M10 11v6M14 11v6"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="!filteredConversations.length" class="empty-state">
            <extension-install-cta
              v-if="!conversations.length && !conversationsLoading && extensionBannerDismissed"
              variant="compact"
              eyebrow="No conversations yet"
              title="Save your first chat"
              description="Install the extension and capture a conversation from ChatGPT, Claude, Gemini, or Grok."
              show-connect
            />
            <p v-else class="empty-card">
              {{ conversations.length ? 'No conversations match your filters.' : conversationsLoading ? 'Loading conversations…' : 'No saved conversations yet.' }}
            </p>
          </div>

          <button v-if="hasMoreConversations" class="button button-ghost load-more-button" type="button" :disabled="conversationsLoading" @click="loadMoreConversations">
            {{ conversationsLoading ? 'Loading…' : 'Load more' }}
          </button>
        </div>
        </template>
      </section>

      <section v-if="isSettingsRoute" class="conversation-panel conversation-panel--settings">
        <settings-panel embedded />
      </section>

      <section v-else-if="!encryption.configured" class="conversation-panel conversation-panel--centered">
        <div class="settings-card vault-card">
          <p class="eyebrow">Private by default</p>
          <h1>Set your Memoriq encryption password</h1>
          <p>
            Memoriq encrypts chats on your device before upload. This password unlocks your private vault;
            it is never sent to the server and cannot be recovered by Memoriq.
          </p>
          <ul class="best-practices">
            <li>Use a long phrase you can remember, not a reused website password.</li>
            <li>Save the recovery key shown after setup in your password manager.</li>
            <li>If you lose both this password and recovery key, encrypted chats cannot be restored.</li>
          </ul>
          <form @submit.prevent="setupEncryption">
            <label class="form-field">
              Encryption password
              <input v-model="setupForm.password" type="password" autocomplete="new-password" required minlength="12" />
            </label>
            <label class="form-field">
              Confirm encryption password
              <input v-model="setupForm.confirm" type="password" autocomplete="new-password" required minlength="12" />
            </label>
            <label class="check-field">
              <input v-model="setupForm.remember" type="checkbox" />
              Keep unlocked on this browser
            </label>
            <div v-if="setupError" class="form-alert">{{ setupError }}</div>
            <button class="button button-primary" type="submit" :disabled="encryption.loading">Create encrypted vault</button>
          </form>
        </div>
      </section>

      <section v-else-if="!encryption.isUnlocked" class="conversation-panel conversation-panel--centered">
        <div class="settings-card vault-card">
          <p class="eyebrow">Vault locked</p>
          <h1>Unlock your Memoriq vault</h1>
          <p>Your saved conversations are encrypted. Enter your Memoriq encryption password to decrypt them on this device.</p>
          <form @submit.prevent="unlockVault">
            <label class="form-field">
              Encryption password
              <input v-model="unlockForm.password" type="password" autocomplete="current-password" required />
            </label>
            <label class="check-field">
              <input v-model="unlockForm.remember" type="checkbox" />
              Keep unlocked on this browser
            </label>
            <div v-if="unlockError" class="form-alert">{{ unlockError }}</div>
            <button class="button button-primary" type="submit" :disabled="encryption.loading">Unlock vault</button>
          </form>

          <div class="vault-reset-section">
            <button class="text-button" type="button" @click="resetPanelOpen = !resetPanelOpen">
              Forgot encryption password?
            </button>

            <div v-if="resetPanelOpen" class="vault-reset-panel">
              <div class="form-alert">
                Your Memoriq encryption password cannot be recovered by Memoriq. If you have your recovery key,
                you can set a new password and keep your saved chats. Without the recovery key, resetting starts
                a new vault and permanently deletes currently saved encrypted chats.
              </div>

              <form class="reset-form" @submit.prevent="resetWithRecoveryKey">
                <h3>Reset with recovery key</h3>
                <p class="muted">Keeps your existing saved chats. Use the recovery key you copied during setup.</p>
                <label class="form-field">
                  Recovery key
                  <textarea v-model="resetForm.recoveryKey" class="recovery-key-box compact" required></textarea>
                </label>
                <label class="form-field">
                  New encryption password
                  <input v-model="resetForm.password" type="password" autocomplete="new-password" required minlength="12" />
                </label>
                <label class="form-field">
                  Confirm new password
                  <input v-model="resetForm.confirm" type="password" autocomplete="new-password" required minlength="12" />
                </label>
                <label class="check-field">
                  <input v-model="resetForm.remember" type="checkbox" />
                  Keep unlocked on this browser
                </label>
                <button class="button button-primary" type="submit" :disabled="encryption.loading">Set new password</button>
              </form>

              <form class="reset-form danger-reset" @submit.prevent="startOverVault">
                <h3>Start over without recovery key</h3>
                <p>
                  This deletes all currently saved encrypted conversations and creates a new empty vault.
                  Only use this if you do not have the recovery key.
                </p>
                <label class="form-field">
                  New encryption password
                  <input v-model="startOverForm.password" type="password" autocomplete="new-password" required minlength="12" />
                </label>
                <label class="form-field">
                  Confirm new password
                  <input v-model="startOverForm.confirm" type="password" autocomplete="new-password" required minlength="12" />
                </label>
                <label class="check-field">
                  <input v-model="startOverForm.confirmDelete" type="checkbox" required />
                  I understand my existing encrypted chats will be permanently deleted.
                </label>
                <button class="button button-danger" type="submit" :disabled="encryption.loading">Delete chats and create new vault</button>
              </form>

              <div v-if="resetError" class="form-alert">{{ resetError }}</div>
            </div>
          </div>
        </div>
      </section>

      <section v-else-if="activeSearchQuery || selectedConversation" class="conversation-panel">
        <div class="conversation-sticky-controls">
          <button class="brand-mark-button" type="button" aria-label="Back to dashboard" @click="goToDashboardHome">
            <memoriq-logo />
          </button>
          <app-menu-button />
        </div>

        <template v-if="activeSearchQuery">
          <header class="conversation-header">
            <div>
              <p class="eyebrow">Search</p>
              <h2>Search results</h2>
            </div>
            <div class="stats-row">
              <span>{{ searchResults.length }} {{ searchResults.length === 1 ? 'match' : 'matches' }}</span>
            </div>
          </header>

          <div v-if="!searchResults.length" class="empty-card">
            No saved chats match “{{ activeSearchQuery }}”.
          </div>

          <div v-else class="search-results-list">
            <button
              v-for="result in searchResults"
              :key="result.id"
              class="search-result-card"
              type="button"
              @click="openSearchResult(result)"
            >
              <div class="search-result-head">
                <h3>{{ result.title }}</h3>
                <span class="badge" :class="'source-' + result.source">{{ sourceLabel(result.source) }}</span>
              </div>
              <p class="search-result-meta">
                <span v-if="result.role">{{ result.role === 'user' ? 'You' : sourceLabel(result.source) }}</span>
                <span v-else>{{ result.isTitleMatch ? 'Title match' : 'Conversation excerpt' }}</span>
                · {{ formatDate(result.archivedAt) }}
                <span v-if="result.project"> · {{ result.project }}</span>
              </p>
              <p class="search-result-snippet" v-html="result.snippetHtml"></p>
            </button>
          </div>
        </template>

        <template v-else>
          <header class="conversation-header">
            <div>
              <div class="conversation-title-row">
                <input
                  v-if="renamingId === selectedConversation.id"
                  ref="titleInputRef"
                  v-model="titleDraft"
                  class="conversation-title-input"
                  type="text"
                  maxlength="200"
                  aria-label="Chat title"
                  @keydown.enter.prevent="saveTitleRename(selectedConversation)"
                  @keydown.esc.prevent="cancelTitleRename"
                  @blur="saveTitleRename(selectedConversation)"
                />
                <template v-else>
                  <h2>{{ selectedConversation.title }}</h2>
                  <button
                    class="conversation-title-edit"
                    type="button"
                    title="Rename chat"
                    aria-label="Rename chat"
                    :disabled="!!movingId"
                    @click="startTitleRename(selectedConversation)"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                    </svg>
                  </button>
                </template>
              </div>
            </div>
            <div class="meta-row">
              <span class="badge" :class="'source-' + selectedConversation.source">{{ sourceLabel(selectedConversation.source) }}</span>
              <span class="badge">{{ selectedConversation.project }}</span>
              <span class="badge">{{ formatDateFull(selectedConversation.archivedAt) }}</span>
              <span class="badge">{{ formatBytes(selectedConversation.bodyBytes) }}</span>
              <a
                v-if="selectedConversation.header?.sourceUrl"
                class="badge metadata-link"
                :href="selectedConversation.header.sourceUrl"
                target="_blank"
                rel="noopener noreferrer"
              >
                Original chat
              </a>
            </div>
          </header>

          <div class="archived-banner">
            End-to-end encrypted · Decrypted locally on this device
          </div>

          <div v-if="bodyLoadingId === selectedConversation.id" class="empty-card">
            Loading encrypted messages…
          </div>

          <div v-else-if="bodyError" class="form-alert conversation-delete-alert">{{ bodyError }}</div>

          <div
            v-for="(message, index) in selectedConversation.messages || []"
            :key="index"
            :ref="(el) => setMessageRef(index, el)"
            class="message-group"
            :class="{ 'message-group--focused': focusMessageIndex === index }"
          >
            <div class="message-role" :class="[message.role, message.role === 'assistant' ? selectedConversation.source : '']">
              <span class="avatar">{{ message.role === 'user' ? 'You' : assistantInitial(selectedConversation.source) }}</span>
              <span>{{ message.role === 'user' ? 'You' : sourceLabel(selectedConversation.source) }}</span>
            </div>

            <div v-if="message.role === 'user'" class="message-content user">
              <div class="message-bubble">
                <message-body :message="normalizeMessage(message)" />
              </div>
            </div>
            <message-body v-else :message="normalizeMessage(message)" />
          </div>
        </template>
      </section>

      <section v-else class="conversation-panel conversation-panel--empty">
        <div class="empty-card conversation-empty-state">
          Select a conversation from the sidebar, or search your saved chats.
        </div>
      </section>
    </div>
  </memoriq-app-layout>
</template>

<script setup>
import axios from 'axios';
import { computed, nextTick, onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AppBrand from '../components/AppBrand.vue';
import MemoriqLogo from '../components/MemoriqLogo.vue';
import AppMenuButton from '../components/AppMenuButton.vue';
import MessageBody from '../components/MessageBody.vue';
import SettingsPanel from '../components/SettingsPanel.vue';
import ExtensionInstallCta from '../components/ExtensionInstallCta.vue';
import MemoriqAppLayout from '../layouts/MemoriqAppLayout.vue';
import { sources } from '../memoriq/demoData';
import { extensionInstallLabel, extensionInstallUrl } from '../memoriq/links';
import {
  clearPendingRecoveryKey,
  readPendingRecoveryKey,
} from '../memoriq/recoveryKey';
import { useEncryptionStore } from '../stores/encryptionStore';
import { collectSearchResults, conversationHaystack } from '../utils/searchText';

const encryption = useEncryptionStore();
const EXTENSION_CTA_DISMISSED_KEY = 'memoriq-extension-cta-dismissed';
const route = useRoute();
const router = useRouter();
const isSettingsRoute = computed(() => route.name === 'Settings');
const NO_PROJECT = '__no_project__';
const conversations = ref([]);
const conversationsLoading = ref(false);
const bodyLoadingId = ref(null);
const bodyError = ref('');
const searchQuery = ref('');
const focusMessageIndex = ref(null);
const messageRefs = ref({});
const activeProject = ref(NO_PROJECT);
const activeSource = ref(null);
const selectedId = ref(null);
const deletingId = ref(null);
const movingId = ref(null);
const projectUpdating = ref(null);
const renamingId = ref(null);
const titleDraft = ref('');
const titleInputRef = ref(null);
const deleteError = ref('');
const pasteModalOpen = ref(false);
const pasteSaving = ref(false);
const pasteError = ref('');
const pasteTitleTouched = ref(false);
const pasteForm = reactive({ source: 'chatgpt', title: '', content: '' });
const projectModal = reactive({
  open: false,
  mode: '',
  conversation: null,
  projectName: '',
  name: '',
  targetProject: '',
  error: '',
});
const setupError = ref('');
const unlockError = ref('');
const resetError = ref('');
const recoveryKey = ref('');
const recoveryKeyTextarea = ref(null);
const recoveryModalOpen = ref(false);
const extensionBannerDismissed = ref(localStorage.getItem(EXTENSION_CTA_DISMISSED_KEY) === '1');
const resetPanelOpen = ref(false);
const setupForm = reactive({ password: '', confirm: '', remember: false });
const unlockForm = reactive({ password: '', remember: false });
const resetForm = reactive({ recoveryKey: '', password: '', confirm: '', remember: false });
const startOverForm = reactive({ password: '', confirm: '', confirmDelete: false });
const pagination = reactive({
  currentPage: 0,
  lastPage: 1,
  total: 0,
  perPage: 25,
});
const syncSnapshot = ref(null);
const syncChecking = ref(false);
const VAULT_IMPORTED_EVENT = 'memoriq:vault-imported';
const SYNC_CHECK_INTERVAL_MS = 3000;
let lastSyncCheckAt = 0;

const projects = computed(() => [...new Set(conversations.value.map((conversation) => conversation.project).filter(Boolean))]);
const noProjectCount = computed(() => conversations.value.filter((conversation) => !conversation.project).length);

const hasFilters = computed(() => !!searchQuery.value.trim() || activeProject.value !== NO_PROJECT || activeSource.value);

const showExtensionBanner = computed(
  () =>
    encryption.configured
    && encryption.isUnlocked
    && !isSettingsRoute.value
    && !conversationsLoading.value
    && conversations.value.length === 0
    && !extensionBannerDismissed.value,
);

const sourceFilteredConversations = computed(() =>
  conversations.value.filter((conversation) => {
    if (activeSource.value && conversation.source !== activeSource.value) return false;
    return true;
  }),
);

const chipFilteredConversations = computed(() =>
  sourceFilteredConversations.value.filter((conversation) => {
    if (activeProject.value === NO_PROJECT) return !conversation.project;
    return conversation.project === activeProject.value;
  }),
);

const activeSearchQuery = computed(() => searchQuery.value.trim());

const searchResults = computed(() => collectSearchResults(sourceFilteredConversations.value, activeSearchQuery.value));

const filteredConversations = computed(() => {
  const q = activeSearchQuery.value.toLowerCase();

  if (!q) return chipFilteredConversations.value;

  const matchingIds = new Set(searchResults.value.map((result) => result.conversationId));

  return sourceFilteredConversations.value.filter(
    (conversation) => matchingIds.has(conversation.id) || conversationHaystack(conversation).includes(q),
  );
});

const selectedConversation = computed(() => conversations.value.find((conversation) => conversation.id === selectedId.value) || null);
const hasMoreConversations = computed(() => pagination.currentPage < pagination.lastPage);
const projectModalTitle = computed(() => ({
  create: 'Create project',
  rename: 'Rename project',
  delete: 'Delete project',
  move: 'Move chat',
}[projectModal.mode] || 'Manage project'));
const projectModalDescription = computed(() => {
  if (projectModal.mode === 'create') return selectedConversation.value ? `Create a project and move "${selectedConversation.value.title}" into it.` : 'Select a chat first, then create a project for it.';
  if (projectModal.mode === 'rename') return `Rename "${projectModal.projectName}" for all chats in this project.`;
  if (projectModal.mode === 'delete') return `Remove "${projectModal.projectName}" from your project list.`;
  if (projectModal.mode === 'move') return `Choose where to place "${projectModal.conversation?.title || 'this chat'}".`;
  return '';
});
const projectModalConfirmLabel = computed(() => ({
  create: 'Create project',
  rename: 'Save rename',
  delete: 'Delete project',
  move: 'Move chat',
}[projectModal.mode] || 'Save'));

watch(filteredConversations, (list) => {
  if (activeSearchQuery.value) return;
  if (isMobileDashboard()) return;

  if (list.length && !list.some((conversation) => conversation.id === selectedId.value)) {
    selectedId.value = list[0].id;
  }
});

watch(selectedId, async () => {
  cancelTitleRename();
  messageRefs.value = {};
  if (selectedConversation.value && !activeSearchQuery.value) {
    await loadConversationBody(selectedConversation.value);
  }
  scrollToFocusedMessage();
});

watch(() => pasteForm.content, (content) => {
  if (pasteTitleTouched.value) return;
  pasteForm.title = titleFromPastedReply(content);
});

function setMessageRef(index, el) {
  if (el) {
    messageRefs.value[index] = el;
  } else {
    delete messageRefs.value[index];
  }
}

function scrollToFocusedMessage() {
  if (focusMessageIndex.value === null) return;

  nextTick(() => {
    messageRefs.value[focusMessageIndex.value]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });
}

function selectConversation(id) {
  if (isSettingsRoute.value) {
    router.push({ name: 'Dashboard' });
  }
  focusMessageIndex.value = null;
  selectedId.value = id;
}

function showMobileConversationList() {
  searchQuery.value = '';
  selectedId.value = null;
  focusMessageIndex.value = null;
  bodyError.value = '';
}

function goToDashboardHome() {
  searchQuery.value = '';
  selectedId.value = null;
  focusMessageIndex.value = null;
  bodyError.value = '';
  router.push({ name: 'Dashboard' });
}

function setProjectFilter(project) {
  activeProject.value = project;
  clearMobileSelection();
}

async function openSearchResult(result) {
  if (isSettingsRoute.value) {
    await router.push({ name: 'Dashboard' });
  }
  searchQuery.value = '';
  selectedId.value = result.conversationId;
  focusMessageIndex.value = result.isTitleMatch ? null : result.messageIndex;
  const conversation = conversations.value.find((item) => item.id === result.conversationId);
  if (conversation) {
    await loadConversationBody(conversation);
  }
  scrollToFocusedMessage();
}

onMounted(async () => {
  document.addEventListener('visibilitychange', onTabVisibilityChange);
  window.addEventListener(VAULT_IMPORTED_EVENT, onVaultImported);
  await encryption.fetchStatus();
  restorePendingRecoveryKeyModal();
  if (encryption.isUnlocked) {
    try {
      await loadConversations();
      await refreshSyncSnapshot();
    } catch {
      // Listing can be retried; do not block vault access.
    }
  }
});

onUnmounted(() => {
  document.removeEventListener('visibilitychange', onTabVisibilityChange);
  window.removeEventListener(VAULT_IMPORTED_EVENT, onVaultImported);
});

watch(() => route.name, async (name) => {
  if (name !== 'Dashboard' || !encryption.isUnlocked) return;
  await loadConversations(1, false, { silent: true });
  if (selectedConversation.value && !selectedConversation.value.bodyLoaded && !activeSearchQuery.value) {
    await loadConversationBody(selectedConversation.value);
  }
  await refreshSyncSnapshot();
});

async function onTabVisibilityChange() {
  if (document.visibilityState === 'visible') {
    await encryption.fetchStatus();
    checkForVaultUpdates();
  }
}

function hasSyncChanges(remote) {
  if (!syncSnapshot.value) return true;

  return (
    remote.conversationCount !== syncSnapshot.value.conversationCount
    || remote.latestUpdatedAt !== syncSnapshot.value.latestUpdatedAt
  );
}

async function refreshSyncSnapshot() {
  const { data } = await axios.get('/api/conversations/sync');
  syncSnapshot.value = {
    conversationCount: data.conversationCount ?? 0,
    latestUpdatedAt: data.latestUpdatedAt ?? null,
  };
}

async function checkForVaultUpdates() {
  if (!encryption.configured || !encryption.isUnlocked) return;
  if (syncChecking.value || conversationsLoading.value) return;
  if (Date.now() - lastSyncCheckAt < SYNC_CHECK_INTERVAL_MS) return;

  lastSyncCheckAt = Date.now();
  syncChecking.value = true;

  try {
    const { data } = await axios.get('/api/conversations/sync');

    if (!hasSyncChanges(data)) return;

    const previousSelectedId = selectedId.value;
    await loadConversations(1, false, { silent: true });
    syncSnapshot.value = {
      conversationCount: data.conversationCount ?? 0,
      latestUpdatedAt: data.latestUpdatedAt ?? null,
    };

    if (previousSelectedId && conversations.value.some((conversation) => conversation.id === previousSelectedId)) {
      selectedId.value = previousSelectedId;
      const conversation = conversations.value.find((item) => item.id === previousSelectedId);
      if (conversation) {
        conversation.bodyLoaded = false;
        conversation.messages = null;
        await loadConversationBody(conversation);
      }
    }
  } catch {
    // Ignore background sync errors; the next manual action can refresh.
  } finally {
    syncChecking.value = false;
  }
}

async function setupEncryption() {
  setupError.value = '';

  if (setupForm.password.length < 12) {
    setupError.value = 'Use at least 12 characters.';
    return;
  }

  if (setupForm.password !== setupForm.confirm) {
    setupError.value = 'Passwords do not match.';
    return;
  }

  let key = '';

  try {
    key = await encryption.setup(setupForm.password, setupForm.remember);
    setupForm.password = '';
    setupForm.confirm = '';
    showRecoveryKeyModal(key);
  } catch (error) {
    setupError.value = encryption.error || 'Unable to create encrypted vault.';
    return;
  }

  try {
    await loadConversations();
    await refreshSyncSnapshot();
  } catch {
    // Vault is ready; conversation list can load on refresh.
  }
}

function selectRecoveryKeyTextarea() {
  const el = recoveryKeyTextarea.value;
  if (!el) return;
  el.focus({ preventScroll: true });
  el.select();
}

async function showRecoveryKeyModal(key) {
  recoveryKey.value = key;
  recoveryModalOpen.value = true;
  await nextTick();
  selectRecoveryKeyTextarea();
}

function restorePendingRecoveryKeyModal() {
  const pendingKey = readPendingRecoveryKey();
  if (!pendingKey) return;
  showRecoveryKeyModal(pendingKey);
}

function closeRecoveryModal() {
  recoveryModalOpen.value = false;
  recoveryKey.value = '';
  clearPendingRecoveryKey();
}

function dismissExtensionBanner() {
  extensionBannerDismissed.value = true;
  localStorage.setItem(EXTENSION_CTA_DISMISSED_KEY, '1');
}

async function unlockVault() {
  unlockError.value = '';

  try {
    await encryption.unlock(unlockForm.password, unlockForm.remember);
    unlockForm.password = '';
    await loadConversations();
    await refreshSyncSnapshot();
  } catch (error) {
    unlockError.value = encryption.error || 'Unable to unlock vault.';
  }
}

async function resetWithRecoveryKey() {
  resetError.value = '';

  if (resetForm.password.length < 12) {
    resetError.value = 'Use at least 12 characters for the new encryption password.';
    return;
  }

  if (resetForm.password !== resetForm.confirm) {
    resetError.value = 'New passwords do not match.';
    return;
  }

  try {
    await encryption.resetWithRecoveryKey(resetForm.recoveryKey, resetForm.password, resetForm.remember);
    resetForm.recoveryKey = '';
    resetForm.password = '';
    resetForm.confirm = '';
    resetPanelOpen.value = false;
    await loadConversations();
    await refreshSyncSnapshot();
  } catch (error) {
    resetError.value = encryption.error || 'Unable to reset encryption password with this recovery key.';
  }
}

async function startOverVault() {
  resetError.value = '';

  if (startOverForm.password.length < 12) {
    resetError.value = 'Use at least 12 characters for the new encryption password.';
    return;
  }

  if (startOverForm.password !== startOverForm.confirm) {
    resetError.value = 'New passwords do not match.';
    return;
  }

  if (!startOverForm.confirmDelete) {
    resetError.value = 'Confirm that existing encrypted chats will be deleted.';
    return;
  }

  try {
    await axios.delete('/api/conversations');
    const key = await encryption.setup(startOverForm.password, true);
    startOverForm.password = '';
    startOverForm.confirm = '';
    startOverForm.confirmDelete = false;
    conversations.value = [];
    selectedId.value = null;
    pagination.currentPage = 0;
    pagination.lastPage = 1;
    pagination.total = 0;
    showRecoveryKeyModal(key);
    resetPanelOpen.value = false;
  } catch (error) {
    resetError.value = encryption.error || 'Unable to start a new encrypted vault.';
  }
}

async function loadConversations(page = 1, append = false, options = {}) {
  const { silent = false } = options;
  if (!silent) conversationsLoading.value = true;

  try {
    const { data } = await axios.get('/api/conversations', {
      params: {
        page,
        per_page: pagination.perPage,
      },
    });
    const decrypted = [];

    for (const item of data.data || []) {
      try {
        const header = await encryption.decrypt(item.encrypted_header);
        decrypted.push({
          id: item.id,
          title: header.title || 'Untitled conversation',
          source: header.provider || 'unknown',
          archivedAt: item.created_at?.slice(0, 10),
          project: header.project || null,
          tags: header.tags || [],
          messageCount: header.messageCount || 0,
          snippet: header.snippet || '',
          searchText: header.searchText || '',
          header,
          encryptedBody: item.encrypted_body || '',
          bodyBytes: item.body_bytes || 0,
          messages: null,
          bodyLoaded: false,
        });
      } catch (error) {
        decrypted.push({
          id: item.id,
          title: 'Unable to decrypt conversation',
          source: 'unknown',
          archivedAt: item.created_at?.slice(0, 10),
          project: 'Locked',
          tags: [],
          messageCount: 0,
          snippet: '',
          searchText: '',
          bodyBytes: item.body_bytes || 0,
          messages: [{ role: 'assistant', content: ['This conversation could not be decrypted with the current key.'] }],
          bodyLoaded: true,
        });
      }
    }

    conversations.value = append ? [...conversations.value, ...decrypted] : decrypted;
    pagination.currentPage = data.current_page || page;
    pagination.lastPage = data.last_page || page;
    pagination.total = data.total || conversations.value.length;

    if (!append && selectedId.value && !conversations.value.some((conversation) => conversation.id === selectedId.value)) {
      selectedId.value = null;
    }

    if (!selectedId.value && filteredConversations.value.length && !activeSearchQuery.value && !isMobileDashboard()) {
      selectedId.value = filteredConversations.value[0].id;
    }
  } finally {
    if (!silent) conversationsLoading.value = false;
  }
}

async function loadMoreConversations() {
  if (!hasMoreConversations.value || conversationsLoading.value) return;
  await loadConversations(pagination.currentPage + 1, true);
}

function isMobileDashboard() {
  return window.matchMedia('(max-width: 980px)').matches;
}

async function loadConversationBody(conversation) {
  if (!conversation || conversation.bodyLoaded || bodyLoadingId.value === conversation.id) return;

  bodyError.value = '';
  bodyLoadingId.value = conversation.id;

  try {
    let encryptedBody = conversation.encryptedBody;

    if (!encryptedBody || (conversation.bodyBytes && encryptedBody.length !== conversation.bodyBytes)) {
      const { data } = await axios.get(`/api/conversations/${conversation.id}`);
      encryptedBody = data.encrypted_body;
      conversation.encryptedBody = encryptedBody;
    }

    const body = await encryption.decrypt(encryptedBody);
    conversation.messages = body.messages || [];
    conversation.bodyLoaded = true;

    if (body.provider) conversation.source = body.provider;
  } catch (error) {
    console.error('Memoriq conversation body load failed', {
      conversationId: conversation.id,
      title: conversation.title,
      source: conversation.source,
      bodyBytes: conversation.bodyBytes,
      encryptedBodyLength: conversation.encryptedBody?.length || 0,
      errorName: error?.name,
      errorMessage: error?.message,
      responseStatus: error?.response?.status,
      responseData: error?.response?.data,
      error,
    });
    bodyError.value = error.response?.data?.message || 'Unable to load this encrypted conversation.';
  } finally {
    bodyLoadingId.value = null;
  }
}

async function onVaultImported() {
  selectedId.value = null;
  bodyError.value = '';
  conversations.value = [];
  pagination.currentPage = 0;
  pagination.lastPage = 1;
  pagination.total = 0;
  syncSnapshot.value = null;

  await encryption.fetchStatus();

  if (!encryption.isUnlocked) return;

  await loadConversations(1, false);
  await refreshSyncSnapshot();
}

function projectCount(project) {
  return conversations.value.filter((conversation) => conversation.project === project).length;
}

function cleanProjectName(value) {
  return (value || '').trim().replace(/\s+/g, ' ').slice(0, 80);
}

function cleanTitle(value) {
  return (value || '').trim().replace(/\s+/g, ' ').slice(0, 200);
}

function truncate(value, maxLength) {
  if (!value || value.length <= maxLength) return value || '';
  return `${value.slice(0, maxLength).trim()}…`;
}

function plainTextFromMarkdown(value) {
  return (value || '')
    .replace(/```[\s\S]*?```/g, ' ')
    .replace(/`([^`]+)`/g, '$1')
    .replace(/!\[[^\]]*]\([^)]*\)/g, ' ')
    .replace(/\[([^\]]+)]\([^)]*\)/g, '$1')
    .replace(/^#{1,6}\s+/gm, '')
    .replace(/^\s*[-*+]\s+/gm, '')
    .replace(/^\s*\d+\.\s+/gm, '')
    .replace(/[*_~>#|]/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();
}

function titleFromPastedReply(value) {
  const firstMeaningfulLine = (value || '')
    .split('\n')
    .map((line) => plainTextFromMarkdown(line))
    .find((line) => line.length >= 4);

  return cleanTitle(truncate(firstMeaningfulLine || 'Pasted AI reply', 120));
}

function buildManualConversationPayload() {
  const content = pasteForm.content.trim();
  const title = cleanTitle(pasteForm.title) || titleFromPastedReply(content);
  const capturedAt = new Date().toISOString();
  const source = pasteForm.source;
  const message = {
    role: 'assistant',
    content,
    markdown: content,
    format: 'markdown',
  };
  const searchText = content;

  return {
    header: {
      version: 2,
      provider: source,
      title,
      sourceUrl: '',
      capturedAt,
      project: null,
      tags: [],
      messageCount: 1,
      snippet: truncate(plainTextFromMarkdown(content), 280),
      searchText: truncate(searchText, 20000),
    },
    body: {
      version: 2,
      provider: source,
      title,
      sourceUrl: '',
      capturedAt,
      messages: [message],
    },
  };
}

function openPasteModal() {
  pasteModalOpen.value = true;
  pasteError.value = '';
  pasteTitleTouched.value = false;
  if (!pasteForm.source) pasteForm.source = sources[0]?.id || 'chatgpt';
  nextTick(() => document.querySelector('.paste-reply-textarea')?.focus());
}

function closePasteModal() {
  if (pasteSaving.value) return;
  pasteModalOpen.value = false;
  pasteError.value = '';
  resetPasteForm();
}

function resetPasteForm() {
  pasteForm.source = 'chatgpt';
  pasteForm.title = '';
  pasteForm.content = '';
  pasteTitleTouched.value = false;
}

async function savePastedReply() {
  pasteError.value = '';

  if (!pasteForm.content.trim()) {
    pasteError.value = 'Paste an AI reply first.';
    return;
  }

  pasteSaving.value = true;

  try {
    const payload = buildManualConversationPayload();
    const encryptedHeader = await encryption.encrypt(payload.header);
    const encryptedBody = await encryption.encrypt(payload.body);
    const { data } = await axios.post('/api/conversations', {
      encrypted_header: encryptedHeader,
      encrypted_body: encryptedBody,
    });

    const conversation = {
      id: data.id,
      title: payload.header.title,
      source: payload.header.provider,
      archivedAt: data.created_at?.slice(0, 10) || payload.header.capturedAt.slice(0, 10),
      project: null,
      tags: payload.header.tags,
      messageCount: 1,
      snippet: payload.header.snippet,
      searchText: payload.header.searchText,
      header: payload.header,
      encryptedBody,
      bodyBytes: data.body_bytes || encryptedBody.length,
      messages: payload.body.messages,
      bodyLoaded: true,
    };

    conversations.value = [conversation, ...conversations.value];
    pagination.total += 1;
    selectedId.value = conversation.id;
    pasteModalOpen.value = false;
    resetPasteForm();
    await refreshSyncSnapshot();
  } catch (error) {
    pasteError.value = error.response?.data?.message || error.message || 'Unable to save this reply.';
  } finally {
    pasteSaving.value = false;
  }
}

function buildConversationHeader(conversation, overrides = {}) {
  return {
    ...(conversation.header || {}),
    version: 2,
    provider: conversation.source,
    title: conversation.title,
    sourceUrl: conversation.header?.sourceUrl,
    capturedAt: conversation.header?.capturedAt || conversation.archivedAt,
    project: conversation.project || null,
    tags: conversation.tags || [],
    messageCount: conversation.messageCount || 0,
    snippet: conversation.snippet || '',
    searchText: conversation.searchText || '',
    ...overrides,
  };
}

async function updateConversationHeader(conversation, overrides = {}, { refreshSync = true } = {}) {
  if (!conversation || movingId.value) return false;

  movingId.value = conversation.id;
  deleteError.value = '';

  try {
    const header = buildConversationHeader(conversation, overrides);
    const encryptedHeader = await encryption.encrypt(header);
    const { data } = await axios.patch(`/api/conversations/${conversation.id}/header`, {
      encrypted_header: encryptedHeader,
    });
    const decryptedHeader = await encryption.decrypt(data.encrypted_header);

    conversation.header = decryptedHeader;
    conversation.project = decryptedHeader.project || null;
    conversation.tags = decryptedHeader.tags || [];
    conversation.title = decryptedHeader.title || conversation.title;
    conversation.source = decryptedHeader.provider || conversation.source;
    conversation.snippet = decryptedHeader.snippet || conversation.snippet;
    conversation.searchText = decryptedHeader.searchText || conversation.searchText;
    conversation.messageCount = decryptedHeader.messageCount || conversation.messageCount;
    if (refreshSync) await refreshSyncSnapshot();
    return true;
  } catch (error) {
    deleteError.value = error.response?.data?.message || 'Unable to update this conversation.';
    return false;
  } finally {
    movingId.value = null;
  }
}

async function applyProjectToAllConversations(fromProject, toProject) {
  const targets = conversations.value.filter((conversation) => conversation.project === fromProject);
  if (!targets.length) return true;

  projectUpdating.value = fromProject;
  deleteError.value = '';

  try {
    for (const conversation of targets) {
      const updated = await updateConversationHeader(conversation, { project: toProject }, { refreshSync: false });
      if (!updated) return false;
    }

    await refreshSyncSnapshot();
    return true;
  } finally {
    projectUpdating.value = null;
  }
}

function resetProjectModal() {
  projectModal.open = false;
  projectModal.mode = '';
  projectModal.conversation = null;
  projectModal.projectName = '';
  projectModal.name = '';
  projectModal.targetProject = '';
  projectModal.error = '';
}

function openCreateProjectModal() {
  resetProjectModal();
  projectModal.open = true;
  projectModal.mode = 'create';
  projectModal.conversation = selectedConversation.value;
}

function openRenameProjectModal(projectName) {
  if (!projectName || projectUpdating.value || movingId.value) return;
  resetProjectModal();
  projectModal.open = true;
  projectModal.mode = 'rename';
  projectModal.projectName = projectName;
  projectModal.name = projectName;
}

function openDeleteProjectModal(projectName) {
  if (!projectName || projectUpdating.value || movingId.value) return;
  resetProjectModal();
  projectModal.open = true;
  projectModal.mode = 'delete';
  projectModal.projectName = projectName;
}

function openMoveProjectModal(conversation) {
  if (!conversation || movingId.value) return;
  resetProjectModal();
  projectModal.open = true;
  projectModal.mode = 'move';
  projectModal.conversation = conversation;
  projectModal.targetProject = conversation.project || '';
}

function closeProjectModal() {
  if (projectUpdating.value || movingId.value) return;
  resetProjectModal();
}

async function confirmProjectModal() {
  projectModal.error = '';

  if (projectModal.mode === 'create') {
    if (!projectModal.conversation) {
      projectModal.error = 'Select a chat first, then create a project from it.';
      return;
    }

    const name = cleanProjectName(projectModal.name);
    if (!name) {
      projectModal.error = 'Enter a project name.';
      return;
    }

    await moveConversationToProject(projectModal.conversation, name);
    resetProjectModal();
    return;
  }

  if (projectModal.mode === 'rename') {
    const newName = cleanProjectName(projectModal.name);
    if (!newName) {
      projectModal.error = 'Enter a project name.';
      return;
    }

    if (newName === projectModal.projectName) {
      resetProjectModal();
      return;
    }

    const updated = await applyProjectToAllConversations(projectModal.projectName, newName);
    if (updated && activeProject.value === projectModal.projectName) {
      activeProject.value = newName;
    }
    if (updated) resetProjectModal();
    return;
  }

  if (projectModal.mode === 'delete') {
    const updated = await applyProjectToAllConversations(projectModal.projectName, null);
    if (updated && activeProject.value === projectModal.projectName) {
      activeProject.value = NO_PROJECT;
    }
    if (updated) resetProjectModal();
    return;
  }

  if (projectModal.mode === 'move') {
    const project = projectModal.targetProject === '__new__'
      ? cleanProjectName(projectModal.name)
      : cleanProjectName(projectModal.targetProject);

    if (projectModal.targetProject === '__new__' && !project) {
      projectModal.error = 'Enter a project name.';
      return;
    }

    await moveConversationToProject(projectModal.conversation, project || null);
    resetProjectModal();
  }
}

function startTitleRename(conversation) {
  if (!conversation || movingId.value) return;

  renamingId.value = conversation.id;
  titleDraft.value = conversation.title || '';
  nextTick(() => titleInputRef.value?.focus());
}

function cancelTitleRename() {
  renamingId.value = null;
  titleDraft.value = '';
}

async function saveTitleRename(conversation) {
  if (!conversation || renamingId.value !== conversation.id) return;

  const newTitle = cleanTitle(titleDraft.value);
  const previousTitle = conversation.title;
  renamingId.value = null;
  titleDraft.value = '';

  if (!newTitle || newTitle === previousTitle) return;

  await updateConversationHeader(conversation, { title: newTitle });
}

async function moveConversationToProject(conversation, project) {
  const updated = await updateConversationHeader(conversation, { project });
  if (updated) {
    activeProject.value = conversation.project || NO_PROJECT;
  }
}

function toggleSource(source) {
  activeSource.value = activeSource.value === source ? null : source;
  clearMobileSelection();
}

function clearFilters() {
  searchQuery.value = '';
  activeProject.value = NO_PROJECT;
  activeSource.value = null;
  clearMobileSelection();
}

function clearMobileSelection() {
  if (!isMobileDashboard()) return;

  selectedId.value = null;
  focusMessageIndex.value = null;
  bodyError.value = '';
}

async function deleteConversation(conversation) {
  if (!conversation?.id || deletingId.value) return;

  const title = conversation.title || 'this chat';
  if (!window.confirm(`Delete "${title}" permanently? This cannot be undone.`)) {
    return;
  }

  deleteError.value = '';
  deletingId.value = conversation.id;

  try {
    await axios.delete(`/api/conversations/${conversation.id}`);
    const remaining = conversations.value.filter((item) => item.id !== conversation.id);
    conversations.value = remaining;

    if (selectedId.value === conversation.id) {
      selectedId.value = filteredConversations.value[0]?.id ?? null;
    }

    await refreshSyncSnapshot();
  } catch (error) {
    deleteError.value = error.response?.data?.message || 'Unable to delete this conversation.';
  } finally {
    deletingId.value = null;
  }
}

function sourceLabel(id) {
  return sources.find((source) => source.id === id)?.label || id;
}

function assistantInitial(source) {
  return { chatgpt: 'G', claude: 'C', gemini: 'Ge', grok: 'X' }[source] || 'AI';
}

function normalizeMessage(message) {
  if (!message) return { role: 'assistant', content: '' };
  if (Array.isArray(message.content)) {
    return { ...message, content: message.content.join('\n\n') };
  }
  return message;
}

function pluralize(count, singular, plural) {
  return `${count} ${count === 1 ? singular : plural}`;
}

function formatBytes(bytes) {
  const value = Number(bytes || 0);
  if (value <= 0) return '0 KB';
  if (value < 1048576) return `${Math.max(1, Math.round(value / 1024))} KB`;
  return `${(value / 1048576).toFixed(value >= 10485760 ? 0 : 1)} MB`;
}

function formatDate(iso) {
  return new Date(`${iso}T12:00:00`).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

function formatDateFull(iso) {
  return new Date(`${iso}T12:00:00`).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>
