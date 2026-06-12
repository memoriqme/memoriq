# Memoriq E2EE and Browser Extension

## Security Model

Memoriq stores AI conversations using end-to-end encryption.

The server stores:

- encrypted conversation payloads
- opaque operational fields such as the row ID, user ID, server timestamps, and encrypted payload size
- an encrypted master encryption key (MEK)
- the salt used to derive the MEK wrapping key

The server does not store plaintext conversation messages, titles, source URLs, provider labels, tags, project names, or other user-visible conversation metadata.

## Key Hierarchy

Memoriq uses a browser-side key hierarchy:

1. The browser generates a random 256-bit AES-GCM Master Encryption Key (MEK).
2. The user's encryption password is passed through PBKDF2-SHA256 with 200,000 iterations.
3. The derived key wraps the MEK using AES-KW.
4. The wrapped MEK and salt are uploaded to the server.
5. Conversations are encrypted locally with the MEK using AES-GCM before upload.

Memoriq does not create or upload a temporary unprotected MEK. Users must set an encryption password before saving conversations.

## User Setup Flow

The dashboard checks `/api/user/encryption-key`.

If no encrypted MEK exists, the user sees a setup screen explaining:

- the password is never sent to Memoriq
- the recovery key must be stored safely
- losing both the password and recovery key means encrypted chats cannot be recovered

After setup, the dashboard displays the recovery key once so the user can store it in a password manager.

## Unlock Flow

If the account has an encrypted MEK but the device does not have a remembered key:

1. The user enters the encryption password.
2. The browser derives the wrapping key.
3. The wrapped MEK is unwrapped locally.
4. Conversation payloads can now be decrypted locally.

Users can choose to keep the vault unlocked on the current browser. This stores the raw MEK JWK in browser `localStorage` so new tabs and browser restarts can restore the unlocked vault. The settings screen includes a "Lock this browser" action that removes the remembered key. This is a convenience trade-off: anyone with access to the browser profile may be able to recover the unlocked vault key, so shared or untrusted devices should not use this option.

## Conversation API

- `GET /api/conversations?page=1&per_page=25` — paginated saved conversation headers
- `GET /api/conversations/{id}` — one saved conversation with encrypted full body
- `PATCH /api/conversations/{id}/header` — replace one encrypted header, used for moving chats between projects
- `DELETE /api/conversations/{id}` — delete one conversation (dashboard)
- `DELETE /api/conversations` — delete all conversations (vault reset flow)
- `GET /api/vault/export` — download an encrypted vault backup JSON
- `POST /api/vault/import` — replace the current encrypted vault from a backup JSON

## Conversation Storage

Conversations are stored in `memoriq_conversations`.

Important fields:

- `id`: opaque database row ID
- `user_id`: owner ID used for authorization
- `payload_version`: currently `2`
- `encrypted_header`: small AES-GCM encrypted JSON blob for local list/search previews
- `encrypted_body`: full AES-GCM encrypted JSON blob when stored in MySQL
- `body_storage_disk` / `body_storage_path`: optional Laravel Storage pointer for large encrypted bodies
- `body_bytes`: encrypted body size for quota accounting
- `created_at` / `updated_at`: server timestamps

The table does not include plaintext title, source URL, provider, tags, project, message count, search text, or content hash columns. The dashboard decrypts each header locally and reads list/search preview data from that encrypted header. Full messages are downloaded and decrypted only when the user opens a conversation.

The encrypted header currently contains:

```json
{
  "version": 2,
  "provider": "chatgpt",
  "title": "Conversation title",
  "sourceUrl": "https://chatgpt.com/...",
  "capturedAt": "2026-06-04T...",
  "project": "Inbox",
  "tags": [],
  "messageCount": 12,
  "snippet": "Short preview text...",
  "searchText": "Truncated local-only search text..."
}
```

The encrypted body currently contains:

