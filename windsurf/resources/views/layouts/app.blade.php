<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts para Modo Escuro -->
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles personalizados -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

        <!-- Loading Overlay -->
        <div id="global-loading-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-[100]" style="display: none;">
            <div class="glass dark:glass-dark p-8 rounded-2xl shadow-2xl max-w-sm w-full text-center">
                <div class="flex flex-col items-center">
                    <div class="relative w-16 h-16 mb-4">
                        <div class="absolute inset-0 border-4 border-primary-500/20 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Processando Dados</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Esta ação pode levar alguns instantes. Por favor, mantenha esta janela aberta.</p>
                </div>
            </div>
        </div>

        <div class="flex h-screen overflow-hidden">

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1 overflow-hidden">

                <!-- Topbar -->
                <header x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }"
                        x-init="$watch('darkMode', val => {
                            localStorage.setItem('dark-mode', val);
                            if (val) document.documentElement.classList.add('dark');
                            else document.documentElement.classList.remove('dark');
                        })"
                        class="flex-shrink-0 flex items-center justify-between h-16 px-4 sm:px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 z-30">

                    <!-- Page Title / Header -->
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="hidden sm:inline text-sm font-semibold tracking-wide text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            Grupo Rota do Mar
                        </span>
                        <span class="hidden sm:inline text-slate-300 dark:text-slate-700">-</span>
                        @isset($header)
                            <div class="min-w-0 text-slate-900 dark:text-white truncate">{{ $header }}</div>
                        @else
                            <span class="text-lg font-semibold text-slate-700 dark:text-slate-200 truncate">
                                Dashboard
                            </span>
                        @endisset
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-2 flex-shrink-0">

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                                class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 focus:outline-none ring-1 ring-black/5 dark:ring-white/5">
                            <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-cloak x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <!-- Sugestões -->
                        <div x-data="{
                            count: 0,
                            async loadCount() {
                                try {
                                    const response = await fetch('{{ route('api.sugestoes.nao-lidas-count') }}', {
                                        headers: { 'Accept': 'application/json' }
                                    });
                                    if (!response.ok) { this.count = 0; return; }
                                    const contentType = response.headers.get('content-type') || '';
                                    if (!contentType.includes('application/json')) { this.count = 0; return; }
                                    const data = await response.json();
                                    this.count = Number(data?.count ?? 0);
                                } catch (error) {
                                    this.count = 0;
                                }
                            },
                            init() { this.loadCount(); setInterval(() => this.loadCount(), 30000); }
                        }">
                            <a href="{{ route('sugestoes.index') }}"
                               class="relative p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 focus:outline-none ring-1 ring-black/5 dark:ring-white/5"
                               title="Sugestões">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.945a2 2 0 002.22 0L21 8m-2 10H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z" />
                                </svg>
                                <span x-show="count > 0" x-text="count"
                                      class="absolute top-0 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                            </a>
                        </div>

                        <!-- Notificações -->
                        <div class="relative" x-data="{
                            open: false,
                            notificacoes: [],
                            count: 0,
                            loading: false,
                            async loadNotificacoes() {
                                if (this.loading) return;
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('api.notificacoes.nao-visualizadas') }}', {
                                        headers: { 'Accept': 'application/json' }
                                    });
                                    if (!response.ok) { this.notificacoes = []; this.count = 0; return; }
                                    const contentType = response.headers.get('content-type') || '';
                                    if (!contentType.includes('application/json')) { this.notificacoes = []; this.count = 0; return; }
                                    const data = await response.json();
                                    this.notificacoes = Array.isArray(data?.notificacoes) ? data.notificacoes : [];
                                    this.count = Number(data?.count ?? this.notificacoes.length);
                                } catch (error) {
                                    this.notificacoes = []; this.count = 0;
                                } finally {
                                    this.loading = false;
                                }
                            },
                            init() { this.loadNotificacoes(); setInterval(() => this.loadNotificacoes(), 30000); }
                        }">
                            <button @click="open = !open; if(open) loadNotificacoes()" @click.away="open = false"
                                    class="relative p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 focus:outline-none ring-1 ring-black/5 dark:ring-white/5">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="count > 0" x-text="count"
                                      class="absolute top-0 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-80 rounded-2xl shadow-2xl origin-top-right right-0 bg-white dark:bg-slate-800 ring-1 ring-black/5 dark:ring-white/10" style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Notificações</h3>
                                            <a href="{{ route('notificacoes.index') }}" class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase hover:underline">Ver todas</a>
                                        </div>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <template x-if="loading">
                                            <div class="px-4 py-6 text-center">
                                                <svg class="animate-spin h-5 w-5 mx-auto text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </template>
                                        <template x-if="!loading && (!Array.isArray(notificacoes) || notificacoes.length === 0)">
                                            <div class="px-4 py-8 text-center text-slate-500 text-xs italic">
                                                Nenhuma notificação nova
                                            </div>
                                        </template>
                                        <template x-for="notificacao in notificacoes" :key="notificacao.id">
                                            <a :href="notificacao.link" :title="notificacao.mensagem" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 border-b border-slate-50 dark:border-slate-700 last:border-0 transition-colors">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-1.5 h-1.5 bg-primary-500 rounded-full mt-1.5 shadow-sm shadow-primary-500/50"></div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-[13px] font-bold text-slate-900 dark:text-white leading-tight" x-text="notificacao.titulo"></p>
                                                        <p class="text-[12px] text-slate-600 dark:text-slate-400 truncate mt-0.5" x-text="notificacao.mensagem"></p>
                                                        <div class="flex items-center space-x-2 mt-1.5">
                                                            <p class="text-[10px] text-slate-400 font-medium" x-text="notificacao.created_at"></p>
                                                            <span class="text-slate-300 dark:text-slate-600">•</span>
                                                            <p class="text-[10px] text-primary-600 dark:text-primary-400 font-bold uppercase" x-text="notificacao.localizacao"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Flash Messages -->
                <div class="px-4 sm:px-6 pt-4">
                    @if(session('success'))
                        <div class="glass border-l-4 border-green-500 bg-green-50/50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-xl mb-4 relative overflow-hidden group transition-all" role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                            <button type="button" class="absolute top-4 right-4 text-green-700 dark:text-green-400 hover:scale-110 transition-transform" onclick="this.closest('[role=alert]').remove()">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-4 relative rounded-xl" role="alert">
                            <p>{{ session('error') }}</p>
                            <button type="button" class="absolute top-4 right-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @endif

                    @if(session('alert_error'))
                        <div class="bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 dark:border-amber-400 text-amber-700 dark:text-amber-300 p-4 mb-4 relative rounded-xl" role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                <p class="font-medium">{{ session('alert_error') }}</p>
                            </div>
                            <button type="button" class="absolute top-4 right-4 text-amber-700 dark:text-amber-400 hover:scale-110 transition-transform" onclick="this.closest('[role=alert]').remove()">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>

            </div>
        </div>

        <!-- jQuery (necessário para o Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const consultaLocalizacaoLinks = document.querySelectorAll('a[href*="consultas.produtos-ativos-por-localizacao"]');
                consultaLocalizacaoLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        document.getElementById('global-loading-overlay').style.display = 'flex';
                    });
                });
            });
        </script>

        <!-- Scripts personalizados -->
        @stack('scripts')
    </body>
</html>
