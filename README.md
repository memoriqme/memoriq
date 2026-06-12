# Memoriq

Memoriq is a private AI memory vault for saving useful conversations from ChatGPT, Claude, Gemini, and Grok.

The goal is simple: when an AI gives you something worth keeping, you should be able to save it, search it later, organize it into projects, export it, and delete it without handing the plaintext to another SaaS database.

This repository contains the Memoriq web app.

Chrome extension: [Chrome Web Store](https://chromewebstore.google.com/detail/memoriq/jhhjcchhlfodciphfacegemnemmjdmci) · source: [github.com/memoriqme/memoriq-extension](https://github.com/memoriqme/memoriq-extension)

## Why

AI chats are becoming personal knowledge work: legal notes, tax research, product ideas, debugging sessions, travel plans, writing drafts, and decisions you may want months later.

The provider chat history is not a great long-term memory:

- it is split across different AI products
- search is inconsistent
- export is awkward
- chats can be hard to organize after the fact
- private context often ends up duplicated in yet another service

Memoriq is a small attempt at a better pattern: keep the conversations you care about in one encrypted, portable memory vault.

## Philosophy

Memoriq is intentionally boring in the places that matter:

- Your vault should be readable by you, not by the service operator.
- Saved AI conversations should be portable and deletable.
- The hosted product should be useful, but self-hosting should remain a real option.
- The project should be honest about early software and provider capture limits.
- Privacy claims should be verifiable in source code, not just promised in copy.

## What It Does

- Saves AI conversations from ChatGPT, Claude, Gemini, and Grok through a browser extension.
- Encrypts chat titles, metadata, projects, source URLs, and message bodies before upload.
- Lets you organize saved chats into projects.
- Keeps new chats that are not assigned to a project in an Unsorted view.
- Supports browser-side search over decrypted conversation data after unlock.
- Shows original chat URL, source, date, and encrypted payload size.
- Supports manual "paste an AI reply" saves for cases where extension capture is not enough.
- Can export and import an encrypted vault backup JSON.

## Privacy Model

Memoriq is designed so the server does not receive plaintext chat content or user-visible metadata.

The server stores:

- encrypted conversation headers
- encrypted conversation bodies
- encrypted key envelope and salt
- operational fields such as row ID, user ID, timestamps, and byte size

The server does not store plaintext:

- messages
- titles
- providers
- project names
- source URLs
- snippets
- search text

Encryption happens in the browser with a locally unwrapped master encryption key. If you forget both your encryption password and recovery key, Memoriq cannot recover your chats.

More detail: [`docs/E2EE-and-extension.md`](docs/E2EE-and-extension.md)

## Current Status and Roadmap

Memoriq is in beta. The encrypted vault, browser extension, mobile sharing, search, export, and import flows are usable today, with ongoing work focused on capture reliability, media support, and polish.

The browser extension captures supported AI pages through best-effort DOM extraction. Because provider UIs change often, some captures may miss or misread content; manual paste, mobile sharing, and encrypted import/export provide fallback paths.

On mobile, Memoriq supports manual saving. A PWA share target exists through the `/share` route, but this feature is under development and may currently receive only the shared link instead of full chat content.

Media files such as images, audio, and video are not preserved in this version. The current focus is reliable private saving for useful text conversations, with fuller media support planned for the roadmap.

Planned improvements include:

- Better provider-specific extraction tests.
- More reliable capture for changing AI provider DOMs.
- Browser-side search improvements.
- Optional semantic search design that does not break the privacy model.
- More import paths for existing exported chats.
- Better handling for media attachments and rich conversation exports.

## Tech Stack

- Laravel
- Laravel Sanctum and Fortify
- Vue
- Pinia
- Vite
- MySQL
- Browser-side crypto for vault encryption/decryption

## Repository Layout

```text
app/                 Laravel backend
database/            migrations and seeders
docs/                encryption, extension, and release notes
public/              built assets and web app manifest
resources/css        app styles
resources/js         Vue app, stores, routes, crypto helpers
routes/              web and API routes
```

## Local Development

Requirements:

- PHP 8.3+
- Composer
- Node.js and npm
- MySQL

Clone and install:

```bash
git clone https://github.com/memoriqme/memoriq.git
cd memoriq
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure `.env`:

```env
APP_NAME=Memoriq
APP_URL=http://memoriq.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=memoriq
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Build or watch frontend assets:

```bash
npm run prod
# or
npm run watch
```

Serve the app with your preferred local web server. For example, on WAMP you can create an Apache virtual host that points to the `public/` directory.

For quick Laravel-only testing, you can also run:

```bash
php artisan serve
```

For the extension, install from the [Chrome Web Store](https://chromewebstore.google.com/detail/memoriq/jhhjcchhlfodciphfacegemnemmjdmci) or, for local development, clone [memoriq-extension](https://github.com/memoriqme/memoriq-extension), load it unpacked in Chrome, and choose the matching environment in the extension popup.

## Extension Flow

1. User logs into Memoriq.
2. Extension connects through `/extension/connect`.
3. Extension stores a scoped Sanctum token.
4. User unlocks the encrypted vault in the extension.
5. Extension extracts a supported AI chat page.
6. Extension encrypts header and body locally.
7. Extension uploads only ciphertext.

The extension can save through either:

- a floating button on supported chat pages
- the extension popup, with a preferred project selector

## Contributing

Issues, bug reports, and small pull requests are welcome, especially around:

- provider capture bugs
- security review
- UX simplification
- documentation
- local setup friction

If reporting an extension capture issue, please include:

- provider
- browser
- extension version
- what was missing or duplicated
- whether title, URL, or message body was wrong

## Security

Please do not open public issues for security vulnerabilities. Use [GitHub private vulnerability reporting](https://github.com/memoriqme/memoriq/security/advisories/new), or the contact details on [memoriq.me](https://memoriq.me). See [`SECURITY.md`](SECURITY.md).

## Trademark

"Memoriq" and the Memoriq logo are used as project trademarks. The AGPL license applies to the source code, but it does not grant permission to use the Memoriq name or logo to publish unofficial apps, extensions, hosted services, or other products in a way that suggests they are official or endorsed.

## License

Memoriq is licensed under the GNU Affero General Public License v3.0 only. See [`LICENSE`](LICENSE).

In short: personal use, self-hosting, studying, modifying, and sharing are allowed. If you modify Memoriq and run it as a network service for others, your modified source code must remain available under the same license.