```json
{
  "version": 2,
  "provider": "chatgpt",
  "title": "Conversation title",
  "sourceUrl": "https://chatgpt.com/...",
  "capturedAt": "2026-06-04T...",
  "messages": [
    { "role": "user", "content": "Plain text fallback", "html": "<p>Rich HTML from the page</p>", "format": "html" },
    { "role": "assistant", "content": "Plain text fallback", "html": "<pre><code>...</code></pre>", "format": "html" }
  ]
}
```

After local decryption, each message includes a readable `content` string for fallback display, plus optional provider `html` captured from the chat UI (tables, code blocks, lists, links, and images). The app sanitizes HTML before rendering. Blob image URLs from the chat page are inlined as `data:` URLs when possible; remote `https` images keep their original URL (they may expire later if the provider removes them).

Although fields are readable in these JSON examples, they are readable only after local decryption. Both header and body JSON blobs are encrypted before upload.

In the database, those JSON objects are not stored directly. A row stores base64 ciphertext, for example:

```text
encrypted_header:
aYk7mL6s4x5nJZfA8bJ9w4J6b0pQd3N2vM8k...

encrypted_body:
G3Qm8xJvT0z9pW2fN8aL1kR5uYc7bH4sP0eZ...
```

Real ciphertext is different for every save because encryption uses a random IV. The readable JSON structure only exists after the browser decrypts the payload locally with the user's vault key.

Large encrypted bodies are moved out of MySQL via Laravel Storage once they exceed `MEMORIQ_CONVERSATION_BODY_THRESHOLD` bytes (default `262144`). Use `MEMORIQ_CONVERSATION_BODY_DISK` to point at `local`, `s3`, or another configured disk. MySQL keeps ownership, encrypted header, timestamps, byte accounting, and the storage pointer.

Projects are stored in the encrypted header only. Moving a chat to a project, or outside of projects, decrypts the header locally, changes the `project` value, re-encrypts the header, and sends only the new ciphertext to the server.

## Encrypted Backups

Account settings can export a server-blind JSON backup. The backup includes:

- backup type and format version
- exported timestamp
- app version, using `APP_VERSION` when set or the current git hash when available
- encrypted MEK envelope and salt
- encrypted conversation headers and encrypted bodies

The filename uses the same timestamp and version marker, for example `memoriq-20260605-090000-a1b2c3d4e5f6.json`.

Importing a backup replaces the current user's encrypted key envelope and saved conversations. This is intentional: it lets an older backup remain recoverable even if the current vault was reset or future encryption metadata changes.

## Extension Authentication

The browser extension uses Sanctum personal access tokens, not Laravel session cookies.

Flow:

1. Extension popup opens `/extension/connect` on the selected environment.
2. If the user is not logged in, Memoriq redirects to `/login?redirect=/extension/connect`.
3. After login, `/extension/connect` creates a scoped token.
4. The extension content script reads the one-time connection payload from the connect page.
5. The extension stores the token in `chrome.storage.local`.
6. API calls use `Authorization: Bearer <token>`.

This avoids cross-origin session cookie and CSRF complexity between:

- `chrome-extension://...`
- `http://memoriq.local`
- `https://memoriq.me`

## Extension Encryption

The extension also implements the same Web Crypto logic:

- fetch encrypted MEK and salt from `/api/user/encryption-key`
- ask the user for the Memoriq encryption password
- unwrap the MEK locally
- store the raw MEK JWK in `chrome.storage.session` until the browser session ends or the user forgets the extension
- encrypt extracted conversations before upload

The extension never sends the encryption password, plaintext conversation content, or plaintext conversation metadata to the server. It sends only `encrypted_header` and `encrypted_body`.

## Current Limitations

The extension uses best-effort DOM extraction from ChatGPT, Claude, Gemini, and Grok. AI provider UIs change frequently, so selectors may need maintenance. For long Gemini chats, scroll to the top before saving so older messages are loaded into the page. Media files such as images, audio, and video may not be preserved.

The dashboard search currently operates on decrypted data in the browser after unlock. Server-side semantic search should wait until the encrypted search architecture is designed.
