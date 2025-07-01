<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">
            <div class="p-6 text-xl font-bold">
                {{ config('app.name', 'Laravel') }}
            </div>
            <nav class="mt-6">
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="block py-2 px-6 hover:bg-gray-200">Dashboard</a></li>
                    <!-- <li><a href="{{ route('your.route') }}" class="block py-2 px-6 hover:bg-gray-200">Your Page</a></li> -->
                    <!-- Add more links as needed -->
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            @isset($header)
                <header class="mb-4 border-b pb-4 text-2xl font-semibold">
                    {{ $header }}
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
