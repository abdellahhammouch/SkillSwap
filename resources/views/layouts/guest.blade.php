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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="ss-page flex min-h-screen flex-col">
            <div class="ss-container flex h-16 items-center justify-between">
                <a href="/" class="inline-flex items-center">
                    <x-application-logo />
                </a>

                @if (Route::has('login'))
                    <div class="text-sm text-slate-400">
                        @if (request()->routeIs('register'))
                            Already have an account?
                            <a href="{{ route('login') }}" class="ss-link ms-2">Log In</a>
                        @elseif (Route::has('register'))
                            Don't have an account?
                            <a href="{{ route('register') }}" class="ss-link ms-2">Create account</a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex flex-1 items-center justify-center px-4 py-12">
                <div class="w-full sm:max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
