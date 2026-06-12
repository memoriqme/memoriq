<template>
  <main class="legal-page">
    <div class="legal-shell">
      <router-link to="/" class="legal-brand">
        <memoriq-logo :size="36" />
        <span>Memoriq</span>
      </router-link>

      <section class="legal-card">
        <p class="legal-eyebrow">Privacy Policy</p>
        <h1>Privacy Policy</h1>
        <p class="legal-updated">Last updated: June 9, 2026</p>

        <p>
          Memoriq is a private AI memory vault for saving conversations from AI tools.
          The product is designed around end-to-end encryption: conversation content and
          user-visible metadata are encrypted in your browser before they are uploaded.
        </p>

        <h2>What we store</h2>
        <p>When you use the hosted Memoriq service, we may store:</p>
        <ul>
          <li>Your account email address and authentication/session records.</li>
          <li>Encrypted vault key material, salts, and recovery data needed to unlock your vault on your devices.</li>
          <li>Encrypted conversation headers and bodies.</li>
          <li>Operational records such as user IDs, row IDs, timestamps, byte sizes, and extension tokens.</li>
          <li>Newsletter email addresses, if you choose to subscribe.</li>
        </ul>

        <h2>What we cannot read</h2>
        <p>
          Memoriq is built so the hosted server does not receive plaintext conversation
          titles, messages, projects, source URLs, snippets, or searchable text. Your
          encryption password is not sent to the server. If you lose both your encryption
          password and recovery key, we cannot recover your encrypted conversations.
        </p>

        <h2>AI training</h2>
        <p>
          We do not train AI models on your saved conversations. The hosted service stores
          encrypted payloads, and we do not have the plaintext needed to read or train on
          your vault contents.
        </p>

        <h2>Analytics</h2>
        <p>
          We may use privacy-conscious analytics to understand basic product usage, traffic
          sources, pages visited, device/browser information, and approximate location.
          Analytics are used to improve the product and do not give us access to your
          encrypted vault contents.
        </p>

        <h2>Chrome extension</h2>
        <p>
          The Memoriq browser extension is optional software that saves supported AI chat
          conversations into your Memoriq vault. Install it from the
          <a href="https://chromewebstore.google.com/detail/memoriq/jhhjcchhlfodciphfacegemnemmjdmci" target="_blank" rel="noopener noreferrer">Chrome Web Store</a>.
          Source code is open (AGPL-3.0) on
          <a href="https://github.com/memoriqme/memoriq-extension" target="_blank" rel="noopener noreferrer">GitHub</a>.
        </p>

        <h3>What the extension can access</h3>
        <p>When you use the extension on a supported AI chat page, it may read, locally in your browser:</p>
        <ul>
          <li>Conversation text visible on the page, or returned through the provider&apos;s own logged-in APIs where available.</li>
          <li>The page URL and a best-effort conversation title.</li>
          <li>Your Memoriq extension connection token after you connect through memoriq.me.</li>
          <li>Your Memoriq encryption key envelope after you unlock the vault in the extension popup.</li>
        </ul>
        <p>
          For ChatGPT, the extension may call OpenAI&apos;s same-origin conversation API
          (<code>/backend-api/conversation/…</code>) using your existing logged-in browser
          session so it can save the full chat without scraping incomplete page HTML.
          That request stays between your browser and ChatGPT; Memoriq does not receive your
          ChatGPT session token.
        </p>
        <p>
          For Claude, Gemini, and Grok, capture currently uses page structure (DOM extraction).
          Provider UIs change often, so capture quality is best-effort for those sites.
        </p>

        <h3>What the extension stores locally</h3>
        <ul>
          <li>A scoped Memoriq API token and basic account metadata after you connect.</li>
          <li>Your save preferences, such as save mode and preferred project.</li>
          <li>Your unlocked vault key material in browser session storage while the vault stays unlocked in the extension. It is cleared when you close the browser session or use &ldquo;Forget extension&rdquo;.</li>
        </ul>
        <p>Your Memoriq encryption password is not stored by the extension.</p>

        <h3>What the extension sends to Memoriq</h3>
        <p>
          The extension encrypts conversation headers and bodies in your browser, then uploads
          only ciphertext to your Memoriq account. Plaintext chats, titles, messages, and
          searchable text are not sent to the Memoriq server.
        </p>

        <h3>Permissions</h3>
        <ul>
          <li><strong>storage</strong> &mdash; save connection state and extension preferences.</li>
          <li><strong>activeTab</strong> &mdash; interact with the current tab when you save from the popup.</li>
          <li><strong>Host access to memoriq.me</strong> &mdash; connect the extension to your Memoriq account.</li>
          <li><strong>Host access to supported AI sites</strong> &mdash; run capture code only on ChatGPT, Claude, Gemini, Grok, and selected X/Grok routes when you choose to save.</li>
        </ul>

        <h3>What the extension does not do</h3>
        <ul>
          <li>It does not sell or share your chats.</li>
          <li>It does not train AI models on your saved conversations.</li>
          <li>It does not run remote code; all extension code is bundled in the package.</li>
          <li>It does not capture pages unless you open a supported chat and choose to save.</li>
        </ul>

        <h3>Removing extension data</h3>
        <p>
          Use &ldquo;Forget extension&rdquo; in the popup to remove the stored Memoriq token and
          local extension preferences. Uninstalling the extension removes its browser storage.
          Saved conversations already uploaded to Memoriq remain in your vault until you delete
          them in the app.
        </p>

        <h2>Export and deletion</h2>
        <p>
          You can export your encrypted vault backup and delete saved conversations from
          the app. You can delete your entire account from Settings, which removes your
          profile, encrypted vault, saved conversations, and extension tokens from the
          hosted service.
        </p>
        <p>
          Deleting your account or hosted data may not immediately remove copies from
          ordinary backups, but backup copies are retained only for operational recovery
          and expire through normal backup rotation. Routine server and security logs may
          retain non-content operational records (such as timestamps and internal IDs)
          for a limited period before rotation; they are not used to recover deleted vault
          contents.
        </p>

        <h2>Self-hosting</h2>
        <p>
          If you self-host Memoriq, you control the server, database, backups, logs, and
          access policies for that deployment. This privacy policy applies to the hosted
          memoriq.me service, not to independent self-hosted instances.
        </p>

        <h2>Contact</h2>
        <p>
          For privacy or security questions, use the contact path published by the project
          owner or repository.
        </p>
      </section>
    </div>
  </main>
