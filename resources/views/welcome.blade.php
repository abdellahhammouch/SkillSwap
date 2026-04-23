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
        <div class="min-h-screen bg-slate-950 text-white">
            <div class="mx-auto max-w-6xl px-6 py-8">
                <div class="flex justify-center">
                    <x-application-logo />
                </div>

                <div class="grid min-h-[430px] items-center gap-12 py-16 md:grid-cols-2">
                    <div>
                        <h1 class="max-w-md text-4xl font-black leading-tight text-slate-200 md:text-5xl">
                            Exchange <span class="text-teal-300">time</span>, not money.
                        </h1>

                        <p class="mt-5 max-w-md text-sm leading-7 text-slate-400">
                            Join a global network where knowledge is the currency. Teach a skill you've mastered and learn something new from experts around the world. No fees, just growth.
                        </p>

                        <div class="mt-8 grid max-w-md grid-cols-2 gap-5">
                            <a href="{{ route('login') }}" class="rounded-lg bg-blue-600 py-4 text-center text-sm font-semibold text-white shadow-lg shadow-blue-950/50">
                                Login
                            </a>

                            <a href="{{ route('register') }}" class="rounded-lg bg-slate-800 py-4 text-center text-sm font-semibold text-slate-200">
                                Sign Up
                            </a>
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-700 bg-slate-900 p-4">
                        <div class="overflow-hidden rounded-md">
                            <img src="{{ asset('images/learning-collaboration.png') }}" alt="Learning collaboration" class="h-56 w-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/70 py-14">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="text-center">
                        <h2 class="text-sm font-semibold text-slate-200">3 Steps to Mastery</h2>
                        <p class="mt-2 text-sm text-slate-400">Our unique ecosystem ensures a fair and high-value exchange for every participant.</p>
                    </div>

                    <div class="mt-8 grid gap-8 md:grid-cols-3">
                        <div class="rounded-lg border border-slate-700 bg-slate-800/70 p-6">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-purple-500/20 text-purple-300">
                                <i class="fa-solid fa-table-cells-large"></i>
                            </div>
                            <h3 class="mt-6 text-sm font-semibold">Post Your Offer</h3>
                            <p class="mt-4 text-sm leading-6 text-slate-400">List the skills you can teach and what you want to learn. Our AI matches your needs with others' expertise.</p>
                        </div>

                        <div class="rounded-lg border border-teal-900 bg-slate-800/70 p-6">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-teal-500/20 text-teal-300">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            <h3 class="mt-6 text-sm font-semibold">Confirm Exchange</h3>
                            <p class="mt-4 text-sm leading-6 text-slate-400">Chat with prospective partners, review their portfolios, and agree on a timeline for your skill swap.</p>
                        </div>

                        <div class="rounded-lg border border-slate-700 bg-slate-800/70 p-6">
                            <div class="flex h-10 w-10 items-center justify-center rounded bg-indigo-500/20 text-indigo-300">
                                <i class="fa-solid fa-rocket"></i>
                            </div>
                            <h3 class="mt-6 text-sm font-semibold">Learn & Grow</h3>
                            <p class="mt-4 text-sm leading-6 text-slate-400">Hop on a live session, share resources, and complete projects. Both parties gain new skills and verified reviews.</p>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="bg-slate-950 py-8">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 text-xs text-slate-500">
                    <div>
                        <p class="text-base font-bold text-white">SkillSwap</p>
                        <p class="mt-2">© 2024 SkillSwap. Empowering Academic Excellence.</p>
                    </div>

                    <div class="hidden gap-8 md:flex">
                        <span>Terms of Service</span>
                        <span>Privacy Policy</span>
                        <span>Code of Conduct</span>
                        <span>Support</span>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
