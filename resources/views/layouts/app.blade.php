<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="flex h-full flex-col bg-telu-bg-soft font-sans text-telu-body antialiased"
        x-data="{ sidebarOpen: false }"
        @resize.window="if (window.innerWidth >= 768) sidebarOpen = false"
    >
        @php
            $navItems = [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'active' => 'dashboard',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>'
                ],
                [
                    'label' => 'Topik Dominan',
                    'route' => 'topics.index',
                    'active' => 'topics.*',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>'
                ],
                [
                    'label' => 'Peta Keahlian',
                    'route' => 'expertise.index',
                    'active' => 'expertise.*',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>'
                ],
                [
                    'label' => 'Profil Dosen',
                    'route' => 'lecturers.index',
                    'active' => 'lecturers.*',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>'
                ],
                [
                    'label' => 'Kolaborasi',
                    'route' => 'collaborations.index',
                    'active' => 'collaborations.*',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11.5c0-.285-.01-.57-.028-.852m0 0A3 3 0 103 8c0 .548.146 1.062.403 1.503m0 0A13.971 13.971 0 028.24 16.5m-3.44-2.04L3.752 12.3c-.63-.59-1.371-1.02-2.192-1.258m16.29 9.07a13.917 13.917 0 002.753-9.571m-1.11-2.75A13.905 13.905 0 0015 11.5c0 .285.01.57.028.852m0 0A3 3 0 1021 8c0-.548-.146-1.062-.403-1.503m0 0A13.97 13.97 0 0215.74 16.5m3.44-2.04l1.049-2.16c.63-.59 1.372-1.02 2.191-1.258M12 5.5V12m-3.5-3.5H12M12 12H8.5" /></svg>'
                ],
                [
                    'label' => 'Rekomendasi',
                    'route' => 'recommendations.index',
                    'active' => 'recommendations.*',
                    'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>'
                ],
            ];
        @endphp

        {{-- Top Accent Bar (Mobile-Sticky, Desktop-Fixed/Static) --}}
        <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-telu-border/40 bg-white px-4 shadow-sm md:hidden sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 md:hidden">
                <span class="h-6 w-1.5 rounded-full bg-telu-red" aria-hidden="true"></span>
                <span class="text-sm font-bold tracking-tight text-telu-ink">FIF Research Hub</span>
            </div>
            
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="sidebarOpen = true"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-telu-border/50 text-telu-ink transition-colors hover:bg-telu-bg-soft md:hidden"
                    aria-label="Buka menu"
                >
                    <span class="sr-only">Buka menu</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            {{-- Backdrop mobile --}}
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-telu-navy/40 backdrop-blur-sm transition-opacity duration-200 md:hidden"
            ></div>

            {{-- Sidebar --}}
            <aside
                :class="{ '!translate-x-0': sidebarOpen }"
                class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-telu-border/50 bg-white transition-transform duration-300 ease-in-out md:sticky md:top-0 md:h-full md:translate-x-0"
            >
                {{-- Sidebar Header --}}
                <div class="flex h-16 items-center justify-between px-6 border-b border-telu-border/20">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-telu-red text-white shadow-sm">
                            <span class="font-bold text-sm">FIF</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold leading-tight text-telu-ink">Research Hub</span>
                            <span class="text-[10px] font-semibold text-telu-muted/70 tracking-wider uppercase">Telkom University</span>
                        </div>
                    </a>

                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-telu-muted transition-colors hover:bg-telu-bg-soft md:hidden"
                        aria-label="Tutup menu"
                    >
                        <span class="sr-only">Tutup menu</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- User Profile (Admin yang login) --}}
                @auth
                    <div class="sidebar-stagger-item flex items-center gap-3 px-6 py-4 border-b border-telu-border/20" style="--stagger-delay: 0.03s">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-telu-red text-sm font-bold text-white">
                            {{ Str::of(auth()->user()->name)->explode(' ')->map(fn ($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}
                        </div>
                        <div class="flex flex-col truncate">
                            <span class="truncate text-sm font-semibold leading-tight text-telu-ink">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs text-telu-muted">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                @endauth

                {{-- Navigation Items --}}
                <nav class="flex-1 space-y-1.5 px-4 py-6 overflow-y-auto" aria-label="Navigasi utama">
                    @foreach ($navItems as $item)
                        @php
                            $isActive = request()->routeIs($item['active']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            style="--stagger-delay: {{ 0.06 + $loop->index * 0.05 }}s"
                            class="sidebar-stagger-item group flex items-center gap-3.5 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-telu-red/5 text-telu-red shadow-sm' : 'text-telu-body hover:bg-telu-bg-soft/75 hover:text-telu-ink' }}"
                        >
                            <span class="transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-telu-red' : 'text-telu-muted group-hover:text-telu-ink' }}">
                                {!! $item['icon'] !!}
                            </span>
                            <span>{{ $item['label'] }}</span>
                            <svg class="ml-auto h-4 w-4 opacity-0 transition-opacity duration-200 group-hover:opacity-100 {{ $isActive ? 'text-telu-red' : 'text-telu-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </nav>

                {{-- Logout (Admin yang login) --}}
                @auth
                    <div class="px-4 pb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="group flex w-full items-center gap-3.5 rounded-xl px-4 py-3 text-sm font-medium text-telu-red transition-colors duration-200 hover:bg-telu-red/10"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                @endauth
            </aside>

            {{-- Kolom konten --}}
            <div class="flex flex-1 flex-col overflow-y-auto">
                <main class="flex-1">
                    <div class="mx-auto max-w-[1800px] px-4 py-8 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
                        @yield('content')
                    </div>
                </main>

                <footer class="border-t border-telu-border/20 bg-white">
                    <div class="mx-auto flex max-w-[1800px] items-center justify-between px-4 py-6 text-xs text-telu-muted sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
                        <span>&copy; {{ date('Y') }} KP Kelompok 1 &mdash; Fakultas Informatika, Telkom University</span>
                        <span>Satgas AI FIF</span>
                    </div>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
