<template>
  <div class="mvp-landing">
    <nav class="nav">
      <div class="container nav-inner">
        <router-link to="/" class="logo">
          <memoriq-logo />
          <span class="logo-name">Memoriq</span>
        </router-link>
        <div class="nav-links" :class="{ open: navOpen }">
          <a class="desktop-nav-link" href="#why" @click="scrollToSection('why', $event)">Why Memoriq</a>
          <a class="desktop-nav-link" href="#how" @click="scrollToSection('how', $event)">How it works</a>
          <a class="desktop-nav-link" href="#privacy" @click="scrollToSection('privacy', $event)">Privacy focused</a>
          <a class="desktop-nav-link" href="#opensource" @click="scrollToSection('opensource', $event)">Open source</a>
          <a class="desktop-nav-link" href="#pricing" @click="scrollToSection('pricing', $event)">Free</a>
          <router-link to="/register" class="mobile-menu-link">Create Account</router-link>
          <router-link to="/login" class="mobile-menu-link">Log in</router-link>
          <router-link to="/terms" class="mobile-menu-link">Terms</router-link>
          <router-link to="/privacy" class="mobile-menu-link">Privacy Policy</router-link>
          <a href="mailto:hello@memoriq.me" class="mobile-menu-link">Contact</a>
        </div>
        <div class="nav-actions">
          <button type="button" class="theme-btn" :aria-label="themeLabel" @click="toggleTheme">
            <svg v-if="theme === 'dark'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          </button>
          <router-link to="/register" class="btn btn-primary nav-cta">Create free account</router-link>
          <router-link to="/login" class="nav-login">Log in</router-link>
          <button type="button" class="nav-toggle" aria-label="Menu" @click="navOpen = !navOpen">
            <svg v-if="!navOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
          </button>
        </div>
      </div>
    </nav>

    <header class="hero">
      <div class="container">
        <div class="hero-badges">
          <div class="hero-badge hero-badge-primary">🔒 End-to-end encrypted · Zero-knowledge</div>
          <div class="hero-badge">Open source - free to use - self-hostable</div>
        </div>
        <h1>Your private AI memory</h1>
        <p class="hero-lead">
          Save the conversations that matter from ChatGPT, Claude, Gemini, and Grok in one searchable vault - encrypted on your device before anything reaches our servers.
        </p>
        <p class="hero-free-note">
          <strong>Free account:</strong> 100 MB hosted vault on memoriq.me.
          Or clone the repo and self-host with unlimited storage.
        </p>
        <div class="hero-cta">
          <router-link to="/register" class="btn btn-primary btn-lg">Create free account</router-link>
          <a :href="GITHUB_URL" class="btn btn-ghost btn-lg github-btn" target="_blank" rel="noopener noreferrer">
            <svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            View source on GitHub
          </a>
        </div>
      </div>
    </header>

    <section class="providers-section">
      <div class="container">
        <div class="providers-label">Works with every major AI</div>
        <div class="providers-list">
          <span class="provider-pill-sm"><span class="provider-dot chatgpt"></span>ChatGPT</span>
          <span class="provider-pill-sm"><span class="provider-dot claude"></span>Claude</span>
          <span class="provider-pill-sm"><span class="provider-dot gemini"></span>Gemini</span>
          <span class="provider-pill-sm"><span class="provider-dot grok"></span>Grok</span>
        </div>
      </div>
    </section>

    <section id="demo" class="demo-section">
      <div class="container">
        <div class="product-card" aria-label="Memoriq product preview">
          <div class="app-preview">
            <aside class="preview-sidebar">
              <div class="preview-brand"><memoriq-logo :size="28" />Memoriq</div>
              <div class="preview-search">Search saved chats...</div>
              <div class="preview-label">Projects</div>
              <div class="preview-project active"><span class="preview-icon-dots">...</span><span>Unsorted</span><small>3</small></div>
              <div class="preview-project"><span class="preview-icon-folder"></span><span>Research</span><small>7</small></div>
              <div class="preview-project"><span class="preview-icon-folder"></span><span>Work notes</span><small>12</small></div>
              <div class="preview-label">AI Sources</div>
              <div class="preview-sources">
                <span class="preview-source-chip"><span class="provider-dot chatgpt"></span>ChatGPT</span>
                <span class="preview-source-chip"><span class="provider-dot claude"></span>Claude</span>
                <span class="preview-source-chip"><span class="provider-dot gemini"></span>Gemini</span>
                <span class="preview-source-chip"><span class="provider-dot grok"></span>Grok</span>
              </div>
              <div class="preview-label">Unsorted chats</div>
              <div class="preview-threads">
                <button
                  v-for="chat in demoChats"
                  :key="chat.id"
                  type="button"
                  class="preview-thread"
                  :class="{ active: chat.id === activeChatId }"
                  @click="selectDemoChat(chat.id)"
                >
                  <strong>{{ chat.title }}</strong>
                  <small><span class="provider-dot" :class="chat.provider"></span>{{ chat.providerLabel }} · {{ chat.dateShort }}</small>
                </button>
              </div>
            </aside>
            <section ref="previewMainRef" class="preview-main">
              <div class="preview-top">
                <div class="preview-title">{{ activeChat.title }}</div>
                <div class="preview-badges">
                  <span class="mini-badge" :class="`source-${activeChat.provider}`">{{ activeChat.providerLabel }}</span>
                  <span class="mini-badge">{{ activeChat.date }}</span>
                  <span class="mini-badge">{{ activeChat.size }}</span>
                  <span class="mini-badge">Original chat</span>
                </div>
              </div>
              <div class="preview-messages">
                <div
                  v-for="(message, index) in activeChat.messages"
                  :key="`${activeChat.id}-${index}`"
                  class="message"
                  :class="{ 'message-user': message.role === 'user' }"
                >
                  <div class="message-role">
                    <span class="avatar" :class="message.role === 'user' ? 'user' : activeChat.provider">{{ message.role === 'user' ? 'You' : activeChat.avatar }}</span>
                    {{ message.role === 'user' ? 'You' : activeChat.providerLabel }}
                  </div>
                  <div v-if="message.role === 'user'" class="bubble">{{ message.text }}</div>
                  <div v-else class="answer">
                    <template v-for="(block, blockIndex) in message.blocks" :key="blockIndex">
                      <p v-if="block.type === 'p'" v-html="block.html"></p>
                      <ul v-else-if="block.type === 'ul'">
                        <li v-for="(item, itemIndex) in block.items" :key="itemIndex">{{ item }}</li>
                      </ul>
                      <span v-else-if="block.type === 'code'" class="code-line">{{ block.text }}</span>
                    </template>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>

    <section id="why">
      <div class="container">
        <div class="section-header">
          <p class="section-label">Why Memoriq</p>
          <h2 class="section-title">Turn useful AI conversations into a private library</h2>
          <p class="section-lead">Memoriq helps you keep the chats worth revisiting searchable, encrypted, and under your control.</p>
        </div>
        <div class="why-rows">
          <article class="why-row">
            <div class="why-side why-side-problem">
              <span class="why-side-tag why-side-tag-problem">Without Memoriq</span>
              <h3>Chats can be read, used for training, or exposed in a breach</h3>
              <p>Everything you send lives on their servers under their rules.</p>
            </div>
            <div class="why-connector" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </div>
            <div class="why-side why-side-solution">
              <span class="why-side-tag why-side-tag-solution">With Memoriq</span>
              <h3>End-to-end encrypted before it leaves your device</h3>
              <p>AES-256 runs in your browser. We store ciphertext. Not even we can read it.</p>
            </div>
          </article>
          <article class="why-row">
            <div class="why-side why-side-problem">
              <span class="why-side-tag why-side-tag-problem">Without Memoriq</span>
              <h3>Split across ChatGPT, Claude, Gemini, Grok - no way to search all</h3>
              <p>Your best thinking is siloed by whoever you happened to use that day.</p>
            </div>
            <div class="why-connector" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </div>
            <div class="why-side why-side-solution">
              <span class="why-side-tag why-side-tag-solution">With Memoriq</span>
              <h3>One searchable vault across every provider</h3>
              <p>Import or capture from any AI. Search and browse everything in one place.</p>
            </div>
          </article>
          <article class="why-row">
            <div class="why-side why-side-problem">
              <span class="why-side-tag why-side-tag-problem">Without Memoriq</span>
              <h3>Threads disappear when limits hit or accounts change</h3>
              <p>A 200-message business plan or health research - gone with no warning.</p>
            </div>
            <div class="why-connector" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </div>
            <div class="why-side why-side-solution">
              <span class="why-side-tag why-side-tag-solution">With Memoriq</span>
              <h3>Kept portable - yours to search, export, and delete</h3>
              <p>Important conversations stay useful even when provider policies, accounts, or interfaces change.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section id="how">
      <div class="container">
        <div class="section-header">
          <p class="section-label">How it works</p>
          <h2 class="section-title">From scattered chats to a private memory vault</h2>
        </div>
        <div class="steps">
          <article class="step">
            <div class="step-num">01</div>
            <h3>Install the Chrome extension</h3>
            <p>Add the Memoriq extension to capture chats from ChatGPT, Claude, Gemini, and Grok in one click - or import conversation exports manually.</p>
          </article>
          <article class="step">
            <div class="step-num">02</div>
            <h3>Archive & organize</h3>
            <p>Conversations land in your vault with source, date, projects, and tags. Search and filter like you would in a chat app.</p>
          </article>
          <article class="step">
            <div class="step-num">03</div>
            <h3>Revisit anytime</h3>
            <p>Open any thread and read it exactly as you remember - formatted, searchable, encrypted, and under your control.</p>
          </article>
        </div>
        <extension-install-cta
          class="how-extension-cta"
          variant="card"
          eyebrow="Fastest way to save"
          title="Capture chats as you go"
          description="Install the free Chrome extension, sign in once, and save any conversation from ChatGPT, Claude, Gemini, or Grok without leaving the page."
        />
      </div>
    </section>

    <section id="privacy">
      <div class="container">
        <div class="privacy-card">
          <div>
            <p class="section-label">Privacy first</p>
            <h2 class="privacy-title">We can't read your conversations. That's the point.</h2>
            <p class="privacy-desc">Your AI archives hold health data, business plans, and thoughts you haven't shared with anyone. Memoriq encrypts them on your device before they ever reach our servers - the opposite of leaving them on a provider's infrastructure where they can be read, mined for training, or exposed in a breach.</p>
            <p class="privacy-desc">We can't read your archives without your keys. Privacy isn't an add-on - it's the architecture.</p>
            <div class="privacy-bullets">
              <div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>AES-256 client-side encryption before upload</div>
              <div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Zero-knowledge - you hold the encryption keys</div>
              <div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>No training on your data. Ever.</div>
              <div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Full export and delete at any time</div>
              <div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Open source - verify encryption in the codebase</div>
            </div>
            <div class="privacy-policy-links">
              <router-link to="/privacy">Read the Privacy Policy</router-link>
              <router-link to="/terms">Read the Terms</router-link>
            </div>
          </div>
          <div class="privacy-terminal-wrap">
            <div class="privacy-terminal">
              <div class="pt-comment">// What we store</div>
              <div><span class="pt-key">ciphertext</span>: <span class="pt-val">"aes256::...</span><span class="pt-val-muted">encrypted</span><span class="pt-val">..."</span></div>
              <div><span class="pt-key">iv</span>: <span class="pt-val">"random_per_message"</span></div>
              <br>
              <div class="pt-comment">// What we can read</div>
              <div><span class="pt-label">nothing from the encrypted payload</span></div>
            </div>
            <div class="privacy-terminal">
              <div class="pt-comment">// Your key, your data</div>
              <div><span class="pt-key">key</span>: <span class="pt-val-muted">lives only on your device</span></div>
              <div><span class="pt-key">server_access</span>: <span class="pt-val">"none"</span></div>
              <div><span class="pt-key">training_opt_in</span>: <span class="pt-val">"false"</span></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="opensource" class="alt">
      <div class="container">
        <div class="opensource-block">
          <div>
            <p class="section-label">Open source</p>
            <h2 class="section-title opensource-title">Open source. Inspect everything. Run it yourself.</h2>
            <p class="opensource-lead">
              Memoriq is AGPL-licensed open source software. Self-host the full app and Chrome extension on your own machine - unlimited storage, every feature, under your control.
            </p>
            <p class="opensource-lead">
              Don't trust our E2EE claims - verify them. Client-side crypto, server storage, and the browser extension are all on GitHub for anyone to audit.
            </p>
            <ul class="feature-list">
              <li>Unlimited storage when you bring your own server</li>
              <li>Full dashboard, search, projects, export, and extension capture</li>
              <li>AGPL-licensed app and extension - fork, audit, or deploy privately</li>
            </ul>
            <div class="opensource-links">
              <a :href="GITHUB_URL" class="btn btn-primary github-btn" target="_blank" rel="noopener noreferrer">
                <svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                View on GitHub
              </a>
            </div>
          </div>
          <div class="opensource-visual">
            <a :href="GITHUB_URL" class="opensource-github-link" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on GitHub">
              <svg class="github-icon-lg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            </a>
            <p>Verify, don't trust</p>
            <span class="opensource-caption">Audit the encryption path from browser to database</span>
          </div>
        </div>
      </div>
    </section>

    <section id="pricing">
      <div class="container">
        <div class="section-header">
          <p class="section-label">Free to use</p>
          <h2 class="section-title">Start with the hosted vault, or self-host everything</h2>
          <p class="section-lead">
            Pick the path that fits - a hosted vault on memoriq.me for quick use, or your own server with unlimited storage and full control.
          </p>
        </div>
        <div class="free-options">
          <article class="free-option free-option-hosted">
            <div class="free-option-header">
              <p class="free-option-label">Hosted on memoriq.me</p>
              <div class="free-option-price">
                <span class="free-option-amount">100 MB</span>
                <span class="free-option-period">free</span>
              </div>
            </div>
            <p class="free-option-desc">Use memoriq.me if you do not want to run a server.</p>
            <ul class="feature-list">
              <li>Managed vault - no server setup</li>
              <li>End-to-end encrypted - we can't read your archives</li>
              <li>Chrome extension for one-click capture</li>
              <li>Import from ChatGPT, Claude, Gemini, and Grok</li>
              <li>Export or delete anytime</li>
            </ul>
            <router-link to="/register" class="btn btn-primary">Create free account</router-link>
          </article>
          <article class="free-option free-option-selfhost">
            <div class="free-option-header">
              <p class="free-option-label">Self-hosted</p>
              <div class="free-option-price">
                <span class="free-option-amount">Unlimited</span>
                <span class="free-option-period">full control</span>
              </div>
            </div>
            <p class="free-option-desc">Run the same app on your own server.</p>
            <ul class="feature-list">
              <li>Unlimited storage on your hardware</li>
              <li>Your server, your backups</li>
              <li>Chrome extension works with your instance</li>
              <li>Audit E2EE claims - all source on GitHub</li>
              <li>Fork, modify, or deploy privately</li>
            </ul>
            <a :href="GITHUB_URL" class="btn btn-ghost github-btn" target="_blank" rel="noopener noreferrer">
              <svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
              View on GitHub
            </a>
          </article>
        </div>
      </div>
    </section>

    <section id="get-started" class="waitlist-section alt">
      <div class="container">
        <h2 class="waitlist-title">Your AI memory vault starts here</h2>
        <p class="waitlist-sub">Free, private, and open source. Create an account for 100 MB on memoriq.me, or clone the repo and self-host with no limits.</p>
        <div class="waitlist-form">
          <router-link to="/register" class="btn btn-primary">Create free account</router-link>
          <a :href="GITHUB_URL" class="btn btn-ghost github-btn" target="_blank" rel="noopener noreferrer">View on GitHub</a>
        </div>
        <p class="waitlist-note">End-to-end encrypted. Export and delete your archive anytime.</p>
        <extension-install-cta
          class="waitlist-extension-cta"
          variant="compact"
          title="Save chats in one click"
          description="Free Chrome extension for ChatGPT, Claude, Gemini, and Grok."
        />
      </div>
    </section>

    <section v-if="newsletterLandingEnabled" id="newsletter" class="newsletter-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Product updates</h2>
          <p class="section-lead">Occasional updates from Memoriq. Unsubscribe anytime.</p>
        </div>
        <form v-if="!newsletterSubscribed" class="newsletter-form" @submit.prevent="submitNewsletter">
          <input
            v-model="newsletterEmail"
            type="email"
            class="newsletter-input"
            placeholder="you@example.com"
            autocomplete="email"
            required
            :disabled="newsletterSubmitting"
          >
          <button type="submit" class="btn btn-primary" :disabled="newsletterSubmitting">
            {{ newsletterSubmitting ? 'Subscribing...' : 'Subscribe' }}
          </button>
        </form>
        <p
          v-if="newsletterFeedback"
          class="newsletter-feedback"
          :class="{ 'newsletter-feedback-error': newsletterFeedbackIsError, 'newsletter-feedback-success': newsletterSubscribed }"
        >
          {{ newsletterFeedback }}
        </p>
      </div>
    </section>

    <footer class="site-footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <router-link to="/" class="footer-brand-link">
              <memoriq-logo />
              <span>Memoriq</span>
            </router-link>
          </div>
          <nav class="footer-links" aria-label="Footer">
            <router-link to="/terms">Terms</router-link>
            <router-link to="/privacy">Privacy Policy</router-link>
            <router-link to="/register">Create Account</router-link>
            <router-link to="/login">Log in</router-link>
            <a href="mailto:hello@memoriq.me">Contact</a>
          </nav>
        </div>
        <div class="footer-bottom">
          <p class="footer-copy">© 2026 Memoriq - Your private AI memory</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import ExtensionInstallCta from '../components/ExtensionInstallCta.vue';
