import { defineStore } from 'pinia';
import axios from 'axios';
import {
  base64ToUint8Array,
  decryptJson,
  encryptJson,
  exportMEK,
  generateMEK,
  generateSalt,
  importMEK,
  uint8ArrayToBase64,
  unwrapMEK,
  wrapMEK,
} from '../services/crypto';
import { persistPendingRecoveryKey } from '../memoriq/recoveryKey';

const BROWSER_MEK_KEY = 'memoriq_browser_mek_jwk';
const LEGACY_SESSION_MEK_KEY = 'memoriq_session_mek_jwk';
const LEGACY_REMEMBERED_MEK_KEY = 'memoriq_mek_jwk';

export const useEncryptionStore = defineStore('encryption', {
  state: () => ({
    configured: false,
    keyData: null,
    salt: null,
    mek: null,
    recoveryKey: null,
    loading: false,
    error: '',
  }),
  getters: {
    isUnlocked: (state) => !!state.mek,
  },
  actions: {
    async fetchStatus() {
      this.loading = true;
      this.error = '';

      try {
        const { data } = await axios.get('/api/user/encryption-key');
        const previousKeyData = this.keyData;
        const previousSalt = this.salt;
        this.configured = !!data.configured;
        this.keyData = data.keyData || null;
        this.salt = data.salt || null;

        if (this.mek && this.envelopeChanged(previousKeyData, previousSalt)) {
          this.forgetDevice();
        }

        if (this.configured && !this.mek) {
          await this.restoreRememberedKey();
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Unable to load encryption status.';
      } finally {
        this.loading = false;
      }
    },

    async setup(password, rememberDevice = true) {
      this.loading = true;
      this.error = '';

      try {
        const mek = await generateMEK();
        const saltBytes = generateSalt();
        const { encryptedMEK, recoveryKey } = await wrapMEK(password, saltBytes, mek);
        const salt = uint8ArrayToBase64(saltBytes);

        await axios.post('/api/user/encryption-key', {
          encryptedMek: encryptedMEK,
          salt,
        });

        persistPendingRecoveryKey(recoveryKey);

        this.configured = true;
        this.keyData = encryptedMEK;
        this.salt = salt;
        this.mek = mek;
        this.recoveryKey = recoveryKey;

        if (rememberDevice) {
          try {
            await this.rememberKey();
          } catch {
            // Vault is configured; failing to cache the key locally must not block setup.
          }
        }

        return recoveryKey;
      } catch (error) {
        this.error = error.response?.data?.message || error.message || 'Unable to set up encryption.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async unlock(password, rememberDevice = true) {
      this.loading = true;
      this.error = '';

      try {
        if (!this.keyData || !this.salt) {
          await this.fetchStatus();
        }

        this.mek = await unwrapMEK(password, base64ToUint8Array(this.salt), this.keyData);

        if (rememberDevice) {
          await this.rememberKey();
        }
      } catch (error) {
        this.error = 'Could not unlock your Memoriq vault. Check the encryption password.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async resetWithRecoveryKey(recoveryKey, newPassword, rememberDevice = true) {
      this.loading = true;
      this.error = '';

      try {
        const mek = await importMEK(JSON.parse(window.atob(recoveryKey.trim())));
        const saltBytes = generateSalt();
        const { encryptedMEK } = await wrapMEK(newPassword, saltBytes, mek);
        const salt = uint8ArrayToBase64(saltBytes);

        await axios.post('/api/user/encryption-key', {
          encryptedMek: encryptedMEK,
          salt,
        });

        this.configured = true;
        this.keyData = encryptedMEK;
        this.salt = salt;
        this.mek = mek;
        this.recoveryKey = recoveryKey.trim();

        if (rememberDevice) {
          await this.rememberKey();
        }
      } catch (error) {
        this.error = 'Could not reset the encryption password. Check the recovery key and new password.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async changePassword(currentPassword, newPassword, rememberDevice = true) {
      this.loading = true;
      this.error = '';

      try {
        if (!this.keyData || !this.salt) {
          await this.fetchStatus();
        }

        const mek = await unwrapMEK(currentPassword, base64ToUint8Array(this.salt), this.keyData);
        const saltBytes = generateSalt();
        const { encryptedMEK } = await wrapMEK(newPassword, saltBytes, mek);
        const salt = uint8ArrayToBase64(saltBytes);

        await axios.post('/api/user/encryption-key', {
          encryptedMek: encryptedMEK,
          salt,
        });

        this.configured = true;
        this.keyData = encryptedMEK;
        this.salt = salt;
        this.mek = mek;

        if (rememberDevice) {
          await this.rememberKey();
        } else {
          localStorage.removeItem(BROWSER_MEK_KEY);
          sessionStorage.removeItem(LEGACY_SESSION_MEK_KEY);
          localStorage.removeItem(LEGACY_REMEMBERED_MEK_KEY);
        }
      } catch (error) {
        this.error = 'Could not change the encryption password. Check the current password.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async rememberKey() {
      if (!this.mek) return;

      localStorage.removeItem(LEGACY_REMEMBERED_MEK_KEY);
      sessionStorage.removeItem(LEGACY_SESSION_MEK_KEY);
      localStorage.setItem(BROWSER_MEK_KEY, JSON.stringify({
        version: 2,
        keyData: this.keyData,
        salt: this.salt,
        mek: await exportMEK(this.mek),
      }));
    },

    async restoreRememberedKey() {
      localStorage.removeItem(LEGACY_REMEMBERED_MEK_KEY);
      sessionStorage.removeItem(LEGACY_SESSION_MEK_KEY);
      const remembered = localStorage.getItem(BROWSER_MEK_KEY);
      if (!remembered) return;

      try {
        const parsed = JSON.parse(remembered);

        if (parsed.version !== 2 || parsed.keyData !== this.keyData || parsed.salt !== this.salt) {
          localStorage.removeItem(BROWSER_MEK_KEY);
          return;
        }

        this.mek = await importMEK(parsed.mek);
      } catch (error) {
        localStorage.removeItem(BROWSER_MEK_KEY);
      }
    },

    envelopeChanged(previousKeyData, previousSalt) {
      if (!previousKeyData || !previousSalt) return false;
      return previousKeyData !== this.keyData || previousSalt !== this.salt;
    },

    forgetDevice() {
      localStorage.removeItem(BROWSER_MEK_KEY);
      sessionStorage.removeItem(LEGACY_SESSION_MEK_KEY);
      localStorage.removeItem(LEGACY_REMEMBERED_MEK_KEY);
      this.mek = null;
    },

    async encrypt(value) {
      if (!this.mek) throw new Error('Memoriq vault is locked.');
      return encryptJson(value, this.mek);
    },

    async decrypt(payload) {
      if (!this.mek) throw new Error('Memoriq vault is locked.');
      return decryptJson(payload, this.mek);
    },
  },
});
