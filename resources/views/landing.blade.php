<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    @php
        $shareTitle = 'Private ChatGPT & Claude Archive - Encrypted AI Chat Vault | Memoriq';
        $shareDescription = 'Archive and search saved conversations from ChatGPT, Claude, Gemini, and Grok in one encrypted AI chat vault.';
        $shareUrl = rtrim(config('app.url'), '/');
        $shareImage = $shareUrl.'/img/thumb.png';
        $githubUrl = 'https://github.com/memoriqme';
        $xUrl = 'https://x.com/memoriqme';
        $chromeUrl = 'https://chromewebstore.google.com/detail/memoriq/jhhjcchhlfodciphfacegemnemmjdmci';
        $firefoxUrl = 'https://addons.mozilla.org/en-US/firefox/addon/memoriq/';
    @endphp
    <title>{{ $shareTitle }}</title>
    <link rel="icon" href="/icons/memoriq.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/icons/memoriq-180.png">
    <link rel="manifest" href="/manifest.webmanifest?v=3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Memoriq">
    <meta name="application-name" content="Memoriq">
    <meta name="theme-color" content="#148b74">
    <link rel="canonical" href="{{ $shareUrl }}/">
    <meta property="og:site_name" content="Memoriq">
    <meta property="og:title" content="{{ $shareTitle }}">
    <meta property="og:description" content="{{ $shareDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $shareUrl }}/">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:secure_url" content="{{ $shareImage }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="800">
    <meta property="og:image:alt" content="Memoriq - private encrypted AI conversation archive">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@memoriq">
    <meta name="twitter:title" content="{{ $shareTitle }}">
    <meta name="twitter:description" content="{{ $shareDescription }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <meta name="twitter:image:alt" content="Memoriq - private encrypted AI conversation archive">
    <script>document.documentElement.dataset.theme = localStorage.getItem('memoriq-theme') === 'light' ? 'light' : 'dark';</script>
    @vite(['resources/css/app.css', 'resources/css/landing.css'])
    @if (config('services.analytics.enabled'))
        <script>
            var _paq = window._paq = window._paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u = @json(config('services.analytics.url'));
                _paq.push(['setTrackerUrl', u + 'matomo.php']);
                _paq.push(['setSiteId', @json(config('services.analytics.site_id'))]);
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.async = true; g.src = u + 'matomo.js'; s.parentNode.insertBefore(g, s);
            })();
        </script>
    @endif
</head>
<body>
<svg aria-hidden="true" width="0" height="0" style="position:absolute">
    <symbol id="memoriq-logo" viewBox="0 0 46.09 46.09">
        <rect width="46.09" height="46.09" rx="11.78" fill="#148B74" />
        <path fill="#fff" d="M31.36 12.5c.66 0 1.2.21 1.62.63.41.42.62.97.62 1.64v16.74c0 .65-.19 1.16-.57 1.53-.38.37-.89.56-1.51.56-.6 0-1.1-.19-1.48-.56-.38-.37-.57-.88-.57-1.53v-10.4l-4.29 7.87c-.28.51-.59.88-.92 1.12-.33.24-.73.35-1.19.35-.44 0-.84-.12-1.18-.35-.34-.24-.65-.61-.94-1.12l-4.29-7.72v10.25c0 .63-.19 1.13-.57 1.52-.38.38-.89.57-1.51.57-.6 0-1.1-.19-1.48-.56-.38-.37-.57-.88-.57-1.53V14.77c0-.67.21-1.21.62-1.64.41-.42.95-.63 1.62-.63.97 0 1.7.5 2.21 1.5l6.14 11.34 6.11-11.34c.54-1 1.27-1.5 2.18-1.5z" />
    </symbol>