import MemoriqLogo from '../components/MemoriqLogo.vue';
import { applyTheme, getStoredTheme } from '../memoriq/theme';
import { GITHUB_URL } from '../memoriq/links';
import { newsletterLandingEnabled, newsletterSubscribeUrl } from '../memoriq/newsletter';

const NAV_OFFSET = 80;

const demoChats = [
  {
    id: 'chatgpt-saving',
    title: 'Which AI chats are worth saving?',
    provider: 'chatgpt',
    providerLabel: 'ChatGPT',
    date: 'Jun 5, 2026',
    dateShort: 'Jun 5',
    size: '128 KB',
    avatar: 'GPT',
    messages: [
      {
        role: 'user',
        text: 'I use ChatGPT for travel ideas, work notes, and random research. Which conversations are actually worth saving instead of leaving them in chat history?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: '<strong>Save the chats you would regret losing.</strong> Think about conversations that took real back-and-forth, not one-off answers you could ask again in five minutes.',
          },
          {
            type: 'ul',
            items: [
              'Plans and decisions you might revisit later.',
              'Research summaries with links, names, or steps you already refined.',
              'Instructions, drafts, or explanations you do not want to recreate from scratch.',
            ],
          },
          {
            type: 'p',
            html: 'Keeping those in one searchable private vault makes them much easier to find than digging through provider sidebars.',
          },
          { type: 'code', text: 'saved from ChatGPT · encrypted before upload' },
        ],
      },
    ],
  },
  {
    id: 'gemini-organize',
    title: 'How should I organize saved AI chats?',
    provider: 'gemini',
    providerLabel: 'Gemini',
    date: 'Jun 6, 2026',
    dateShort: 'Jun 6',
    size: '96 KB',
    avatar: 'Ge',
    messages: [
      {
        role: 'user',
        text: 'I use Gemini, ChatGPT, and Claude for different things. If I start saving important chats in one place, how should I organize them?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: '<strong>Organize by topic, not by AI app.</strong> Save new chats to Unsorted first, then move the useful ones into projects when you know what they are for.',
          },
          {
            type: 'ul',
            items: [
              'Travel, Work, Health, Home, Learning - whatever matches your life.',
              'Search across every provider in one vault instead of three separate histories.',
              'Give chats clear titles so future-you can find them months later.',
            ],
          },
          { type: 'code', text: 'saved from Gemini · encrypted before upload' },
        ],
      },
      {
        role: 'user',
        text: 'What if I save something sensitive, like health or finance notes?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: 'That is when encryption before upload matters. The conversation is locked on your device first, then stored as ciphertext - not as readable text on a server.',
          },
          {
            type: 'p',
            html: 'You unlock your vault when you need to read or search, and you can export or delete saved chats whenever you want.',
          },
        ],
      },
    ],
  },
  {
    id: 'claude-vault',
    title: 'How does private AI memory work?',
    provider: 'claude',
    providerLabel: 'Claude',
    date: 'Jun 4, 2026',
    dateShort: 'Jun 4',
    size: '112 KB',
    avatar: 'Cl',
    messages: [
      {
        role: 'user',
        text: 'I keep hearing about saving AI chats to a private vault. What does that actually mean for a normal user?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: '<strong>It means keeping useful AI conversations in one searchable library you control.</strong> Instead of losing them inside separate chat apps, you save the ones that matter and find them later in one place.',
          },
          {
            type: 'p',
            html: 'A private vault also means sensitive content is encrypted on your device before upload, so the hosted service should not be able to read your saved chats.',
          },
        ],
      },
      {
        role: 'user',
        text: 'So I still use ChatGPT, Claude, and Gemini normally, and just save the good ones?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: 'Exactly. Use AI tools as usual, then save only the conversations worth keeping - from the browser extension on desktop, or by pasting a single reply on mobile.',
          },
          {
            type: 'p',
            html: 'Think of it as building a personal memory library over time, not saving every random question.',
          },
        ],
      },
      {
        role: 'user',
        text: 'What if I want to leave the hosted service later?',
      },
      {
        role: 'assistant',
        blocks: [
          {
            type: 'p',
            html: 'You should still be able to export an encrypted backup and take your archive with you. Open-source and self-hosting options matter here because your memory should not be trapped in one company\'s platform.',
          },
          {
            type: 'p',
            html: 'That is the point of a vault: useful now, portable later.',
          },
        ],
      },
    ],
  },
];

