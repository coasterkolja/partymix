<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @fluxAppearance

    <title>{{ $title ?? 'Spotify PartyMix' }}</title>
</head>

<body class="max-h-screen bg-white dark:bg-zinc-800">
    <div class="w-full h-screen flex items-center justify-center">
        {{ $slot }}
    </div>

    @fluxScripts
    @livewireScriptConfig
</body>

</html>
