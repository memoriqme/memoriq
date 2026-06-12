<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $shareTitle = 'Memoriq - Your Private AI Memory';
            $shareDescription = 'Archive, search, and protect every ChatGPT, Claude, Gemini, and Grok conversation in one encrypted vault.';
            $shareUrl = rtrim(config('app.url'), '/');
            $shareImage = $shareUrl . '/img/thumb.png';
        @endphp

        <title>{{ $shareTitle }}</title>
        <meta name="description" content="{{ $shareDescription }}">

        <link rel="icon" href="/icons/memoriq.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/icons/memoriq-180.png">
        <link rel="manifest" href="/manifest.webmanifest?v=2">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Memoriq">
        <meta name="application-name" content="Memoriq">
        <meta name="theme-color" content="#148b74">

        <link rel="canonical" href="{{ $shareUrl }}/">

        <!-- Open Graph -->
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
        <meta property="og:image:alt" content="Memoriq - Your Private AI Memory, end-to-end encrypted">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@memoriq">
        <meta name="twitter:title" content="{{ $shareTitle }}">
        <meta name="twitter:description" content="{{ $shareDescription }}">
        <meta name="twitter:image" content="{{ $shareImage }}">
        <meta name="twitter:image:alt" content="Memoriq - Your Private AI Memory, end-to-end encrypted">

        @vite('resources/css/app.css')

        @if (config('services.analytics.enabled'))
        <!-- Matomo -->
        <script>
        var _paq = window._paq = window._paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="{{ config('services.analytics.url') }}";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '{{ config('services.analytics.site_id') }}']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
        </script>
        @endif
    </head>
    <body>
        <div id="app" style="height:100%;"></div>
        
        @vite('resources/js/app.js')
    </body>
</html>