const navOpen = ref(false);
const theme = ref(getStoredTheme());
const activeChatId = ref(demoChats[0].id);
const previewMainRef = ref(null);
const newsletterEmail = ref('');
const newsletterFeedback = ref('');
const newsletterFeedbackIsError = ref(false);
const newsletterSubscribed = ref(false);
const newsletterSubmitting = ref(false);
const themeLabel = computed(() => (theme.value === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'));
const activeChat = computed(() => demoChats.find((chat) => chat.id === activeChatId.value) ?? demoChats[0]);

onMounted(() => {
  applyTheme(theme.value);
  if (window.location.hash) {
    const id = window.location.hash.replace('#', '');
    scrollToSection(id);
  }
});

function toggleTheme() {
  theme.value = theme.value === 'dark' ? 'light' : 'dark';
  applyTheme(theme.value);
}

function selectDemoChat(id) {
  activeChatId.value = id;
  previewMainRef.value?.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToSection(id, event) {
  event?.preventDefault();
  navOpen.value = false;
  const el = document.getElementById(id);
  if (!el) {
    return;
  }
  const top = el.getBoundingClientRect().top + window.scrollY - NAV_OFFSET;
  window.scrollTo({ top, behavior: 'smooth' });
  history.replaceState(null, '', `#${id}`);
}

async function submitNewsletter() {
  if (!newsletterLandingEnabled) {
    return;
  }

  const email = newsletterEmail.value.trim();
  if (!email || !email.includes('@')) {
    newsletterFeedbackIsError.value = true;
    newsletterFeedback.value = 'Error: Invalid e-mail address';
    return;
  }

  newsletterSubmitting.value = true;
  newsletterFeedback.value = '';
  newsletterFeedbackIsError.value = false;

  try {
    const response = await fetch(newsletterSubscribeUrl(email), { method: 'POST' });
    const resp = (await response.text()).trim();

    if (resp === 'email error') {
      newsletterFeedbackIsError.value = true;
      newsletterFeedback.value = 'Error: Invalid e-mail address';
    } else if (resp === 'limit error') {
      newsletterFeedbackIsError.value = true;
      newsletterFeedback.value = 'You are doing that too much. Please wait and try again.';
    } else if (resp === 'success') {
      newsletterSubscribed.value = true;
      newsletterFeedbackIsError.value = false;
      newsletterFeedback.value = 'Subscribed!';
      newsletterEmail.value = '';
    } else if (!response.ok) {
      newsletterFeedbackIsError.value = true;
      newsletterFeedback.value = 'Something went wrong. Please try again.';
    }
  } catch {
    newsletterFeedbackIsError.value = true;
    newsletterFeedback.value = 'Something went wrong. Please try again.';
  } finally {
    newsletterSubmitting.value = false;
  }
}
</script>

<style scoped>
.mvp-landing {
  min-height: 100vh;
  background: var(--bg);
  color: var(--text);
}

.container {
  width: 100%;
  max-width: 1120px;
  margin: 0 auto;
  padding: 0 24px;
}

.nav {
  position: fixed;
  z-index: 100;
  top: 0;
  right: 0;
  left: 0;
  border-bottom: 1px solid var(--border-soft);
  background: var(--nav-bg);
  backdrop-filter: blur(12px);
}

.nav-inner,
.logo,
.nav-links,
.nav-actions,
.hero-cta,
.providers-list,
.preview-top,
.preview-badges,
.footer-brand-link,
.footer-links,
.footer-grid {
  display: flex;
  align-items: center;
}

.nav-inner {
  position: relative;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  height: 64px;
}

.logo {
  justify-self: start;
  gap: 10px;
  color: var(--text);
  font-size: 18px;
  font-weight: 600;
  letter-spacing: -0.02em;
}

.nav-links {
  justify-self: center;
  gap: 24px;
}

.nav-links a {
  color: var(--text-secondary);
  font-size: 14px;
  white-space: nowrap;
  transition: color 0.15s;
}

.nav-links a:hover {
  color: var(--text);
}

.nav-actions {
  justify-self: end;
  gap: 10px;
}

.nav-login {
  color: var(--text-secondary);
  font-size: 14px;
  font-weight: 600;
  white-space: nowrap;
  transition: color 0.15s;
}

.nav-login:hover {
  color: var(--text);
}

.nav-cta {
  display: inline-flex;
}

.mobile-menu-link {
  display: none;
}

.nav-toggle {
  display: none;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--surface);
  color: var(--text-secondary);
  cursor: pointer;
}

.nav-toggle:hover {
  background: var(--bg-elevated);
  color: var(--text);
}

.nav-toggle svg {
  width: 20px;
  height: 20px;
}

.logo-name {
  white-space: nowrap;
}

section[id] {
  scroll-margin-top: 80px;
}

.theme-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--surface);
  color: var(--text-secondary);
  cursor: pointer;
}