</template>

<script setup>
import MemoriqLogo from '../components/MemoriqLogo.vue';
</script>

<style scoped>
.legal-page {
  min-height: 100vh;
  background:
    radial-gradient(circle at top left, rgba(79, 70, 229, 0.16), transparent 34rem),
    var(--bg);
  color: var(--text);
  padding: 40px 20px;
}

.legal-shell {
  width: 100%;
  max-width: 840px;
  margin: 0 auto;
}

.legal-brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
  color: var(--text);
  font-weight: 800;
  text-decoration: none;
}

.legal-updated {
  color: var(--muted);
}

.legal-card {
  border: 1px solid var(--border);
  border-radius: 28px;
  background: var(--card);
  box-shadow: var(--shadow);
  padding: clamp(28px, 5vw, 56px);
}

.legal-eyebrow {
  margin: 0 0 10px;
  color: var(--accent);
  font-size: 0.78rem;
  font-weight: 800;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.legal-card h1 {
  margin: 0;
  font-size: clamp(2.1rem, 6vw, 3.6rem);
  letter-spacing: -0.05em;
}

.legal-card h2 {
  margin: 32px 0 10px;
  font-size: 1.18rem;
}

.legal-card h3 {
  margin: 24px 0 8px;
  font-size: 1rem;
}

.legal-card p,
.legal-card li {
  color: var(--muted);
  line-height: 1.75;
}

.legal-card ul {
  padding-left: 1.2rem;
}
</style>
