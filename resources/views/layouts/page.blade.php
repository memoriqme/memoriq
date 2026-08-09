<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $pageTitle = trim($__env->yieldContent('title'));
        $pageUrl = rtrim(config('app.url'), '/').'/'.request()->path();
        $shareImage = rtrim(config('app.url'), '/').'/img/thumb.png';
        $githubUrl = 'https://github.com/memoriqme';
        $xUrl = 'https://x.com/memoriqme';
    @endphp
    <title>{{ $pageTitle }}</title>
    <link rel="canonical" href="{{ $pageUrl }}">
    <link rel="icon" href="/icons/memoriq.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/icons/memoriq-180.png">
    <link rel="manifest" href="/manifest.webmanifest?v=3">
    <meta name="theme-color" content="#148b74">
    <meta property="og:site_name" content="Memoriq">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <script>document.documentElement.dataset.theme = localStorage.getItem('memoriq-theme') === 'light' ? 'light' : 'dark';</script>
    @vite('resources/css/page.css')
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

    <nav class="nav" aria-label="Main navigation">
        <div class="container nav-inner">
            <a href="/" class="logo">
                <svg class="memoriq-logo" width="32" height="32" aria-hidden="true"><use href="#memoriq-logo" /></svg>
                <span>Memoriq</span>
            </a>
            <div class="nav-actions">
                <button type="button" class="theme-btn" id="theme-toggle" aria-label="Switch to light mode">
                    <svg class="theme-icon-sun" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    <svg class="theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
                <a href="/register" class="btn btn-primary nav-cta">Create free account</a>
                <a href="/login" class="nav-login">Log in</a>
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="page-shell">
            <article class="page-card">
                <h1>@yield('heading')</h1>
                <p class="page-updated">@yield('updated')</p>
                @yield('content')
            </article>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="footer-brand-link">
                        <svg class="memoriq-logo" width="32" height="32" aria-hidden="true"><use href="#memoriq-logo" /></svg>
                        <span>Memoriq</span>
                    </a>
                    <div class="footer-social" aria-label="Social links">
                        <a href="{{ $xUrl }}" class="footer-social-link footer-social-link-x" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on X">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="{{ $githubUrl }}" class="footer-social-link footer-social-link-github" target="_blank" rel="noopener noreferrer" aria-label="Memoriq on GitHub">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                        </a>
                    </div>
                </div>
                <nav class="footer-links" aria-label="Footer">
                    <a href="/terms">Terms</a>
                    <a href="/privacy">Privacy Policy</a>
                    <a href="/register">Create Account</a>
                    <a href="/login">Log in</a>
                    <a href="mailto:hello@memoriq.me">Contact</a>
                </nav>
            </div>
            <div class="footer-bottom"><p class="footer-copy">© {{ date('Y') }} Memoriq - Your private AI memory</p></div>
        </div>
    </footer>
    @vite('resources/js/page.js')
</body>
</html>