.theme-btn:hover {
  background: var(--bg-elevated);
  color: var(--text);
}

.theme-btn svg {
  width: 18px;
  height: 18px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: 0;
  border-radius: 10px;
  padding: 10px 18px;
  cursor: pointer;
  font-family: inherit;
  font-size: 14px;
  font-weight: 500;
  transition: transform 0.12s, background 0.15s, box-shadow 0.15s;
}

.btn:active {
  transform: scale(0.98);
}

.btn-primary {
  background: var(--accent);
  color: #fff;
}

.btn-primary:hover {
  background: var(--accent-hover);
  box-shadow: 0 4px 24px rgba(20, 139, 116, 0.25);
}

.btn-ghost {
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text-secondary);
}

.btn-ghost:hover {
  background: var(--surface);
  color: var(--text);
}

.btn-lg {
  border-radius: 12px;
  padding: 14px 28px;
  font-size: 15px;
}

.hero {
  padding: 140px 0 72px;
  background:
    radial-gradient(ellipse 80% 60% at 50% -20%, rgba(20, 139, 116, 0.18), transparent 60%),
    radial-gradient(ellipse 50% 40% at 100% 0%, rgba(66, 133, 244, 0.08), transparent 50%);
  text-align: center;
}

.hero-badges {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin-bottom: 28px;
}

