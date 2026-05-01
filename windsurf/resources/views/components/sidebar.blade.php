<aside x-data
       :class="$store.sidebar.open ? 'w-64' : 'w-[4.5rem]'"
       class="flex-shrink-0 flex flex-col bg-gray-900 transition-all duration-300 ease-in-out overflow-hidden h-screen sticky top-0">

    <!-- Logo + Toggle -->
    <div class="flex items-center h-16 px-3 border-b border-gray-700/60 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <span class="flex-shrink-0">
                <x-application-logo class="h-8 w-8 fill-current text-white" />
            </span>
            <span x-cloak x-show="$store.sidebar.open"
                  class="text-white font-bold text-sm whitespace-nowrap overflow-hidden leading-tight">
                Grupo Rota do Mar
            </span>
        </a>
        <button @click="$store.sidebar.toggle()"
                class="ml-auto flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
            <svg x-show="$store.sidebar.open" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-cloak x-show="!$store.sidebar.open" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-3 px-2 space-y-0.5">

        <!-- Dashboard -->
        <x-sidebar-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot>
            Dashboard
        </x-sidebar-item>

        @if(auth()->user()->hasPermission('produtos'))
        <x-sidebar-item :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </x-slot>
            Produtos
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('criacao'))
        <x-sidebar-item :href="route('criacao.index')" :active="request()->routeIs('criacao.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </x-slot>
            Criação
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('movimentacoes'))
        <x-sidebar-item :href="route('movimentacoes.index')" :active="request()->routeIs('movimentacoes.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </x-slot>
            Movimentações
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('kanban'))
        <x-sidebar-item :href="route('kanban.index')" :active="request()->routeIs('kanban.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </x-slot>
            Kanban
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('planejamento'))
        <x-sidebar-item :href="route('localizacao-capacidade.dashboard')" :active="request()->routeIs('localizacao-capacidade.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </x-slot>
            Planejamento
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('sugestoes'))
        <x-sidebar-item :href="route('sugestoes.index')" :active="request()->routeIs('sugestoes.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m1.636 6.364l.707-.707M12 21v-1m-6.364-1.636l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </x-slot>
            Sugestões
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('logistica'))
        <x-sidebar-item :href="route('logistica-coleta.index')" :active="request()->routeIs('logistica-coleta.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l1 1h10l1-1zM13 6h3l3 4v6h-6V6z" />
                </svg>
            </x-slot>
            Logística
        </x-sidebar-item>
        @endif

        @if(auth()->user()->hasPermission('cadastros'))
        <div class="pt-1">
            <x-sidebar-group
                label="Cadastros"
                storeKey="cadastros"
                :active="request()->routeIs('tecidos.*') || request()->routeIs('estilistas.*') || request()->routeIs('marcas.*') || request()->routeIs('grupo_produtos.*') || request()->routeIs('tipos.*') || request()->routeIs('status.*') || request()->routeIs('situacoes.*') || request()->routeIs('localizacoes.*') || request()->routeIs('direcionamentos-comerciais.*') || request()->routeIs('etapas-producao.*') || request()->routeIs('veiculos.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </x-slot>
                <x-sidebar-item :href="route('tecidos.index')" :active="request()->routeIs('tecidos.*')">Tecidos</x-sidebar-item>
                <x-sidebar-item :href="route('estilistas.index')" :active="request()->routeIs('estilistas.*')">Estilistas</x-sidebar-item>
                <x-sidebar-item :href="route('marcas.index')" :active="request()->routeIs('marcas.*')">Marcas</x-sidebar-item>
                <x-sidebar-item :href="route('grupo_produtos.index')" :active="request()->routeIs('grupo_produtos.*')">Grupos de Produtos</x-sidebar-item>
                <x-sidebar-item :href="route('tipos.index')" :active="request()->routeIs('tipos.*')">Tipos</x-sidebar-item>
                <x-sidebar-item :href="route('status.index')" :active="request()->routeIs('status.*')">Status</x-sidebar-item>
                <x-sidebar-item :href="route('situacoes.index')" :active="request()->routeIs('situacoes.*')">Situações</x-sidebar-item>
                <x-sidebar-item :href="route('localizacoes.index')" :active="request()->routeIs('localizacoes.*') && !request()->routeIs('localizacao-capacidade.*')">Localizações</x-sidebar-item>
                <x-sidebar-item :href="route('direcionamentos-comerciais.index')" :active="request()->routeIs('direcionamentos-comerciais.*')">Direcionamentos Comerciais</x-sidebar-item>
                <x-sidebar-item :href="route('etapas-producao.index')" :active="request()->routeIs('etapas-producao.*')">Etapas de Produção</x-sidebar-item>
                <x-sidebar-item :href="route('veiculos.index')" :active="request()->routeIs('veiculos.*')">Veículos</x-sidebar-item>
            </x-sidebar-group>
        </div>
        @endif

        @if(auth()->user()->hasPermission('consultas'))
        <div class="pt-1">
            <x-sidebar-group
                label="Consultas"
                storeKey="consultas"
                :active="request()->routeIs('consultas.*') || request()->routeIs('dashboard.produtos-por-estilista')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </x-slot>
                <x-sidebar-item :href="route('consultas.produtos-ativos-por-localizacao')" :active="request()->routeIs('consultas.produtos-ativos-por-localizacao')">Produtos por Localização</x-sidebar-item>
                <x-sidebar-item :href="route('consultas.media-dias-atraso')" :active="request()->routeIs('consultas.media-dias-atraso')">Média de Dias por Localização</x-sidebar-item>
                <x-sidebar-item :href="route('dashboard.produtos-por-estilista')" :active="request()->routeIs('dashboard.produtos-por-estilista')">Gráfico por Estilista</x-sidebar-item>
                <x-sidebar-item :href="route('consultas.pivot-estilistas-status')" :active="request()->routeIs('consultas.pivot-estilistas-status')">Tabela Estilistas x Status</x-sidebar-item>
            </x-sidebar-group>
        </div>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="pt-1">
            <x-sidebar-group
                label="Administração"
                storeKey="admin"
                :active="request()->routeIs('users.*') || request()->routeIs('permissions.*') || request()->routeIs('logs.*') || request()->routeIs('activity-log.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </x-slot>
                <x-sidebar-item :href="route('users.index')" :active="request()->routeIs('users.*')">Usuários</x-sidebar-item>
                <x-sidebar-item :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">Permissões</x-sidebar-item>
                <x-sidebar-item :href="route('logs.index')" :active="request()->routeIs('logs.*')">Logs</x-sidebar-item>
                <x-sidebar-item :href="route('activity-log.index')" :active="request()->routeIs('activity-log.*')">Log de Atividades</x-sidebar-item>
            </x-sidebar-group>
        </div>
        @endif

    </nav>

    <!-- User Profile (bottom) -->
    <div class="flex-shrink-0 border-t border-gray-700/60 px-2 py-3">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors group relative">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <span x-cloak x-show="$store.sidebar.open" class="flex-1 text-left min-w-0">
                    <span class="block text-sm font-medium whitespace-nowrap overflow-hidden">{{ Auth::user()->name }}</span>
                    <span class="block text-xs text-gray-500 whitespace-nowrap overflow-hidden">{{ Auth::user()->email }}</span>
                </span>
                <span x-cloak x-show="!$store.sidebar.open"
                      class="absolute left-full ml-3 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md
                             whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150
                             pointer-events-none z-[100] shadow-lg border border-gray-700">
                    {{ Auth::user()->name }}
                </span>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute bottom-full left-0 mb-1 w-48 bg-gray-800 rounded-xl shadow-xl border border-gray-700 py-1 z-50"
                 style="display: none;">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    Perfil
                </a>
                <div class="border-t border-gray-700 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
