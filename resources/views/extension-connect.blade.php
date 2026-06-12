<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Memoriq Extension Connected</title>
    <style>
        body {
            align-items: center;
            background: #0a0a0a;
            color: #f5f5f5;
            display: flex;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: #1a1a1a;
            border: 1px solid #2f2f2f;
            border-radius: 18px;
            max-width: 420px;
            padding: 32px;
            text-align: center;
        }
        .mark {
            display: block;
            height: 44px;
            margin: 0 auto 18px;
            width: 44px;
        }
        p { color: #b4b4b4; }
    </style>
</head>
<body>
    <main class="card">
        <img src="/icons/memoriq.svg" alt="" class="mark" width="44" height="44" aria-hidden="true">
        <h1>Memoriq is connected</h1>
        <p id="connectStatus">Saving connection to the extension...</p>
    </main>
    <script id="memoriq-extension-payload" type="application/json">
        @json($payload)
    </script>
</body>
</html>