.hero-badge {
  display: inline-flex;
  gap: 8px;
  border: 1px solid rgba(20, 139, 116, 0.25);
  border-radius: 100px;
  background: var(--accent-soft);
  color: var(--accent);
  padding: 6px 14px;
  font-size: 13px;
}

.hero-badge-primary {
  border-color: rgba(20, 139, 116, 0.45);
  font-weight: 600;
}

.hero h1 {
  max-width: 1120px;
  margin: 0 auto 24px;
  font-size: clamp(2.5rem, 6vw, 4rem);
  font-weight: 700;
  letter-spacing: -0.03em;
  line-height: 1.1;
}

.hero-lead {
  max-width: 620px;
  margin: 0 auto 20px;
  color: var(--text-secondary);
  font-size: clamp(1.05rem, 2vw, 1.25rem);
  line-height: 1.65;
}

.hero-free-note {
  max-width: 560px;
  margin: 0 auto 40px;
  color: var(--text-muted);
  font-size: 15px;
  line-height: 1.65;
}

.hero-free-note strong {
  color: var(--accent);
  font-weight: 600;
}

.hero-cta,
.providers-list {
  flex-wrap: wrap;
  justify-content: center;
  gap: 12px;
}

.providers-section {
  padding: 0 0 56px;
  text-align: center;
}

.providers-label {
  margin-bottom: 20px;
  color: var(--text-muted);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.07em;
  text-transform: uppercase;
}

.provider-pill-sm {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border: 1px solid var(--border);
  border-radius: 40px;
  background: var(--bg-card);
  color: var(--text-secondary);
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 600;
}

.provider-dot {
  border-radius: 50%;
}

.provider-dot {
  width: 8px;
  height: 8px;
}

.provider-dot.chatgpt {
  background: var(--provider-chatgpt);
}

.provider-dot.claude {
  background: var(--provider-claude);
}

.provider-dot.gemini {
  background: var(--provider-gemini);
}

.provider-dot.grok {
  background: var(--provider-grok);
}

.provider-dot.more {
  background: var(--text-muted);
}

section {
  padding: 100px 0;
}

section.alt {
  background: var(--bg-elevated);
}

.demo-section {
  padding: 0 0 100px;
}

.product-card {
  max-width: 1072px;
  overflow: hidden;
  margin: 0 auto;
  border: 1px solid var(--border);
  border-radius: 20px;
  background: var(--bg-card);
  box-shadow: var(--shadow);
}

.app-preview {
  display: grid;
  grid-template-columns: 310px 1fr;
  height: 600px;
}

.preview-sidebar {
  overflow: hidden;
  border-right: 1px solid var(--border);
  background: var(--bg-elevated);
  padding: 22px 18px;
}

.preview-brand {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 18px;
  color: #a4a4a4;
  font-size: 15px;
  font-weight: 700;
}

.preview-search,
.preview-thread,
.preview-project {
  border-radius: 9px;
  color: var(--text-muted);
}

.preview-sources {
  display: flex;
  flex-wrap: nowrap;
  gap: 5px;
  margin-bottom: 6px;
  padding: 0 2px;
}

.preview-source-chip {
  display: inline-flex;
  flex: 1 1 0;
  align-items: center;
  justify-content: center;
  gap: 4px;
  min-width: 0;
  border: 1px solid var(--border);
  border-radius: 100px;
  background: var(--surface);
  color: var(--text-secondary);
  padding: 5px 6px;
  font-size: 9px;
  font-weight: 600;
  white-space: nowrap;
}

.preview-source-chip .provider-dot {
  flex-shrink: 0;
}

.preview-search {
  display: flex;
  align-items: center;
  height: 38px;
  margin-bottom: 18px;
  background: var(--surface);
  padding: 0 12px;
  font-size: 12px;
}