</svg>
<div class="mvp-landing" data-landing-theme="dark">
    <nav class="nav">
        <div class="container nav-inner">
            <a href="/" class="logo">
                <svg class="memoriq-logo" width="32" height="32" aria-hidden="true"><use href="#memoriq-logo" /></svg>
                <span class="logo-name">Memoriq</span>
            </a>
            <div class="nav-links" id="nav-links">
                <a class="desktop-nav-link" href="#why">Why Memoriq</a>
                <a class="desktop-nav-link" href="#how">How it works</a>
                <a class="desktop-nav-link" href="#privacy">Privacy focused</a>
                <a class="desktop-nav-link" href="#opensource">Open source</a>
                <a class="desktop-nav-link" href="#pricing">Free</a>
                <a href="/register" class="mobile-menu-link">Create Account</a>
                <a href="/login" class="mobile-menu-link">Log in</a>
                <a href="/terms" class="mobile-menu-link">Terms</a>
                <a href="/privacy" class="mobile-menu-link">Privacy Policy</a>
                <a href="mailto:hello@memoriq.me" class="mobile-menu-link">Contact</a>
            </div>
            <div class="nav-actions">
                <button type="button" class="theme-btn" id="theme-toggle" aria-label="Switch to light mode">
                    <svg class="theme-icon theme-icon-sun" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
                <a href="/register" class="btn btn-primary nav-cta">Create free account</a>
                <a href="/login" class="nav-login">Log in</a>
                <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Menu" aria-expanded="false">
                    <svg class="nav-icon-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                    <svg class="nav-icon-close" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container hero-group">
            <div class="hero-badges">
                <div class="hero-badge"><span class="hero-badge-icon" aria-hidden="true">🔒</span><span class="hero-badge-text">End-to-End Encrypted Vault</span></div>
            </div>
            <h1>Your Private AI Chat Archive</h1>
            <p class="hero-lead">Save the conversations that matter from ChatGPT, Claude, Gemini, and Grok in one searchable vault - encrypted on your device before anything reaches our servers.</p>
            <p class="hero-free-note"><strong>Free account:</strong> 100 MB hosted vault on memoriq.me. Or clone the repo and self-host with unlimited storage.</p>
            <div class="hero-cta">
                <a href="/register" class="btn btn-primary btn-lg">Create free account</a>
                <a href="{{ $githubUrl }}" class="btn btn-ghost btn-lg github-btn" target="_blank" rel="noopener noreferrer">
                    <svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    View source on GitHub
                </a>
            </div>
            <div class="providers-section">
                <div class="providers-label">Works with every major AI</div>
                <div class="providers-list">
                    <span class="provider-pill-sm"><span class="provider-dot chatgpt"></span>ChatGPT</span>
                    <span class="provider-pill-sm"><span class="provider-dot claude"></span>Claude</span>
                    <span class="provider-pill-sm"><span class="provider-dot gemini"></span>Gemini</span>
                    <span class="provider-pill-sm"><span class="provider-dot grok"></span>Grok</span>
                </div>
            </div>
        </div>
    </header>

    <section id="demo" class="demo-section">
        <div class="container">
            <div class="product-card" aria-label="Memoriq product preview">
                <div class="app-preview">
                    <aside class="preview-sidebar">
                        <div class="preview-brand"><svg class="memoriq-logo" width="28" height="28" aria-hidden="true"><use href="#memoriq-logo" /></svg>Memoriq</div>
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
                            <button type="button" class="preview-thread active" data-chat="chatgpt-saving"><strong>Which AI chats are worth saving?</strong><small><span class="provider-dot chatgpt"></span>ChatGPT · Jun 5</small></button>
                            <button type="button" class="preview-thread" data-chat="gemini-organize"><strong>How should I organize saved AI chats?</strong><small><span class="provider-dot gemini"></span>Gemini · Jun 6</small></button>
                            <button type="button" class="preview-thread" data-chat="claude-vault"><strong>How does private AI memory work?</strong><small><span class="provider-dot claude"></span>Claude · Jun 4</small></button>
                        </div>
                    </aside>
                    <section class="preview-main">
                        <div class="preview-top">
                            <div class="preview-title">Which AI chats are worth saving?</div>
                            <div class="preview-badges">
                                <span class="mini-badge source-chatgpt" data-demo-source>ChatGPT</span>
                                <span class="mini-badge" data-demo-date>Jun 5, 2026</span>
                                <span class="mini-badge" data-demo-size>128 KB</span>
                                <span class="mini-badge">Original chat</span>
                            </div>
                        </div>
                        <div class="preview-messages demo-chat-panel" data-chat-panel="chatgpt-saving">
                            <div class="message message-user">
                                <div class="message-role"><span class="avatar user">You</span>You</div>
                                <div class="bubble">I use ChatGPT for travel ideas, work notes, and random research. Which conversations are actually worth saving instead of leaving them in chat history?</div>
                            </div>
                            <div class="message">
                                <div class="message-role"><span class="avatar chatgpt">GPT</span>ChatGPT</div>
                                <div class="answer">
                                    <p><strong>Save the chats you would regret losing.</strong> Think about conversations that took real back-and-forth, not one-off answers you could ask again in five minutes.</p>
                                    <ul><li>Plans and decisions you might revisit later.</li><li>Research summaries with links, names, or steps you already refined.</li><li>Instructions, drafts, or explanations you do not want to recreate from scratch.</li></ul>
                                    <p>Keeping those in one searchable private vault makes them much easier to find than digging through provider sidebars.</p>
                                    <span class="code-line">saved from ChatGPT · encrypted before upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-messages demo-chat-panel" data-chat-panel="gemini-organize" hidden>
                            <div class="message message-user"><div class="message-role"><span class="avatar user">You</span>You</div><div class="bubble">I use Gemini, ChatGPT, and Claude for different things. If I start saving important chats in one place, how should I organize them?</div></div>
                            <div class="message"><div class="message-role"><span class="avatar gemini">Ge</span>Gemini</div><div class="answer"><p><strong>Organize by topic, not by AI app.</strong> Save new chats to Unsorted first, then move the useful ones into projects when you know what they are for.</p><ul><li>Travel, Work, Health, Home, Learning - whatever matches your life.</li><li>Search across supported providers in one vault instead of three separate histories.</li><li>Give chats clear titles so future-you can find them months later.</li></ul><span class="code-line">saved from Gemini · encrypted before upload</span></div></div>
                            <div class="message message-user"><div class="message-role"><span class="avatar user">You</span>You</div><div class="bubble">What if I save something sensitive, like health or finance notes?</div></div>
                            <div class="message"><div class="message-role"><span class="avatar gemini">Ge</span>Gemini</div><div class="answer"><p>That is when encryption before upload matters. The conversation is locked on your device first, then stored as ciphertext - not as readable text on a server.</p><p>You unlock your vault when you need to read or search, and you can export or delete saved chats whenever you want.</p></div></div>
                        </div>
                        <div class="preview-messages demo-chat-panel" data-chat-panel="claude-vault" hidden>
                            <div class="message message-user"><div class="message-role"><span class="avatar user">You</span>You</div><div class="bubble">I keep hearing about saving AI chats to a private vault. What does that actually mean for a normal user?</div></div>
                            <div class="message"><div class="message-role"><span class="avatar claude">Cl</span>Claude</div><div class="answer"><p><strong>It means keeping useful AI conversations in one searchable library you control.</strong> Instead of losing them inside separate chat apps, you save the ones that matter and find them later in one place.</p><p>A private vault also means sensitive content is encrypted on your device before upload, so the hosted service should not be able to read your saved chats.</p></div></div>
                            <div class="message message-user"><div class="message-role"><span class="avatar user">You</span>You</div><div class="bubble">So I still use ChatGPT, Claude, and Gemini normally, and just save the good ones?</div></div>
                            <div class="message"><div class="message-role"><span class="avatar claude">Cl</span>Claude</div><div class="answer"><p>Exactly. Use AI tools as usual, then save only the conversations worth keeping - from the browser extension on desktop, or by pasting a single reply on mobile.</p><p>Think of it as building a personal memory library over time, not saving every random question.</p></div></div>
                            <div class="message message-user"><div class="message-role"><span class="avatar user">You</span>You</div><div class="bubble">What if I want to leave the hosted service later?</div></div>
                            <div class="message"><div class="message-role"><span class="avatar claude">Cl</span>Claude</div><div class="answer"><p>You should still be able to export an encrypted backup and take your archive with you. Open-source and self-hosting options matter here because your memory should not be trapped in one company's platform.</p><p>That is the point of a vault: useful now, portable later.</p></div></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <section id="why">
        <div class="container">
            <div class="section-header"><p class="section-label">Why Memoriq</p><h2 class="section-title">Turn useful AI conversations into a private library</h2><p class="section-lead">Memoriq helps you keep the chats worth revisiting searchable, encrypted, and under your control.</p></div>
            <div class="why-rows">
                <article class="why-row"><div class="why-side why-side-problem"><span class="why-side-tag why-side-tag-problem">Without Memoriq</span><h3>Chats can be read, used for training, or exposed in a breach</h3><p>Everything you send lives on their servers, under their privacy, retention, and training rules.</p></div><div class="why-connector" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></div><div class="why-side why-side-solution"><span class="why-side-tag why-side-tag-solution">With Memoriq</span><h3>End-to-end encrypted before it leaves your device</h3><p>AES-256 runs in your browser. We store ciphertext. Not even we can read it.</p></div></article>
                <article class="why-row"><div class="why-side why-side-problem"><span class="why-side-tag why-side-tag-problem">Without Memoriq</span><h3>Split across ChatGPT, Claude, Gemini, Grok - no way to search all</h3><p>Your best thinking is siloed by whichever supported provider you used that day.</p></div><div class="why-connector" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></div><div class="why-side why-side-solution"><span class="why-side-tag why-side-tag-solution">With Memoriq</span><h3>One searchable vault across supported providers</h3><p>Save supported AI chats and search your saved conversations in one place.</p></div></article>
                <article class="why-row"><div class="why-side why-side-problem"><span class="why-side-tag why-side-tag-problem">Without Memoriq</span><h3>Threads disappear when limits hit or accounts change</h3><p>A 200-message business plan or health research - gone with no warning.</p></div><div class="why-connector" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></div><div class="why-side why-side-solution"><span class="why-side-tag why-side-tag-solution">With Memoriq</span><h3>Kept portable - yours to search, export, and delete</h3><p>Important conversations stay useful even when provider policies, accounts, or interfaces change.</p></div></article>
            </div>
        </div>
    </section>

    <section id="how">
        <div class="container">
            <div class="section-header"><p class="section-label">How it works</p><h2 class="section-title">From scattered chats to one encrypted AI conversation archive</h2></div>
            <div class="steps">
                <article class="step"><div class="step-num">01</div><h3>Install the browser extension</h3><p>Add the Memoriq extension to save chats from ChatGPT, Claude, Gemini, and Grok in one click - or paste a useful AI response manually.</p></article>
                <article class="step"><div class="step-num">02</div><h3>Archive &amp; organize</h3><p>Conversations land in your vault with source, date, projects, and tags. Search and filter like you would in a chat app.</p></article>
                <article class="step"><div class="step-num">03</div><h3>Revisit anytime</h3><p>Open any thread and read it exactly as you remember - formatted, searchable, encrypted, and under your control.</p></article>
            </div>
            <div class="extension-cta extension-cta--card how-extension-cta">
                <div class="extension-cta-icon" aria-hidden="true">🧩</div>
                <div class="extension-cta-copy"><p class="extension-cta-eyebrow">Fastest way to save</p><h3 class="extension-cta-title">Capture chats as you go</h3><p class="extension-cta-desc">Install the free browser extension for Chrome or Firefox, sign in once, and save any conversation from ChatGPT, Claude, Gemini, or Grok without leaving the page.</p></div>
                <div class="extension-cta-actions">
                    <a href="{{ $chromeUrl }}" class="extension-cta-btn extension-cta-btn-primary" target="_blank" rel="noopener noreferrer"><svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0C8.21 0 4.831 1.757 2.632 4.501l3.953 6.848A5.454 5.454 0 0 1 12 6.545h10.691A12 12 0 0 0 12 0zM1.931 5.47A11.943 11.943 0 0 0 0 12c0 6.012 4.42 10.991 10.189 11.864l3.953-6.847a5.45 5.45 0 0 1-6.865-2.29zm13.342 2.166a5.446 5.446 0 0 1 1.45 7.09l.002.001h-.002l-5.344 9.257c.206.01.413.016.621.016 6.627 0 12-5.373 12-12 0-1.54-.29-3.011-.818-4.364zM12 16.364a4.364 4.364 0 1 1 0-8.728 4.364 4.364 0 0 1 0 8.728Z"/></svg>Chrome</a>
                    <a href="{{ $firefoxUrl }}" class="extension-cta-btn extension-cta-btn-ghost" target="_blank" rel="noopener noreferrer"><svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20.452 3.445a11.002 11.002 0 00-2.482-1.908C16.944.997 15.098.093 12.477.032c-.734-.017-1.457.03-2.174.144-.72.114-1.398.292-2.118.56-1.017.377-1.996.975-2.574 1.554.583-.349 1.476-.733 2.55-.992a10.083 10.083 0 013.729-.167c2.341.34 4.178 1.381 5.48 2.625a8.066 8.066 0 011.298 1.587c1.468 2.382 1.33 5.376.184 7.142-.85 1.312-2.67 2.544-4.37 2.53-.583-.023-1.438-.152-2.25-.566-2.629-1.343-3.021-4.688-1.118-6.306-.632-.136-1.82.13-2.646 1.363-.742 1.107-.7 2.816-.242 4.028a6.473 6.473 0 01-.59-1.895 7.695 7.695 0 01.416-3.845A8.212 8.212 0 019.45 5.399c.896-1.069 1.908-1.72 2.75-2.005-.54-.471-1.411-.738-2.421-.767C8.31 2.583 6.327 3.061 4.7 4.41a8.148 8.148 0 00-1.976 2.414c-.455.836-.691 1.659-.697 1.678.122-1.445.704-2.994 1.248-4.055-.79.413-1.827 1.668-2.41 3.042C.095 9.37-.2 11.608.14 13.989c.966 5.668 5.9 9.982 11.843 9.982C18.62 23.971 24 18.591 24 11.956a11.93 11.93 0 00-3.548-8.511z"/></svg>Firefox</a>
                </div>
            </div>
        </div>
    </section>

    <section id="privacy">
        <div class="container">
            <div class="privacy-card">
                <div><p class="section-label">Privacy first</p><h2 class="privacy-title">We can't read your conversations. That's the point.</h2><p class="privacy-desc">Your AI archives hold health data, business plans, and thoughts you haven't shared with anyone. Memoriq encrypts them on your device before they ever reach our servers - the opposite of leaving them on a provider's infrastructure where they can be read, mined for training, or exposed in a breach.</p><p class="privacy-desc">We can't read your archives without your keys. Privacy isn't an add-on - it's the architecture.</p>
                    <div class="privacy-bullets"><div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>AES-256 client-side encryption before upload</div><div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Zero-knowledge - you hold the encryption keys</div><div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>No training on your data. Ever.</div><div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Full export and delete at any time</div><div class="privacy-bullet"><div class="privacy-bullet-check">✓</div>Open source - verify encryption in the codebase</div></div>
                    <div class="privacy-policy-links"><a href="/privacy">Read the Privacy Policy</a><a href="/terms">Read the Terms</a></div>
                </div>
                <div class="privacy-terminal-wrap"><div class="privacy-terminal"><div class="pt-comment">// What we store</div><div><span class="pt-key">ciphertext</span>: <span class="pt-val">"aes256::...</span><span class="pt-val-muted">encrypted</span><span class="pt-val">..."</span></div><div><span class="pt-key">iv</span>: <span class="pt-val">"random_per_message"</span></div><br><div class="pt-comment">// What we can read</div><div><span class="pt-label">nothing from the encrypted payload</span></div></div><div class="privacy-terminal"><div class="pt-comment">// Your key, your data</div><div><span class="pt-key">key</span>: <span class="pt-val-muted">lives only on your device</span></div><div><span class="pt-key">server_access</span>: <span class="pt-val">"none"</span></div><div><span class="pt-key">training_opt_in</span>: <span class="pt-val">"false"</span></div></div></div>
            </div>
        </div>
    </section>

    <section id="opensource" class="alt">
        <div class="container"><div class="opensource-block">
            <div><p class="section-label">Open source</p><h2 class="section-title opensource-title">Open source. Inspect everything. Run it yourself.</h2><p class="opensource-lead">Memoriq is AGPL-licensed open source software. Self-host the full app and browser extension on your own machine - unlimited storage, every feature, under your control.</p><p class="opensource-lead">Don't trust our E2EE claims - verify them. Client-side crypto, server storage, and the browser extension are all on GitHub for anyone to audit.</p><ul class="feature-list"><li>Unlimited storage when you bring your own server</li><li>Full dashboard, search, projects, export, and extension capture</li><li>AGPL-licensed app and extension - fork, audit, or deploy privately</li></ul><div class="opensource-links"><a href="{{ $githubUrl }}" class="btn btn-primary github-btn" target="_blank" rel="noopener noreferrer"><svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>View on GitHub</a></div></div>
            <div class="opensource-visual"><a href="{{ $githubUrl }}" class="opensource-github-link" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on GitHub"><svg class="github-icon-lg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg></a><p>Verify, don't trust</p><span class="opensource-caption">Audit the encryption path from browser to database</span></div>
        </div></div>
    </section>

    <section id="pricing">
        <div class="container">
            <div class="section-header"><p class="section-label">Free to use</p><h2 class="section-title">Start with the hosted vault, or self-host everything</h2><p class="section-lead">Pick the path that fits - a hosted vault on memoriq.me for quick use, or your own server with unlimited storage and full control.</p></div>
            <div class="free-options">
                <article class="free-option free-option-hosted"><div class="free-option-header"><p class="free-option-label">Hosted on memoriq.me</p><div class="free-option-price"><span class="free-option-amount">100 MB</span><span class="free-option-period">free</span></div></div><p class="free-option-desc">Use memoriq.me if you do not want to run a server.</p><ul class="feature-list"><li>Managed vault - no server setup</li><li>End-to-end encrypted - we can't read your archives</li><li>Browser extension for one-click capture (Chrome &amp; Firefox)</li><li>Archive chats from ChatGPT, Claude, Gemini, and Grok</li><li>Export or delete anytime</li></ul><a href="/register" class="btn btn-primary">Create free account</a></article>
                <article class="free-option free-option-selfhost"><div class="free-option-header"><p class="free-option-label">Self-hosted</p><div class="free-option-price"><span class="free-option-amount">Unlimited</span><span class="free-option-period">full control</span></div></div><p class="free-option-desc">Run the same app on your own server.</p><ul class="feature-list"><li>Unlimited storage on your hardware</li><li>Your server, your backups</li><li>Browser extension works with your instance</li><li>Audit E2EE claims - all source on GitHub</li><li>Fork, modify, or deploy privately</li></ul><a href="{{ $githubUrl }}" class="btn btn-ghost github-btn" target="_blank" rel="noopener noreferrer"><svg class="github-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>View on GitHub</a></article>
            </div>
        </div>
    </section>

    <section id="get-started" class="waitlist-section alt">
        <div class="container"><h2 class="waitlist-title">Your AI memory vault starts here</h2><p class="waitlist-sub">Free, private, and open source. Create an account for 100 MB on memoriq.me, or clone the repo and self-host with no limits.</p><div class="waitlist-form"><a href="/register" class="btn btn-primary">Create free account</a><a href="{{ $githubUrl }}" class="btn btn-ghost github-btn" target="_blank" rel="noopener noreferrer">View on GitHub</a></div><p class="waitlist-note">End-to-end encrypted. Export and delete your archive anytime.</p>
            <div class="extension-cta extension-cta--compact waitlist-extension-cta">
                <div class="extension-cta-icon" aria-hidden="true">🧩</div>
                <div class="extension-cta-copy"><h3 class="extension-cta-title">Save chats in one click</h3><p class="extension-cta-desc">Free browser extension for Chrome and Firefox — ChatGPT, Claude, Gemini, and Grok.</p></div>
                <div class="extension-cta-actions">
                    <a href="{{ $chromeUrl }}" class="extension-cta-btn extension-cta-btn-primary" target="_blank" rel="noopener noreferrer">
                        <svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0C8.21 0 4.831 1.757 2.632 4.501l3.953 6.848A5.454 5.454 0 0 1 12 6.545h10.691A12 12 0 0 0 12 0zM1.931 5.47A11.943 11.943 0 0 0 0 12c0 6.012 4.42 10.991 10.189 11.864l3.953-6.847a5.45 5.45 0 0 1-6.865-2.29zm13.342 2.166a5.446 5.446 0 0 1 1.45 7.09l.002.001h-.002l-5.344 9.257c.206.01.413.016.621.016 6.627 0 12-5.373 12-12 0-1.54-.29-3.011-.818-4.364zM12 16.364a4.364 4.364 0 1 1 0-8.728 4.364 4.364 0 0 1 0 8.728Z"/></svg>
                        Chrome
                    </a>
                    <a href="{{ $firefoxUrl }}" class="extension-cta-btn extension-cta-btn-ghost" target="_blank" rel="noopener noreferrer">
                        <svg class="extension-cta-browser-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20.452 3.445a11.002 11.002 0 00-2.482-1.908C16.944.997 15.098.093 12.477.032c-.734-.017-1.457.03-2.174.144-.72.114-1.398.292-2.118.56-1.017.377-1.996.975-2.574 1.554.583-.349 1.476-.733 2.55-.992a10.083 10.083 0 013.729-.167c2.341.34 4.178 1.381 5.48 2.625a8.066 8.066 0 011.298 1.587c1.468 2.382 1.33 5.376.184 7.142-.85 1.312-2.67 2.544-4.37 2.53-.583-.023-1.438-.152-2.25-.566-2.629-1.343-3.021-4.688-1.118-6.306-.632-.136-1.82.13-2.646 1.363-.742 1.107-.7 2.816-.242 4.028a6.473 6.473 0 01-.59-1.895 7.695 7.695 0 01.416-3.845A8.212 8.212 0 019.45 5.399c.896-1.069 1.908-1.72 2.75-2.005-.54-.471-1.411-.738-2.421-.767C8.31 2.583 6.327 3.061 4.7 4.41a8.148 8.148 0 00-1.976 2.414c-.455.836-.691 1.659-.697 1.678.122-1.445.704-2.994 1.248-4.055-.79.413-1.827 1.668-2.41 3.042C.095 9.37-.2 11.608.14 13.989c.966 5.668 5.9 9.982 11.843 9.982C18.62 23.971 24 18.591 24 11.956a11.93 11.93 0 00-3.548-8.511z"/></svg>
                        Firefox
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if (config('services.newsletter.enabled'))
        <section id="newsletter" class="newsletter-section" data-newsletter-url="{{ config('services.newsletter.url') }}"><div class="container"><div class="section-header"><h2 class="section-title">Product updates</h2><p class="section-lead">Occasional updates from Memoriq. Unsubscribe anytime.</p></div><form id="newsletter-form" class="newsletter-form"><input id="newsletter-email" type="email" class="newsletter-input" placeholder="you@example.com" autocomplete="email" required><button type="submit" class="btn btn-primary">Subscribe</button></form><p id="newsletter-feedback" class="newsletter-feedback" aria-live="polite"></p></div></section>
    @endif

    <footer class="site-footer">
        <div class="container"><div class="footer-grid"><div class="footer-brand"><a href="/" class="footer-brand-link"><svg class="memoriq-logo" width="32" height="32" aria-hidden="true"><use href="#memoriq-logo" /></svg><span>Memoriq</span></a><div class="footer-social" aria-label="Social links"><a href="{{ $xUrl }}" class="footer-social-link footer-social-link-x" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on X"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a><a href="{{ $githubUrl }}" class="footer-social-link footer-social-link-github" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on GitHub"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg></a></div></div><nav class="footer-links" aria-label="Footer"><a href="/terms">Terms</a><a href="/privacy">Privacy Policy</a><a href="/register">Create Account</a><a href="/login">Log in</a><a href="mailto:hello@memoriq.me">Contact</a></nav></div><div class="footer-bottom"><p class="footer-copy">© 2026 Memoriq - Your private AI memory</p></div></div>
    </footer>
</div>
@vite('resources/js/landing.js')
</body>
</html>
