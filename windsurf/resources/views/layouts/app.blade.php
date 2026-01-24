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
        <!-- Loading Overlay for Specific Routes -->
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

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-16 z-30 transition-all duration-300">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
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
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-4 relative" role="alert">
                        <p>{{ session('error') }}</p>
                        <button type="button" class="absolute top-0 right-0 mt-3 mr-3 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- jQuery (necessário para o Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>

        <script>
            // Mostrar overlay de carregamento para consultas específicas
            document.addEventListener('DOMContentLoaded', function() {
                // Encontrar o link da consulta por localização
                const consultaLocalizacaoLinks = document.querySelectorAll('a[href*="consultas.produtos-ativos-por-localizacao"]');

                consultaLocalizacaoLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        // Mostrar o overlay de carregamento
                        document.getElementById('global-loading-overlay').style.display = 'flex';
                    });
                });
            });
        </script>

        <!-- Scripts personalizados -->
        @stack('scripts')
    </body>
</html>