.preview-label {
  margin: 14px 8px 8px;
  color: var(--text-muted);
  font-size: 10px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.preview-project {
  display: grid;
  grid-template-columns: 22px minmax(0, 1fr) auto;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
  padding: 8px 10px;
  color: var(--text-secondary);
  font-size: 12px;
}

.preview-project.active {
  background: var(--surface);
  color: var(--text);
}

.preview-project small {
  color: var(--text-muted);
}

.preview-icon-dots,
.preview-icon-folder {
  display: grid;
  width: 18px;
  height: 18px;
  place-items: center;
  border: 1px solid var(--border);
  border-radius: 5px;
  color: var(--text-muted);
  font-size: 11px;
  font-weight: 800;
  line-height: 1;
}

.preview-icon-folder {
  position: relative;
  border-radius: 4px;
}

.preview-icon-folder::before {
  content: "";
  position: absolute;
  top: 3px;
  left: 3px;
  width: 7px;
  height: 3px;
  border-radius: 2px 2px 0 0;
  background: currentColor;
}

.preview-threads {
  padding-bottom: 28px;
}

.preview-thread {
  display: grid;
  gap: 5px;
  overflow: hidden;
  width: 100%;
  margin-bottom: 5px;
  border: 0;
  padding: 10px;
  font-family: inherit;
  font-size: 12px;
  text-align: left;
  text-overflow: ellipsis;
  background: transparent;
  color: inherit;
  cursor: pointer;
  transition: background 0.15s;
}

.preview-thread:hover:not(.active) {
  background: var(--surface);
}

.preview-thread.active {
  background: var(--surface);
  color: var(--text);
}

.preview-thread strong {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.preview-thread small {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--text-secondary);
  font-size: 11px;
}

.preview-main {
  min-height: 0;
  overflow-y: auto;
  background: var(--bg);
  padding: 28px 0 36px 34px;
}

.preview-messages .message {
  padding-right: 34px;
}

.preview-messages {
  padding: 0;
}

.preview-top {
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 22px;
  border-bottom: 1px solid var(--border);
  padding: 0 34px 16px 0;
}

/* ─── Landing page light theme ─────────────────────────────────────────────
   Built from scratch by inverting the landing dark palette and merging
   it with the app light theme's clean, minimal character.
   ─────────────────────────────────────────────────────────────────────── */
[data-theme="light"] .mvp-landing {
  /* Backgrounds — warm off-white layering (mirrors dark's near-black steps) */
  --bg: #f7f7f5;
  --bg-elevated: #f0efec;
  --bg-card: #ffffff;

  /* Surfaces — warm light, for inputs / hover / active states */
  --surface: #edece8;
  --surface-strong: #e4e2dd;

  /* Borders */
  --border: #dcdad4;
  --border-soft: #e7e5e0;

  /* Typography */
  --text: #111110;
  --text-secondary: #4a4946;
  --text-muted: #878480;

  /* Brand accent — teal stays the same; contrast is fine on warm white */
  --accent: #148b74;
  --accent-hover: #117a66;
  --accent-soft: rgba(20, 139, 116, 0.09);
  --accent-border: rgba(20, 139, 116, 0.2);

  /* Semantic */
  --danger: #dc2626;
  --success: #087f5b;
  --link: #0969da;

  /* Nav */
  --nav-bg: rgba(247, 247, 245, 0.9);

  /* Depth */
  --shadow: 0 24px 80px rgba(0, 0, 0, 0.07);

  /* Chat preview / code */
  --user-bubble: #edece8;
  --code-bg: #f2f1ee;
  --code-inline-fg: #c7254e;

  /* Scrollbar */
  --scrollbar-track: #f0efec;
  --scrollbar-thumb: #c4c2bd;
  --scrollbar-thumb-hover: #a4a29d;

  color-scheme: light;
}

.preview-title {
  color: var(--text);
  font-size: 14px;
  font-weight: 700;
}

.preview-badges {
  flex-wrap: wrap;
  gap: 6px;
}

.mini-badge {
  border: 1px solid rgba(20, 139, 116, 0.2);
  border-radius: 7px;
  background: var(--accent-soft);
  color: var(--accent);
  padding: 4px 7px;
  font-size: 10px;
  font-weight: 700;
}

.mini-badge.source-gemini {
  border-color: rgba(66, 133, 244, 0.25);
  background: rgba(66, 133, 244, 0.15);
  color: #7baaf7;
}

[data-theme="light"] .mini-badge.source-gemini {
  border-color: rgba(66, 133, 244, 0.2);
  background: rgba(66, 133, 244, 0.08);
  color: #1a56db;
}

.mini-badge.source-chatgpt {
  border-color: rgba(20, 139, 116, 0.25);
  background: rgba(20, 139, 116, 0.15);
  color: #6dd4b0;
}

[data-theme="light"] .mini-badge.source-chatgpt {
  border-color: rgba(20, 139, 116, 0.2);
  background: rgba(20, 139, 116, 0.08);
  color: #0d7a61;
}

.mini-badge.source-claude {
  border-color: rgba(204, 120, 92, 0.25);
  background: rgba(204, 120, 92, 0.15);
  color: #e8a88a;
}

[data-theme="light"] .mini-badge.source-claude {
  border-color: rgba(204, 120, 92, 0.2);
  background: rgba(204, 120, 92, 0.08);
  color: #c2410c;
}

.message {
  margin-bottom: 20px;
}

.message-user .message-role {
  justify-content: flex-end;
}

.message-role {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 9px;
  color: var(--text);
  font-size: 12px;
  font-weight: 700;
}

.avatar {
  display: grid;
  width: 24px;
  height: 24px;
  place-items: center;
  border-radius: 7px;
  background: var(--accent);
  color: #fff;
  font-size: 11px;
}

.avatar.user {
  background: #6d5dfc;
}

.avatar.chatgpt {
  background: var(--provider-chatgpt);
}

.avatar.claude {
  background: var(--provider-claude);
}

.bubble {
  width: fit-content;
  max-width: 82%;
  margin-left: auto;
  border-radius: 18px;
  background: var(--surface);
  color: var(--text);
  padding: 11px 14px;
  font-size: 13px;
  line-height: 1.55;
}

.avatar.gemini {
  background: var(--provider-gemini);
}

.answer {
  color: var(--text-secondary);
  font-size: 13px;
  line-height: 1.7;
}

.answer strong {
  color: var(--text);
}

.answer ul {
  margin: 10px 0 0 18px;
}

.code-line {
  display: block;
  overflow: hidden;
  margin-top: 10px;
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--bg-elevated);
  color: var(--accent);
  padding: 10px 12px;
  font-family: var(--mono);
  font-size: 11px;
  text-overflow: ellipsis;
}

.section-header {
  margin-bottom: 48px;
  text-align: center;
}

