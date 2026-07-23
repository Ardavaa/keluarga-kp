<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Masuk') — {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex h-full items-center justify-center bg-telu-bg-soft font-sans text-telu-body antialiased">
        <div class="w-full max-w-sm px-4">
            <div class="mb-6 flex items-center justify-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-md bg-telu-red text-white">
                    <span class="font-semibold text-sm">FIF</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold leading-tight text-telu-ink">Research Hub</span>
                    <span class="text-[10px] font-semibold text-telu-muted tracking-wider uppercase">Telkom University</span>
                </div>
            </div>

            <div class="card-premium p-6">
                @yield('content')
            </div>
        </div>
    </body>
</html>
