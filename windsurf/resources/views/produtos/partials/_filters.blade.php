{{-- Seção de Filtros --}}
<div class="glass dark:glass-dark overflow-hidden border-none ring-1 ring-black/5 rounded-2xl mb-4 sm:mb-8">
    <div class="p-4 sm:p-6">

        {{-- Cabeçalho dos Filtros com Toggle --}}
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filtros</h3>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <button type="submit" form="filter-form" class="btn-ghost-primary w-full sm:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filtrar
                </button>
                <a href="{{ route('produtos.index', ['limpar_filtros' => 1]) }}" id="btn-clear-filters" class="btn-ghost-secondary w-full sm:w-auto justify-center">
                    Limpar Filtros
                </a>
                <button type="button" id="toggle-filters-btn" class="btn-ghost-secondary w-full sm:w-auto justify-center">
                    <svg id="filter-icon-show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg id="filter-icon-hide" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <span id="filter-toggle-text">Ocultar Filtros</span>
                </button>
            </div>
        </div>

        {{-- Filtros Ativos (visível quando filtros estão ocultos) --}}
        <div id="active-filters-summary" class="mb-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/40 dark:to-slate-900/60 border border-indigo-200 dark:border-indigo-900/50 rounded-xl p-4 hidden backdrop-blur-sm ring-1 ring-indigo-500/10 dark:ring-indigo-400/10">
            <div class="flex items-start">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-2">Filtros Ativos:</p>
                    <div id="active-filters-list" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>

        {{-- Formulário de Filtros --}}
        @include('produtos.partials._filter-form', [
            'filters' => $filters,
            'marcas' => $marcas,
            'tecidos' => $tecidos,
            'estilistas' => $estilistas,
            'grupos' => $grupos,
            'statuses' => $statuses,
            'direcionamentosComerciais' => $direcionamentosComerciais,
            'localizacoes' => $localizacoes,
            'situacoes' => $situacoes,
            'localizacoesPlanejamento' => $localizacoesPlanejamento
        ])
    </div>
</div>
