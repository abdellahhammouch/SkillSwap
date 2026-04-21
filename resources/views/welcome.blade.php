<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SkillSwap</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="ss-page">
            <div class="ss-container py-6">
                <div class="flex items-center justify-between">
                    <x-application-logo />

                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-300">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-blue-400">Sign up</a>
                    </div>
                </div>

                <div class="grid min-h-[70vh] place-items-center">
                    <div class="max-w-2xl text-center">
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-400">Learn by exchange</p>
                        <h1 class="mt-4 text-5xl font-black text-white">
                            Help so that you will be helped.
                        </h1>
                        <p class="mt-5 text-lg text-slate-400">
                            SkillSwap helps students find each other, send exchange requests,
                            chat after acceptance, and plan learning sessions.
                        </p>

                        <div class="mt-8 flex justify-center gap-4">
                            <a href="{{ route('register') }}" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white">
                                Create account
                            </a>

                            <a href="{{ route('login') }}" class="rounded-xl border border-slate-700 px-6 py-3 text-sm font-bold text-slate-200">
                                Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