.section-label {
  margin-bottom: 12px;
  color: var(--accent);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.section-title {
  margin: 0 0 16px;
  font-size: clamp(1.75rem, 4vw, 2.5rem);
  font-weight: 700;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.section-lead {
  max-width: 560px;
  margin: 0 auto;
  color: var(--text-secondary);
  font-size: 17px;
  line-height: 1.65;
}

.why-rows {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.why-row {
  display: grid;
  grid-template-columns: 1fr 56px 1fr;
  align-items: stretch;
  border: 1px solid var(--border);
  border-radius: 18px;
  overflow: hidden;
  background: var(--bg-card);
  transition: box-shadow 0.18s, transform 0.18s, border-color 0.18s;
}

.why-row:hover {
  transform: translateY(-2px);
  border-color: rgba(20, 139, 116, 0.35);
  box-shadow: 0 10px 32px rgba(20, 139, 116, 0.12);
}

.why-side {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 24px 26px;
}

.why-side-problem {
  background: linear-gradient(135deg, rgba(224, 108, 117, 0.08) 0%, transparent 72%);
}

.why-side-solution {
  background: linear-gradient(135deg, rgba(20, 139, 116, 0.1) 0%, transparent 72%);
}

.why-side-tag {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.why-side-tag-problem {
  color: rgba(224, 108, 117, 0.9);
}

.why-side-tag-solution {
  color: var(--accent);
}

.why-side h3 {
  margin: 0;
  font-size: 15px;
  font-weight: 600;
  line-height: 1.4;
}

.why-side-problem h3 {
  color: var(--text-secondary);
}

.why-side-solution h3 {
  color: var(--text);
}

.why-side p {
  margin: 0;
  color: var(--text-muted);
  font-size: 13px;
  line-height: 1.6;
}

.why-connector {
  display: grid;
  place-items: center;
  border-left: 1px solid var(--border);
  border-right: 1px solid var(--border);
  color: var(--accent);
  background: var(--bg-elevated);
}

.why-connector svg {
  width: 22px;
  height: 22px;
}


.free-options {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 24px;
  width: 100%;
}

.free-option {
  display: flex;
  flex-direction: column;
  gap: 16px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: var(--bg-card);
  padding: 32px;
}

.free-option-hosted {
  border-color: rgba(20, 139, 116, 0.35);
  box-shadow: 0 18px 60px rgba(20, 139, 116, 0.1);
}

.free-option-selfhost {
  border-color: var(--border);
}

.free-option-header {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.free-option-label {
  margin: 0;
  color: var(--accent);
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.07em;
  text-transform: uppercase;
}

.free-option-price {
  display: flex;
  align-items: baseline;
  gap: 10px;
}

.free-option-amount {
  color: var(--text);
  font-size: 32px;
  font-weight: 700;
  letter-spacing: -0.03em;
  line-height: 1;
}

.free-option-period {
  color: var(--text-muted);
  font-size: 15px;
  font-weight: 600;
}

.free-option-desc {
  margin: 0;
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.65;
}

.free-option .feature-list {
  flex: 1;
  margin: 0;
}

.free-option .btn {
  width: 100%;
  margin-top: auto;
}

.github-icon {
  width: 22px;
  height: 22px;
  flex-shrink: 0;
}

.github-icon-lg {
  width: 56px;
  height: 56px;
}

.github-btn {
  gap: 10px;
}

.opensource-github-link {
  display: inline-flex;
  margin-bottom: 12px;
  color: var(--text);
  transition: color 0.15s, transform 0.15s;
}

.opensource-github-link:hover {
  color: var(--accent);
  transform: scale(1.05);
}

.opensource-block {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 48px;
  align-items: center;
  border: 1px solid var(--border);
  border-radius: 20px;
  background: var(--bg-card);
  padding: 48px;
}

.opensource-title {
  margin-bottom: 16px;
  text-align: left;
}

.opensource-lead {
  margin: 0 0 12px;
  color: var(--text-secondary);
  font-size: 16px;
  line-height: 1.7;
}

.opensource-links {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 24px;
}

.opensource-visual {
  border: 1px solid rgba(20, 139, 116, 0.2);
  border-radius: 14px;
  background: var(--accent-soft);
  padding: 32px;
  text-align: center;
}

.opensource-visual p {
  margin: 0 0 8px;
  color: var(--accent);
  font-size: 15px;
  font-weight: 600;
}

.opensource-caption {
  color: var(--text-secondary);
  font-size: 13px;
  line-height: 1.5;
}

.step h3 {
  margin: 0 0 8px;
  font-size: 17px;
  font-weight: 600;
}

.step p {
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.65;
}

.feature-list {
  margin: 16px 0 0;
  padding: 0;
  list-style: none;
}

.feature-list li {
  position: relative;
  padding: 6px 0 6px 22px;
  color: var(--text-secondary);
  font-size: 14px;
}

.feature-list li::before {
  content: "✓";
  position: absolute;
  left: 0;
  color: var(--accent);
  font-weight: 600;
}

.steps {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
}

.steps {
  gap: 32px;
  margin-bottom: 40px;
}

.how-extension-cta {
  max-width: 720px;
  margin: 0 auto;
  background: var(--bg-card);
}

.step-num {
  margin-bottom: 16px;
  color: var(--accent-soft);
  font-size: 48px;
  font-weight: 700;
  letter-spacing: -0.04em;
  line-height: 1;
}

.privacy-card {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 48px;
  align-items: center;
  position: relative;
  overflow: hidden;
  border: 1px solid var(--border);
  border-radius: 20px;
  background: var(--bg-card);
  padding: 48px;
}

.privacy-card::before {
  content: "";
  position: absolute;
  top: -100px;
  right: -100px;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(20, 139, 116, 0.07), transparent 65%);
  pointer-events: none;
}

.privacy-title {
  margin: 0 0 16px;
  font-size: clamp(1.4rem, 3vw, 1.85rem);
  letter-spacing: -0.025em;
  line-height: 1.2;
}

.privacy-desc {
  margin-bottom: 24px;
  color: var(--text-secondary);
  font-size: 15px;
  line-height: 1.7;
}

.privacy-bullets,
.privacy-terminal-wrap {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.privacy-bullet {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  color: var(--text-secondary);
  font-size: 14px;
}

.privacy-bullet-check {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  margin-top: 1px;
  border: 1px solid rgba(20, 139, 116, 0.3);
  border-radius: 50%;
  background: var(--accent-soft);
  color: var(--accent);
  font-size: 11px;
}

.privacy-policy-links {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 18px;
}

.privacy-policy-links a {
  color: var(--accent);
  font-size: 0.92rem;
  font-weight: 800;
  text-decoration: none;
}

.privacy-policy-links a:hover {
  text-decoration: underline;
}

.privacy-terminal {
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--bg-elevated);
  padding: 14px 16px;
  font-family: var(--mono);
  font-size: 12px;
  line-height: 1.7;
}

.pt-comment,
.pt-val-muted {
  color: var(--text-muted);
}

.pt-key {
  color: #e06c75;
}

[data-theme="light"] .pt-key {
  color: #b91c1c;
}

.pt-val {
  color: #98c379;
}

[data-theme="light"] .pt-val {
  color: #15803d;
}

.pt-label {
  color: var(--accent);
}

.waitlist-section {
  padding: 100px 0 120px;
  text-align: center;
}

.waitlist-title {
  margin: 0 0 16px;
  font-size: clamp(1.75rem, 4vw, 2.75rem);
  letter-spacing: -0.03em;
  line-height: 1.1;
}

.waitlist-sub {
  max-width: 520px;
  margin: 0 auto 36px;
  color: var(--text-secondary);
  font-size: 17px;
  line-height: 1.65;
}

.waitlist-form {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  max-width: 440px;
  margin: 0 auto 16px;
}

.waitlist-note {
  margin: 0 auto 52px;
  color: var(--text-muted);
  font-size: 12px;
}

.waitlist-extension-cta {
  max-width: 420px;
  margin: 0 auto;
  text-align: center;
}

.waitlist-extension-cta :deep(.extension-cta) {
  gap: 8px;
  border: 0;
  background: transparent;
  padding: 19px 16px;
  text-align: center;
  justify-items: center;
}

.waitlist-extension-cta :deep(.extension-cta-icon) {
  font-size: 20px;
}

.waitlist-extension-cta :deep(.extension-cta-actions) {
  justify-content: center;
}

.waitlist-extension-cta :deep(.extension-cta-title) {
  margin-bottom: 4px;
  font-size: 13px;
}

.waitlist-extension-cta :deep(.extension-cta-desc) {
  font-size: 12px;
  line-height: 1.5;
}

.waitlist-extension-cta :deep(.extension-cta-btn) {
  padding: 8px 14px;
  font-size: 13px;
}

.newsletter-section {
  padding: 80px 0;
  text-align: center;
}

.newsletter-section .section-lead {
  max-width: none;
  white-space: nowrap;
}

.newsletter-form {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  max-width: 420px;
  margin: 0 auto;
}

.newsletter-input {
  flex: 1 1 200px;
  min-width: 0;
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--surface);
  color: var(--text);
  padding: 12px 14px;
  font-family: inherit;
  font-size: 14px;
}

.newsletter-input:focus {
  outline: none;
  border-color: rgba(20, 139, 116, 0.45);
  box-shadow: 0 0 0 3px rgba(20, 139, 116, 0.12);
}

.newsletter-input::placeholder {
  color: var(--text-muted);
}

.newsletter-feedback {
  margin: 16px auto 0;
  font-size: 15px;
  font-weight: 700;
}

.newsletter-feedback-success {
  color: #00a200;
}

.newsletter-feedback-error {
  color: #ef4444;
}

.site-footer {
  border-top: 1px solid var(--border-soft);
  background: var(--bg-elevated);
  padding: 56px 0 32px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 40px;
  align-items: center;
  padding-bottom: 40px;
  border-bottom: 1px solid var(--border-soft);
}

.footer-links {
  flex-wrap: wrap;
  justify-content: flex-end;
  gap: 12px 24px;
}

.footer-links a {
  color: var(--text-secondary);
  font-size: 14px;
  text-decoration: none;
  transition: color 0.15s;
}

.footer-links a:hover {
  color: var(--text);
}

.footer-brand-link {
  gap: 10px;
  margin-bottom: 14px;
  color: var(--text);
  font-size: 16px;
  font-weight: 600;
  letter-spacing: -0.02em;
  text-decoration: none;
}

.footer-brand-link:hover {
  color: var(--text);
}

.footer-tagline {
  max-width: 320px;
  margin: 0;
  color: var(--text-secondary);
  font-size: 14px;
  line-height: 1.65;
}

.footer-bottom {
  padding-top: 24px;
  text-align: center;
}

.footer-copy {
  margin: 0;
  color: var(--text-muted);
  font-size: 12px;
  line-height: 1.6;
}

.landing-toast {
  position: fixed;
  z-index: 200;
  bottom: 28px;
  left: 50%;
  border: 1px solid rgba(20, 139, 116, 0.35);
  border-radius: 12px;
  background: var(--bg-card);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
  color: var(--text);
  padding: 14px 20px;
  font-size: 14px;
  pointer-events: none;
  opacity: 0;
  transform: translate(-50%, 12px);
  transition: opacity 0.2s, transform 0.2s;
}

.landing-toast.show {
  opacity: 1;
  transform: translate(-50%, 0);
}

@media (max-width: 1024px) {
  .nav-links {
    gap: 16px;
  }

  .nav-links a {
    font-size: 13px;
  }

  .nav-cta {
    display: none;
  }

}

@media (max-width: 900px) {
  .nav-inner {
    grid-template-columns: 1fr auto;
  }

  .nav-links {
    display: none;
  }

  .nav-links.open {
    position: absolute;
    top: 64px;
    right: 0;
    z-index: 99;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 0;
    width: min(260px, calc(100vw - 32px));
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--bg-card);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
    padding: 10px 16px;
  }

  .nav-links.open a {
    padding: 12px 0;
    border-bottom: 1px solid var(--border-soft);
  }

  .nav-links.open .desktop-nav-link {
    display: none;
  }

  .nav-links.open .mobile-menu-link {
    display: block;
  }

  .nav-links.open a:last-child {
    border-bottom: 0;
  }

  .nav-login {
    display: none;
  }

  .nav-toggle {
    display: flex;
  }
}

@media (max-width: 768px) {
  section {
    padding: 90px 0;
  }

  .providers-section {
    padding: 0 0 46px;
  }

  .waitlist-section {
    padding: 90px 0 110px;
  }

  .newsletter-section {
    padding: 70px 0;
  }

  .site-footer {
    padding: 46px 0 32px;
  }

  .hero {
    padding: 80px 0 28px;
  }

  .hero-badges {
    flex-direction: column;
    align-items: center;
    gap: 0;
    margin-bottom: -11px;
  }

  .hero-badge + .hero-badge {
    margin-top: -13px;
  }

  .hero h1 {
    margin-top: 10px;
  }

  .providers-list {
    flex-wrap: nowrap;
    gap: 6px;
  }

  .providers-section .provider-pill-sm {
    gap: 6px;
    padding: 7px 11px;
    font-size: 12px;
    white-space: nowrap;
  }

  .providers-section .provider-dot {
    width: 7px;
    height: 7px;
  }

  .why-row {
    grid-template-columns: 1fr;
  }

  .why-connector {
    border-left: 0;
    border-right: 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 6px 0;
  }

  .why-connector svg {
    transform: rotate(90deg);
  }

  .newsletter-section .section-lead {
    white-space: normal;
  }

  .free-options,
  .steps {
    grid-template-columns: 1fr;
  }

  .opensource-block {
    grid-template-columns: 1fr;
    padding: 32px 24px;
  }

  .opensource-title {
    text-align: center;
  }

  .privacy-card {
    grid-template-columns: 1fr;
    padding: 32px 24px;
  }

  .footer-grid {
    grid-template-columns: 1fr;
    gap: 28px;
  }

  .footer-links {
    justify-content: flex-start;
  }

  .demo-section {
    display: none;
  }
}
</style>
