<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
                        {{ __('Produtos') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tecidos.index')" :active="request()->routeIs('tecidos.*')">
                        {{ __('Tecidos') }}
                    </x-nav-link>

                    <x-nav-link :href="route('movimentacoes.index')" :active="request()->routeIs('movimentacoes.*')">
                        {{ __('Movimentações') }}
                    </x-nav-link>

                    <x-nav-link :href="route('kanban.index')" :active="request()->routeIs('kanban.*')">
                        {{ __('Kanban') }}
                    </x-nav-link>

                    <!-- Cadastros Dropdown -->
                    <div class="hidden sm:flex sm:items-center" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" :class="{'text-indigo-600': {{ request()->routeIs('produtos.*') || request()->routeIs('tecidos.*') || request()->routeIs('estilistas.*') || request()->routeIs('marcas.*') || request()->routeIs('grupo_produtos.*') ? 'true' : 'false' }} }">
                                <div>{{ __('Cadastros') }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0" style="display: none;">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                    <a href="{{ route('estilistas.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('estilistas.*') ? 'bg-gray-100' : '' }}">{{ __('Estilistas') }}</a>
                                    <a href="{{ route('marcas.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('marcas.*') ? 'bg-gray-100' : '' }}">{{ __('Marcas') }}</a>
                                    <a href="{{ route('grupo_produtos.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('grupo_produtos.*') ? 'bg-gray-100' : '' }}">{{ __('Grupos de Produtos') }}</a>
                                    <a href="{{ route('tipos.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('tipos.*') ? 'bg-gray-100' : '' }}">{{ __('Tipos') }}</a>
                                    <a href="{{ route('status.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('status.*') ? 'bg-gray-100' : '' }}">{{ __('Status') }}</a>
                                    <a href="{{ route('situacoes.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('situacoes.*') ? 'bg-gray-100' : '' }}">{{ __('Situações') }}</a>
                                    <a href="{{ route('localizacoes.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('localizacoes.*') && !request()->routeIs('localizacao-capacidade.*') ? 'bg-gray-100' : '' }}">{{ __('Localizações') }}</a>
                                    <a href="{{ route('direcionamentos-comerciais.index') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('direcionamentos-comerciais.*') ? 'bg-gray-100' : '' }}">{{ __('Direcionamentos Comerciais') }}</a>
                                    <a href="{{ route('localizacao-capacidade.dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('localizacao-capacidade.*') ? 'bg-gray-100' : '' }}">{{ __('Capacidade Mensal') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consultas Dropdown -->
                    <div class="hidden sm:flex sm:items-center" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" @click.away="open = false" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" :class="{'text-indigo-600': {{ request()->routeIs('consultas.*') ? 'true' : 'false' }} }">
                                <div>{{ __('Consultas') }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0" style="display: none;">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                    <a href="{{ route('consultas.produtos-ativos-por-localizacao') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('consultas.produtos-ativos-por-localizacao') ? 'bg-gray-100' : '' }}">{{ __('Produtos por Localização') }}</a>
                                    <a href="{{ route('consultas.media-dias-atraso') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('consultas.media-dias-atraso') ? 'bg-gray-100' : '' }}">{{ __('Média de Dias por Localização') }}</a>
                                    <a href="{{ route('dashboard.produtos-por-estilista') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('dashboard.produtos-por-estilista') ? 'bg-gray-100' : '' }}">{{ __('Gráfico por Estilista') }}</a>
                                    <a href="{{ route('consultas.pivot-estilistas-status') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('consultas.pivot-estilistas-status') ? 'bg-gray-100' : '' }}">{{ __('Tabela Estilistas x Status') }}</a>

                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Outros links serão adicionados conforme implementados -->
                </div>
            </div>

            <!-- Notifications Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6" x-data="{
                open: false,
                notificacoes: [],
                count: 0,
                loading: false,
                async loadNotificacoes() {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route("api.notificacoes.nao-visualizadas") }}', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.notificacoes = data.notificacoes;
                        this.count = data.count;
                    } catch (error) {
                        console.error('Erro ao carregar notificações:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                init() {
                    this.loadNotificacoes();
                    // Recarregar notificações a cada 30 segundos
                    setInterval(() => this.loadNotificacoes(), 30000);
                }
            }">
                <div class="relative">
                    <button @click="open = !open; if(open) loadNotificacoes()" @click.away="open = false"
                            class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12h5v12z" />
                        </svg>
                        <span x-show="count > 0" x-text="count"
                              class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem] h-5"></span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute z-50 mt-2 w-80 rounded-md shadow-lg origin-top-right right-0 bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900">Notificações</h3>
                                    <a href="{{ route('notificacoes.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Ver todas</a>
                                </div>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="loading">
                                    <div class="px-4 py-3 text-center text-gray-500">
                                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </template>

                                <template x-if="!loading && notificacoes.length === 0">
                                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                        Nenhuma notificação nova
                                    </div>
                                </template>

                                <template x-for="notificacao in notificacoes" :key="notificacao.id">
                                    <a :href="notificacao.link" :title="notificacao.mensagem" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900" x-text="notificacao.titulo"></p>
                                                <p class="text-sm text-gray-600 truncate" x-text="notificacao.mensagem"></p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <p class="text-xs text-gray-400" x-text="notificacao.created_at"></p>
                                                    <span class="text-xs text-gray-400">•</span>
                                                    <p class="text-xs text-blue-600 font-medium" x-text="notificacao.localizacao"></p>
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

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(auth()->user()->isAdmin())
                        <x-dropdown-link :href="route('users.index')">
                            {{ __('Usuários') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('permissions.index')">
                            {{ __('Permissões') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('logs.index')">
                            {{ __('Logs') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('activity-log.index')">
                            {{ __('Log de Atividades') }}
                        </x-dropdown-link>
                        @endif
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
                {{ __('Produtos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('movimentacoes.index')" :active="request()->routeIs('movimentacoes.*')">
                {{ __('Movimentações') }}
            </x-responsive-nav-link>

            @if(auth()->user()->localizacao_id)
            <x-responsive-nav-link :href="route('movimentacoes.minhas')" :active="request()->routeIs('movimentacoes.minhas')">
                {{ __('Minhas Movimentações') }}
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('kanban.index')" :active="request()->routeIs('kanban.*')">
                {{ __('Kanban') }}
            </x-responsive-nav-link>

            <!-- Responsive Cadastros -->
            <div class="pt-2 pb-3 space-y-1">
                <div class="pl-3 pr-4 py-2 font-medium text-base text-gray-600">{{ __('Cadastros') }}</div>
                <x-responsive-nav-link :href="route('tecidos.index')" :active="request()->routeIs('tecidos.*')" class="pl-6">
                    {{ __('Tecidos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('estilistas.index')" :active="request()->routeIs('estilistas.*')" class="pl-6">
                    {{ __('Estilistas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('marcas.index')" :active="request()->routeIs('marcas.*')" class="pl-6">
                    {{ __('Marcas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('grupo_produtos.index')" :active="request()->routeIs('grupo_produtos.*')" class="pl-6">
                    {{ __('Grupos de Produtos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tipos.index')" :active="request()->routeIs('tipos.*')" class="pl-6">
                    {{ __('Tipos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('status.index')" :active="request()->routeIs('status.*')" class="pl-6">
                    {{ __('Status') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('situacoes.index')" :active="request()->routeIs('situacoes.*')" class="pl-6">
                    {{ __('Situações') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('localizacoes.index')" :active="request()->routeIs('localizacoes.*') && !request()->routeIs('localizacao-capacidade.*')" class="pl-6">
                    {{ __('Localizações') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('direcionamentos-comerciais.index')" :active="request()->routeIs('direcionamentos-comerciais.*')" class="pl-6">
                    {{ __('Direcionamentos Comerciais') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('localizacao-capacidade.dashboard')" :active="request()->routeIs('localizacao-capacidade.*')" class="pl-6">
                    {{ __('Capacidade Mensal') }}
                </x-responsive-nav-link>

            </div>

            <!-- Responsive Consultas Menu -->
            <div class="pt-2 pb-3 space-y-1">
                <div class="pl-3 pr-4 py-2 font-medium text-base text-gray-600">{{ __('Consultas') }}</div>
                <x-responsive-nav-link :href="route('consultas.produtos-ativos-por-localizacao')" :active="request()->routeIs('consultas.produtos-ativos-por-localizacao')" class="pl-6">
                    {{ __('Produtos por Localização') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.media-dias-atraso')" :active="request()->routeIs('consultas.media-dias-atraso')" class="pl-6">
                    {{ __('Média de Dias por Localização') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.produtos-por-estilista')" :active="request()->routeIs('dashboard.produtos-por-estilista')" class="pl-6">
                    {{ __('Gráfico por Estilista') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('consultas.pivot-estilistas-status')" :active="request()->routeIs('consultas.pivot-estilistas-status')" class="pl-6">
                    {{ __('Tabela Estilistas x Status') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Admin Menu (visível apenas para administradores) -->
            @if(auth()->user()->isAdmin())
            <div class="pt-2 pb-3 space-y-1">
                <div class="pl-3 pr-4 py-2 font-medium text-base text-gray-600">{{ __('Admin') }}</div>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="pl-6">
                    {{ __('Usuários') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')" class="pl-6">
                    {{ __('Permissões') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.*')" class="pl-6">
                    {{ __('Logs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activity-log.index')" :active="request()->routeIs('activity-log.*')" class="pl-6">
                    {{ __('Log de Atividades') }}
                </x-responsive-nav-link>
            </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
