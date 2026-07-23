<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Masuk') — {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative flex h-full items-center justify-center overflow-hidden bg-telu-bg-soft font-sans text-telu-body antialiased">
        <div
            class="pointer-events-none fixed inset-0 overflow-hidden"
            aria-hidden="true"
        >
            <div class="absolute -top-32 -left-32 h-96 w-96 animate-float-slow rounded-full bg-telu-red/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 h-96 w-96 animate-float-slower rounded-full bg-telu-navy/10 blur-3xl"></div>
            <div class="absolute left-1/2 top-1/2 h-[32rem] w-[32rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-telu-red/5 blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-md px-4">
            <div class="animate-card-in rounded-[2rem] border border-telu-border/60 bg-white/90 p-10 shadow-2xl shadow-telu-navy/10 backdrop-blur-sm">
                <div class="mb-7 flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('images/logo-fif.png') }}" alt="Logo Fakultas Informatika" class="h-9 w-auto">
                    <div class="flex flex-col">
                        <span class="text-base font-semibold leading-tight text-telu-ink">Research Hub</span>
                        <span class="text-[11px] font-semibold text-telu-muted tracking-wider uppercase">Telkom University</span>
                    </div>
                </div>

                @yield('content')
            </div>
        </div>
    </body>
</html>
