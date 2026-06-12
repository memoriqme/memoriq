<template>
  <div class="settings-page" :class="{ 'settings-page--embedded': embedded }">
    <header class="settings-header">
      <div class="settings-header-top">
        <div>
          <h1>Settings</h1>
          <p>Manage your Memoriq account.</p>
        </div>
        <div class="settings-header-actions">
          <button class="icon-button" type="button" :aria-label="themeLabel" :title="themeLabel" @click="toggleTheme">
            <svg v-if="theme === 'dark'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <circle cx="12" cy="12" r="4"/>
              <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
            </svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
          </button>
          <app-menu-button v-if="!embedded" />
        </div>
      </div>
    </header>

    <div class="settings-grid">
      <section class="settings-card">
        <div class="card-heading">
          <h2>Profile</h2>
          <p>Update the email address attached to your account.</p>
        </div>

        <form @submit.prevent="updateProfile">
          <label class="form-field">
            Name
            <input v-model="profileForm.name" type="text" autocomplete="name" />
          </label>

          <label class="form-field">
            Email
            <input v-model="profileForm.email" type="email" autocomplete="email" required />
          </label>

          <div v-if="profileMessage" class="form-success">{{ profileMessage }}</div>
          <div v-if="profileError" class="form-alert">{{ profileError }}</div>

          <button class="button button-primary" type="submit" :disabled="profileProcessing">
            {{ profileProcessing ? 'Saving...' : 'Save profile' }}
          </button>
        </form>
      </section>

      <section class="settings-card">
        <div class="card-heading">
          <h2>Password</h2>
          <p>Change your password using the existing account endpoint.</p>
        </div>

        <form @submit.prevent="updatePassword">
          <label class="form-field">
            Current password
            <input v-model="passwordForm.current_password" type="password" autocomplete="current-password" required />
          </label>

          <label class="form-field">
            New password
            <input v-model="passwordForm.password" type="password" autocomplete="new-password" required />
          </label>

          <label class="form-field">
            Confirm password
            <input v-model="passwordForm.password_confirmation" type="password" autocomplete="new-password" required />
          </label>

          <div v-if="passwordMessage" class="form-success">{{ passwordMessage }}</div>
          <div v-if="passwordError" class="form-alert">{{ passwordError }}</div>

          <button class="button button-primary" type="submit" :disabled="passwordProcessing">
            {{ passwordProcessing ? 'Saving...' : 'Update password' }}
          </button>
        </form>
      </section>

      <section class="settings-card">
        <div class="card-heading">
          <h2>Encryption vault</h2>
          <p>Your AI conversations are encrypted locally before upload. Changing this password keeps existing chats intact.</p>
        </div>

        <p class="muted" v-if="encryption.configured">
          Vault configured. {{ encryption.isUnlocked ? 'Unlocked on this device.' : 'Locked on this device.' }}
        </p>
        <p class="muted" v-else>
          Vault not configured yet. Open the dashboard to create your encryption password before saving chats.
        </p>

        <form v-if="encryption.configured" @submit.prevent="changeEncryptionPassword">
          <label class="form-field">
            Current encryption password
            <input v-model="encryptionPasswordForm.current" type="password" autocomplete="current-password" required />
          </label>

          <label class="form-field">
            New encryption password
            <input v-model="encryptionPasswordForm.password" type="password" autocomplete="new-password" required minlength="12" />
          </label>

          <label class="form-field">
            Confirm new encryption password
            <input v-model="encryptionPasswordForm.confirm" type="password" autocomplete="new-password" required minlength="12" />
          </label>

          <label class="check-field">
            <input v-model="encryptionPasswordForm.remember" type="checkbox" />
            Keep unlocked on this browser
          </label>

          <div v-if="encryptionPasswordMessage" class="form-success">{{ encryptionPasswordMessage }}</div>
          <div v-if="encryptionPasswordError" class="form-alert">{{ encryptionPasswordError }}</div>

          <div class="settings-actions">
            <button class="button button-primary" type="submit" :disabled="encryption.loading">
              {{ encryption.loading ? 'Updating...' : 'Change encryption password' }}
            </button>
            <button v-if="encryption.isUnlocked" class="button button-ghost" type="button" @click="forgetDevice">
              Lock this browser
            </button>
          </div>
        </form>
      </section>

      <section class="settings-card">
        <div class="card-heading">
          <h2>Browser extension</h2>
          <p>Capture conversations from ChatGPT, Claude, Gemini, and Grok without leaving the page.</p>
        </div>

        <p class="muted">
          Install the Chrome extension, then connect it to your Memoriq account. Chats are encrypted on your device before upload.
        </p>

        <extension-install-cta
          variant="compact"
          title=""
          description="One-click save from any supported AI chat tab."
          show-connect
        />
      </section>

      <section class="settings-card">
        <div class="card-heading">
          <h2>Encrypted backup</h2>
          <p>Export or restore the encrypted vault as a JSON file.</p>
        </div>

        <p class="muted">
          The backup contains your encrypted key envelope and encrypted conversations. Memoriq still cannot read chat contents from the file.
        </p>

        <div class="settings-actions">
          <button class="button button-primary" type="button" :disabled="backupExporting || !encryption.configured" @click="exportVault">
            {{ backupExporting ? 'Preparing export...' : 'Export encrypted JSON' }}
          </button>
          <label class="button button-ghost import-button" :class="{ disabled: backupImporting }">
            <input ref="backupFileInput" type="file" accept="application/json,.json" :disabled="backupImporting" @change="importVault" />
            {{ backupImporting ? 'Importing...' : 'Import encrypted JSON' }}
          </label>
        </div>

        <p class="muted">
          Import replaces the current vault, encryption key envelope, and saved conversations with the contents of the backup.
        </p>

        <div v-if="backupMessage" class="form-success">{{ backupMessage }}</div>
        <div v-if="backupError" class="form-alert">{{ backupError }}</div>
      </section>

      <section class="settings-card">
        <div class="card-heading">
          <h2>Storage</h2>
          <p>Your encrypted vault usage on this account.</p>
        </div>

        <div class="storage-meter">
          <div class="storage-meter-header">
            <strong>{{ formattedUsedStorage }}</strong>
            <span>{{ storagePercent }}% of {{ formattedStorageLimit }}</span>
          </div>
          <div class="storage-bar" role="meter" aria-label="Encrypted storage used" :aria-valuenow="storagePercent" aria-valuemin="0" aria-valuemax="100">
            <span :style="{ width: `${storagePercent}%` }"></span>
          </div>
        </div>

        <p class="muted">
          Free forever includes 100 MB of encrypted storage. Upgrade when your vault needs more room.
        </p>
        <div v-if="storageError" class="form-alert">{{ storageError }}</div>
      </section>

      <details class="settings-card danger-card" @toggle="onDeleteSectionToggle">
        <summary class="danger-card-summary">
          <div class="card-heading">
            <h2>Delete account</h2>
            <p>Permanently remove your account and encrypted data from the server.</p>
          </div>
          <svg class="danger-card-chevron" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path d="M5 7.5L10 12.5L15 7.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </summary>

        <div class="danger-card-body">
          <p class="muted">
            Export an encrypted backup first if you may want your chats later. This cannot be undone.
            Routine server logs may keep non-content operational records for a short period before rotation.
          </p>

          <form @submit.prevent="deleteAccount">
            <label class="form-field">
              Account password
              <input v-model="deleteForm.password" type="password" autocomplete="current-password" required />
            </label>

            <label class="check-field">
              <input v-model="deleteForm.confirmed" type="checkbox" />
              I understand this permanently deletes my account, vault, and saved conversations from the server.
            </label>
            <div v-if="deleteError" class="form-alert">{{ deleteError }}</div>

            <button class="button button-danger" type="submit" :disabled="deletingAccount || !deleteForm.confirmed">
              {{ deletingAccount ? 'Deleting account...' : 'Delete my account' }}
            </button>
          </form>
        </div>
      </details>

      <section class="settings-card session-card">
        <div class="card-heading">
          <h2>Session</h2>
          <p>Signed in as {{ auth.user?.email }}.</p>
        </div>

        <div class="settings-session-row">
          <div class="user-avatar">{{ userInitial }}</div>
          <div>
            <div class="user-email">{{ auth.user?.email }}</div>
            <div class="user-caption">Signed in</div>
          </div>
        </div>

        <button class="sidebar-logout-btn settings-logout-btn" type="button" :disabled="loggingOut" @click="logout">
          {{ loggingOut ? 'Logging out...' : 'Log out' }}
        </button>
      </section>

    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import AppMenuButton from './AppMenuButton.vue';
