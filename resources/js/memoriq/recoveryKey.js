const PENDING_RECOVERY_KEY_STORAGE_KEY = 'memoriq_pending_recovery_key';

export function persistPendingRecoveryKey(recoveryKey) {
  if (!recoveryKey) return;
  sessionStorage.setItem(PENDING_RECOVERY_KEY_STORAGE_KEY, recoveryKey);
}

export function readPendingRecoveryKey() {
  return sessionStorage.getItem(PENDING_RECOVERY_KEY_STORAGE_KEY) || '';
}

export function clearPendingRecoveryKey() {
  sessionStorage.removeItem(PENDING_RECOVERY_KEY_STORAGE_KEY);
}

export function hasPendingRecoveryKey() {
  return !!readPendingRecoveryKey();
}
