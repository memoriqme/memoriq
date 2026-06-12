import { aeskw, gcm } from '@noble/ciphers/aes.js';
import { randomBytes } from '@noble/ciphers/utils.js';
import { pbkdf2Async } from '@noble/hashes/pbkdf2.js';
import { sha256 } from '@noble/hashes/sha2.js';

export function uint8ArrayToBase64(bytes) {
  let binary = '';
  for (let i = 0; i < bytes.byteLength; i += 1) {
    binary += String.fromCharCode(bytes[i]);
  }
  return window.btoa(binary);
}

export function base64ToUint8Array(base64) {
  const binary = window.atob(base64);
  const bytes = new Uint8Array(binary.length);
  for (let i = 0; i < binary.length; i += 1) {
    bytes[i] = binary.charCodeAt(i);
  }
  return bytes;
}

function bytesToBase64Url(bytes) {
  return uint8ArrayToBase64(bytes).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/g, '');
}

function base64UrlToBytes(base64url) {
  const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
  const padded = base64.padEnd(base64.length + ((4 - (base64.length % 4)) % 4), '=');
  return base64ToUint8Array(padded);
}

function mekToJwk(mek) {
  return {
    kty: 'oct',
    k: bytesToBase64Url(mek),
    alg: 'A256GCM',
    ext: true,
    key_ops: ['encrypt', 'decrypt'],
  };
}

function jwkToMek(jwk) {
  if (!jwk?.k) throw new Error('Invalid recovery key.');
  return base64UrlToBytes(jwk.k);
}

export function generateSalt() {
  return randomBytes(16);
}

export async function generateMEK() {
  return randomBytes(32);
}

async function deriveWrappingKey(password, salt) {
  return pbkdf2Async(sha256, new TextEncoder().encode(password), salt, { c: 200000, dkLen: 32 });
}

export async function wrapMEK(password, salt, mek) {
  const wrappingKey = await deriveWrappingKey(password, salt);
  const wrapped = aeskw(wrappingKey).encrypt(mek);
  const recoveryJwk = mekToJwk(mek);

  return {
    encryptedMEK: uint8ArrayToBase64(wrapped),
    recoveryKey: window.btoa(JSON.stringify(recoveryJwk)),
  };
}

export async function unwrapMEK(password, salt, encryptedMEK) {
  const wrappingKey = await deriveWrappingKey(password, salt);
  return aeskw(wrappingKey).decrypt(base64ToUint8Array(encryptedMEK));
}

export async function exportMEK(mek) {
  return mekToJwk(mek);
}

export async function importMEK(jwk) {
  return jwkToMek(jwk);
}

export async function encryptJson(value, mek) {
  const iv = randomBytes(12);
  const plaintext = new TextEncoder().encode(JSON.stringify(value));
  const ciphertext = gcm(mek, iv).encrypt(plaintext);
  const combined = new Uint8Array(iv.length + ciphertext.length);
  combined.set(iv);
  combined.set(ciphertext, iv.length);
  return uint8ArrayToBase64(combined);
}

export async function decryptJson(payload, mek) {
  const combined = base64ToUint8Array(payload);
  const iv = combined.slice(0, 12);
  const ciphertext = combined.slice(12);
  const plaintext = gcm(mek, iv).decrypt(ciphertext);
  return JSON.parse(new TextDecoder().decode(plaintext));
}