import ExtensionInstallCta from './ExtensionInstallCta.vue';
import { applyTheme, getStoredTheme } from '../memoriq/theme';
import { useEncryptionStore } from '../stores/encryptionStore';
import { useAuthStore } from '../stores/authStore';

defineProps({
  embedded: {
    type: Boolean,
    default: false,
  },
});

const auth = useAuthStore();
const encryption = useEncryptionStore();
const router = useRouter();

const profileProcessing = ref(false);
const profileMessage = ref('');
const profileErrors = ref(null);
const passwordProcessing = ref(false);
const passwordMessage = ref('');
const passwordErrors = ref(null);
const backupExporting = ref(false);
const backupImporting = ref(false);
const backupMessage = ref('');
const backupError = ref('');
const backupFileInput = ref(null);
const encryptionPasswordMessage = ref('');
const encryptionPasswordError = ref('');
const storageUsage = reactive({
  usedBytes: 0,
  limitBytes: 104857600,
});
const storageError = ref('');
const loggingOut = ref(false);
const deletingAccount = ref(false);
const deleteError = ref('');
const theme = ref(getStoredTheme());
const themeLabel = computed(() => (theme.value === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'));

const profileForm = reactive({
  name: '',
  email: '',
});

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const encryptionPasswordForm = reactive({
  current: '',
  password: '',
  confirm: '',
  remember: false,
});

const deleteForm = reactive({
  password: '',
  confirmed: false,
});

const profileError = computed(() => firstError(profileErrors.value));
const passwordError = computed(() => firstError(passwordErrors.value));
const storagePercent = computed(() => {
  if (!storageUsage.limitBytes) return 0;
  return Math.min(100, Math.round((storageUsage.usedBytes / storageUsage.limitBytes) * 100));
});
const formattedUsedStorage = computed(() => formatBytes(storageUsage.usedBytes));
const formattedStorageLimit = computed(() => formatBytes(storageUsage.limitBytes));
const userInitial = computed(() => (auth.user?.email || 'M').charAt(0).toUpperCase());

onMounted(() => {
  applyTheme(theme.value);
  profileForm.name = auth.user?.name || '';
  profileForm.email = auth.user?.email || '';
  encryption.fetchStatus();
  fetchStorageUsage();
});

function toggleTheme() {
  theme.value = theme.value === 'dark' ? 'light' : 'dark';
  applyTheme(theme.value);
}

async function updateProfile() {
  profileProcessing.value = true;
  profileMessage.value = '';
  profileErrors.value = null;

  try {
    await axios.put('/user/profile-information', profileForm);
    await auth.fetchUser();
    profileMessage.value = 'Profile saved.';
  } catch (error) {
    profileErrors.value = error.response?.data?.errors || { email: [error.response?.data?.message || 'Unable to save profile.'] };
  } finally {
    profileProcessing.value = false;
  }
}

async function updatePassword() {
  passwordProcessing.value = true;
  passwordMessage.value = '';
  passwordErrors.value = null;

  try {
    await axios.put('/user/password', passwordForm);
    passwordForm.current_password = '';
    passwordForm.password = '';
    passwordForm.password_confirmation = '';
    passwordMessage.value = 'Password updated.';
  } catch (error) {
    passwordErrors.value = error.response?.data?.errors || { password: [error.response?.data?.message || 'Unable to update password.'] };
  } finally {
    passwordProcessing.value = false;
  }
}

async function changeEncryptionPassword() {
  encryptionPasswordMessage.value = '';
  encryptionPasswordError.value = '';

  if (encryptionPasswordForm.password.length < 12) {
    encryptionPasswordError.value = 'Use at least 12 characters for the new encryption password.';
    return;
  }

  if (encryptionPasswordForm.password !== encryptionPasswordForm.confirm) {
    encryptionPasswordError.value = 'New encryption passwords do not match.';
    return;
  }

  if (encryptionPasswordForm.current === encryptionPasswordForm.password) {
    encryptionPasswordError.value = 'Choose a new encryption password that is different from the current one.';
    return;
  }

  try {
    await encryption.changePassword(
      encryptionPasswordForm.current,
      encryptionPasswordForm.password,
      encryptionPasswordForm.remember,
    );

    encryptionPasswordForm.current = '';
    encryptionPasswordForm.password = '';
    encryptionPasswordForm.confirm = '';
    encryptionPasswordMessage.value = 'Encryption password changed. Existing chats remain encrypted with the same private vault key.';
  } catch (error) {
    encryptionPasswordError.value = encryption.error || 'Unable to change encryption password.';
  }
}

function firstError(errors) {
  if (!errors) return '';
  const first = Object.values(errors)[0];
  return Array.isArray(first) ? first[0] : first;
}

async function fetchStorageUsage() {
  storageError.value = '';

  try {
    const { data } = await axios.get('/api/conversations/storage');
    storageUsage.usedBytes = data.usedBytes || 0;
    storageUsage.limitBytes = data.limitBytes || 104857600;
  } catch (error) {
    storageError.value = error.response?.data?.message || 'Unable to load storage usage.';
  }
}

async function exportVault() {
  backupExporting.value = true;
  backupMessage.value = '';
  backupError.value = '';

  try {
    const response = await axios.get('/api/vault/export', { responseType: 'blob' });
    const disposition = response.headers['content-disposition'] || '';
    const filename = filenameFromDisposition(disposition) || `memoriq-${new Date().toISOString().slice(0, 10)}.json`;
    const url = URL.createObjectURL(response.data);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
    backupMessage.value = 'Encrypted backup exported.';
  } catch (error) {
    backupError.value = error.response?.data?.message || 'Unable to export encrypted backup.';
  } finally {
    backupExporting.value = false;
  }
}

async function importVault(event) {
  const [file] = event.target.files || [];
  if (!file) return;

  const confirmed = window.confirm(
    'Importing this backup will replace your current encryption key envelope and all saved conversations. Continue?',
  );

  if (!confirmed) {
    event.target.value = '';
    return;
  }

  backupImporting.value = true;
  backupMessage.value = '';
  backupError.value = '';

  try {
    const formData = new FormData();
    formData.append('backup', file);
    const { data } = await axios.post('/api/vault/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    encryption.forgetDevice();
    await encryption.fetchStatus();
    await fetchStorageUsage();
    window.dispatchEvent(new CustomEvent('memoriq:vault-imported'));
    backupMessage.value = `Imported ${data.conversationCount || 0} encrypted conversations from backup ${data.appVersion || ''}.`.trim();
  } catch (error) {
    backupError.value = error.response?.data?.message || 'Unable to import encrypted backup.';
  } finally {
    backupImporting.value = false;
    if (backupFileInput.value) backupFileInput.value.value = '';
  }
}

function filenameFromDisposition(disposition) {
  const match = disposition.match(/filename="?([^"]+)"?/i);
  return match?.[1] || '';
}

function formatBytes(bytes) {
  if (!bytes) return '0 MB';
  const units = ['B', 'KB', 'MB', 'GB', 'TB'];
  const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
  const value = bytes / 1024 ** exponent;
  const precision = value >= 10 || exponent === 0 ? 0 : 1;
  return `${value.toFixed(precision)} ${units[exponent]}`;
}

function forgetDevice() {
  encryption.forgetDevice();
}

async function logout() {
  loggingOut.value = true;

  try {
    await axios.post('/logout');
  } finally {
    auth.user = null;
    loggingOut.value = false;
    router.push({ name: 'Home' });
  }
}

function onDeleteSectionToggle(event) {
  if (!event.target.open) {
    deleteForm.password = '';
    deleteForm.confirmed = false;
    deleteError.value = '';
  }
}

async function deleteAccount() {
  if (!deleteForm.confirmed) {
    deleteError.value = 'Confirm that you understand this action is permanent.';
    return;
  }

  deletingAccount.value = true;
  deleteError.value = '';

  try {
    await axios.post('/user/delete', {
      password: deleteForm.password,
      delete_confirmed: deleteForm.confirmed,
    });

    encryption.forgetDevice();
    auth.user = null;
    deleteForm.password = '';
    deleteForm.confirmed = false;
    window.location = '/';
  } catch (error) {
    deleteError.value = error.response?.data?.message
      || firstError(error.response?.data?.errors)
      || 'Unable to delete account.';
  } finally {
    deletingAccount.value = false;
  }
}
</script>
