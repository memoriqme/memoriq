<template>
  <main class="legal-page">
    <div class="legal-shell">
      <router-link to="/" class="legal-brand">
        <memoriq-logo :size="36" />
        <span>Memoriq</span>
      </router-link>

      <section class="legal-card">
        <p class="legal-eyebrow">Terms of Service</p>
        <h1>Terms of Service</h1>
        <p class="legal-updated">Last updated: June 7, 2026</p>

        <p>
          These terms cover use of the hosted Memoriq service at memoriq.me and related
          hosted services. Memoriq is a private AI memory vault for saving, organizing,
          searching, exporting, and deleting your own AI conversations.
        </p>

        <h2>Early software</h2>
        <p>
          Memoriq is early-stage software. The service, extension, import tools, and
          provider capture logic may change, break, or be discontinued. You should keep
          your own exports or backups of anything important.
        </p>

        <h2>Your account and encryption keys</h2>
        <p>
          You are responsible for your account access, encryption password, recovery key,
          and exported backups. Because Memoriq is designed around end-to-end encryption,
          we cannot read or recover encrypted vault contents if you lose the required keys.
        </p>

        <h2>Your content</h2>
        <p>
          You keep ownership of the conversations and data you save. You are responsible
          for making sure you have the right to save, store, export, and delete that data.
          Do not use Memoriq for unlawful content or activity.
        </p>

        <h2>Extension capture</h2>
        <p>
          The Chrome extension uses best-effort extraction from supported AI provider
          pages. Providers can change their interfaces at any time, so capture may miss
          content, duplicate content, save formatting imperfectly, or stop working until
          updated.
        </p>

        <h2>Availability and data loss</h2>
        <p>
          We try to keep the hosted service reliable, but Memoriq is provided without a
          guarantee of uninterrupted availability or loss-free operation. Use export and
          self-hosting options if you need stronger control over backups and availability.
        </p>

        <h2>Open source and self-hosting</h2>
        <p>
          The Memoriq app and extension are licensed under the GNU Affero General Public
          License v3.0 only. You may study, self-host, modify, and share the software under
          that license. Independent self-hosted deployments are operated by whoever runs
          them, not by the hosted memoriq.me service.
        </p>

        <h2>Memoriq name and logo</h2>
        <p>
          “Memoriq” and the Memoriq logo are used as project trademarks. The AGPL license
          applies to the source code, but it does not grant permission to use the Memoriq
          name or logo to publish unofficial apps, extensions, hosted services, or other
          products in a way that suggests they are official or endorsed.
        </p>

        <h2>Changes</h2>
        <p>
          These terms may be updated as the product matures. Material changes will be
          reflected on this page.
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

.legal-card p,
.legal-card li {
  color: var(--muted);
  line-height: 1.75;
}
</style>
