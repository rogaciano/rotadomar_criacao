<x-app-layout maxWidth="max-w-full">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Planejamento - Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <!-- Botões de ação -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap gap-3">
                    {{-- Nova Capacidade - Azul --}}
                    <a href="{{ route('localizacao-capacidade.create') }}" class="btn-ghost-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Nova Capacidade
                    </a>

                    {{-- Gerar Capacidades - Verde --}}
                    <button onclick="openGerarCapacidadesModal()" class="btn-ghost-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Gerar Capacidades
                    </button>

                    {{-- Listagem - Cinza --}}
                    <a href="{{ route('localizacao-capacidade.index') }}" class="btn-ghost-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Listagem
                    </a>

                    {{-- Fluxo - Roxo --}}
                    <a href="{{ route('etapas-producao.visualizar-fluxo-quantidades') }}" target="_blank" class="btn-ghost-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Fluxo
                    </a>

                    {{-- Calendário - Laranja --}}
                    <a href="{{ route('localizacao-capacidade.calendario', ['mes' => $mes, 'ano' => $ano, 'localizacao_id' => $localizacaoId ?? '']) }}" class="btn-ghost-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Calendário
                    </a>

                    {{-- Sugestões - Verde Água --}}
                    <a href="{{ route('sugestoes.index') }}" class="btn-ghost-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.945a2 2 0 002.22 0L21 8m-2 10H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z" />
                        </svg>
                        Sugestões
                    </a>
                </div>

                <div class="flex flex-wrap gap-2">
                    {{-- PDF Paisagem --}}
                    <a href="{{ route('localizacao-capacidade.relatorio-pdf', ['mes' => $mes, 'ano' => $ano, 'localizacao_id' => $localizacaoId ?? '', 'marca_id' => $marcaId ?? '', 'etapa_id' => $etapaId ?? '', 'referencia' => $referencia ?? '', 'orientation' => 'landscape']) }}" target="_blank" class="btn-ghost-rose">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF Paisagem
                    </a>

                    {{-- PDF Retrato --}}
                    <a href="{{ route('localizacao-capacidade.relatorio-pdf', ['mes' => $mes, 'ano' => $ano, 'localizacao_id' => $localizacaoId ?? '', 'marca_id' => $marcaId ?? '', 'etapa_id' => $etapaId ?? '', 'referencia' => $referencia ?? '', 'orientation' => 'portrait']) }}" target="_blank" class="btn-ghost-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF Retrato
                    </a>
                </div>
            </div>

            @push('styles')
    <style>
        /* Select2 Light Mode */
        .select2-container--default .select2-selection--multiple {
            background-color: #fff !important;
            border-color: #cbd5e1 !important;
            border-radius: 0.75rem !important;
            min-height: 38px !important;
            padding: 2px 4px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #eef2ff !important;
            border-color: #c7d2fe !important;
            color: #4338ca !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            border-radius: 9999px !important;
            padding: 2px 8px !important;
            margin: 2px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #4338ca !important;
            margin-right: 5px !important;
            border: none !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: none !important;
            color: #3730a3 !important;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
            margin-top: 5px !important;
        }
        .select2-container--default .select2-search--inline .select2-search__field::placeholder {
            color: #94a3b8 !important;
        }
        /* Dropdown */
        .select2-dropdown {
            border-color: #cbd5e1 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1 !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eef2ff !important;
            color: #4338ca !important;
        }

        /* Select2 Dark Mode */
        .dark .select2-container--default .select2-selection--multiple {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }
        .dark .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2) !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #312e81 !important;
            border-color: #4338ca !important;
            color: #c7d2fe !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #c7d2fe !important;
        }
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #e0e7ff !important;
        }
        .dark .select2-container--default .select2-search--inline .select2-search__field {
            color: #e2e8f0 !important;
        }
        .dark .select2-container--default .select2-search--inline .select2-search__field::placeholder {
            color: #64748b !important;
        }
        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }
        .dark .select2-container--default .select2-results__option {
            color: #e2e8f0 !important;
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5 !important;
            color: #fff !important;
        }
        .dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #312e81 !important;
            color: #c7d2fe !important;
        }
        .dark .select2-container--default .select2-results__group {
            color: #94a3b8 !important;
        }

    </style>
    @endpush

            <!-- Filtros -->
            <div class="glass dark:glass-dark rounded-2xl border-none ring-1 ring-black/5 mb-6 p-6">
                    <form action="{{ route('localizacao-capacidade.dashboard') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end gap-4">
                        <div class="flex-1 min-w-[150px]">
                            <label for="localizacao_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 text-nowrap">
                                Localização
                                @if($usuarioFaccao ?? false)
                                    <span class="text-xs text-gray-500">(Seu acesso)</span>
                                @endif
                            </label>
                            <select name="localizacao_id[]" id="localizacao_id" multiple class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px] lg:h-auto min-h-[38px]">
                                @if($usuarioFaccao ?? false)
                                    {{-- Usuário de facção: principal no topo, visualizações abaixo --}}
                                    @php
                                        $locPrincipal = $localizacoes->firstWhere('id', $localizacaoPrincipalId ?? null);
                                        $locsVisualizacao = $localizacoes->filter(fn($l) => in_array($l->id, $localizacoesVisualizacaoIds ?? []));
                                        $selectedIds = is_array($localizacaoId) ? $localizacaoId : [$localizacaoId];
                                    @endphp
                                    @if($locPrincipal)
                                        <option value="{{ $locPrincipal->id }}" {{ in_array($locPrincipal->id, $selectedIds) || empty($localizacaoId) ? 'selected' : '' }}>
                                            {{ $locPrincipal->nome_localizacao }} (Principal)
                                        </option>
                                    @endif
                                    @if($locsVisualizacao->count() > 0)
                                        <optgroup label="Visualização">
                                            @foreach($locsVisualizacao as $locVis)
                                                <option value="{{ $locVis->id }}" {{ in_array($locVis->id, $selectedIds) ? 'selected' : '' }}>
                                                    {{ $locVis->nome_localizacao }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @else
                                    {{-- Usuário normal: pode ver todas --}}
                                    @php
                                        $selectedIds = is_array($localizacaoId) ? $localizacaoId : ($localizacaoId ? [$localizacaoId] : []);
                                    @endphp
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ in_array($localizacao->id, $selectedIds) ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="w-full lg:w-32">
                            <label for="referencia" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Referência</label>
                            <input type="text" name="referencia" id="referencia" value="{{ $referencia ?? '' }}" placeholder="Ex: 1234" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px]">
                        </div>

                        <div class="w-full lg:w-32">
                            <label for="mes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mês</label>
                            <select name="mes" id="mes" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px]">
                                @php
                                    $meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                @endphp
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                        {{ $meses[$m] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full lg:w-28">
                            <label for="ano" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ano</label>
                            <select name="ano" id="ano" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px]">
                                @foreach(range(now()->year - 1, now()->year + 2) as $a)
                                    <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1 min-w-[120px]">
                            <label for="marca_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Marca</label>
                            <select name="marca_id" id="marca_id" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px]">
                                <option value="">Todas as Marcas</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" {{ ($marcaId ?? '') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nome_marca }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1 min-w-[150px]">
                            <label for="etapa_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Etapa de Produção</label>
                            <select name="etapa_id" id="etapa_id" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-[38px]">
                                <option value="">Todas as Etapas</option>
                                @foreach($etapasProducao as $etapa)
                                    <option value="{{ $etapa->id }}" {{ ($etapaId ?? '') == $etapa->id ? 'selected' : '' }}>
                                        {{ $etapa->icone ?? '' }} {{ $etapa->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full lg:w-auto">
                            <button type="submit" class="btn-ghost-primary w-full lg:w-auto h-[42px] flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Atualizar
                            </button>
                        </div>
                    </form>

                    <!-- Botão de Toggle Global para Produtos Previstos -->
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" id="toggleAllProducts" onclick="toggleAllProductsVisibility()" class="btn-ghost-purple">
                            <svg id="toggleIconShow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="toggleIconHide" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <span id="toggleText">Mostrar Todos os Produtos</span>
                        </button>
                        <span class="ml-3 text-sm text-slate-500 dark:text-slate-400">Expande ou recolhe todas as listas de produtos previstos</span>
                    </div>
            </div>

            <!-- Resumo Geral -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                @php
                    $totalCapacidade = $dadosDashboard->sum('capacidade');
                    $totalPrevistos = $dadosDashboard->sum('produtos_previstos');
                    $totalReferencias = $dadosDashboard->pluck('produtos')->flatten()->unique('id')->count();
                    $totalSaldo = $totalCapacidade - $totalPrevistos;
                    $totalPercentual = $totalCapacidade > 0 ? round(($totalPrevistos / $totalCapacidade) * 100, 1) : 0;
                @endphp

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Capacidade Total</p>
                                <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">{{ $totalCapacidade }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 dark:text-blue-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produtos Previstos</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalPrevistos > $totalCapacidade ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">{{ $totalPrevistos }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalPrevistos > $totalCapacidade ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Referências</p>
                                <p class="mt-1 text-3xl font-semibold text-purple-600 dark:text-purple-400">{{ $totalReferencias }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-600 dark:text-purple-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Disponível</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalSaldo < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">{{ $totalSaldo }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalSaldo < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Taxa de Ocupação</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalPercentual > 100 ? 'text-red-600 dark:text-red-400' : ($totalPercentual > 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">{{ $totalPercentual }}%</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalPercentual > 100 ? 'text-red-600 dark:text-red-400' : ($totalPercentual > 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalhes por Localização -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg"
                 @abrir-modal-etapa.window="
                    modalEtapaAction = $event.detail.action;
                    modalEtapaId = $event.detail.etapaId;
                    modalEtapaNome = $event.detail.etapaNome;
                    modalEtapaObservacao = '';
                    modalEtapaAberto = true;
                 "
                 @abrir-modal-confirmar-chegada.window="
                    modalChegadaAction = $event.detail.action;
                    modalChegadaReferencia = $event.detail.referencia;
                    modalChegadaMotorista = $event.detail.motorista;
                    modalChegadaVeiculo = $event.detail.veiculo;
                    modalChegadaBackUrl = $event.detail.backUrl;
                    modalChegadaObservacao = '';
                    modalChegadaAberto = true;
                 "
                 x-data="{
                    modalEtapaAberto: false,
                    modalEtapaAction: '',
                    modalEtapaId: null,
                    modalEtapaNome: '',
                    modalEtapaObservacao: '',
                    fecharModalEtapa() {
                        this.modalEtapaAberto = false;
                    },
                    modalChegadaAberto: false,
                    modalChegadaAction: '',
                    modalChegadaReferencia: '',
                    modalChegadaMotorista: '',
                    modalChegadaVeiculo: '',
                    modalChegadaBackUrl: '',
                    modalChegadaObservacao: '',
                    fecharModalChegada() {
                        this.modalChegadaAberto = false;
                    }
                 }">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Capacidade por Localização</h3>

                    @if($dadosDashboard->count() > 0)
                        <div class="space-y-4">
                            @foreach($dadosDashboard as $dado)
                                <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 {{ $dado['acima_capacidade'] ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-white dark:bg-slate-900' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                {{ $dado['localizacao']->nome_localizacao }}
                                            </h4>
                                            @if(!empty($dado['observacoes']))
                                                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-sm font-medium rounded-lg border border-amber-200">
                                                    {{ $dado['observacoes'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($dado['acima_capacidade'])
                                                <!-- <button
                                                    onclick="abrirModalRedistribuicao({{ $dado['localizacao']->id }}, {{ $mes}}, {{ $ano }})"
                                                    class="px-3 py-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded-md transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                    Redistribuir
                                                </button> -->
                                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">
                                                    ACIMA DA CAPACIDADE
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Capacidade</p>
                                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $dado['capacidade'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Previstos</p>
                                            <p class="text-2xl font-bold {{ $dado['acima_capacidade'] ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $dado['produtos_previstos'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ $dado['saldo'] > 0 ? 'Capacidade Disponível' : 'Acima da Capacidade' }}</p>
                                            <p class="text-2xl font-bold {{ $dado['saldo'] > 0 ? ' text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                @if($dado['saldo'] > 0)+@endif{{ abs($dado['saldo']) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Ocupação</p>
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 dark:bg-slate-700 rounded-full h-4 mr-2">
                                                    <div class="h-4 rounded-full {{ $dado['percentual'] > 100 ? 'bg-red-600' : ($dado['percentual'] > 80 ? 'bg-yellow-600' : 'bg-green-600') }}" style="width: {{ min($dado['percentual'], 100) }}%"></div>
                                                </div>
                                                <span class="text-sm font-bold {{ $dado['percentual'] > 100 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                    {{ $dado['percentual'] }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Barra de Comparação Visual -->
                                    <div class="mt-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg relative overflow-hidden">
                                                <div class="absolute top-0 left-0 h-full bg-blue-200 dark:bg-blue-900/50" style="width: 100%"></div>
                                                <div class="absolute top-0 left-0 h-full {{ $dado['acima_capacidade'] ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ min($dado['percentual'], 100) }}%"></div>
                                                <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-gray-700 dark:text-white">
                                                    {{ $dado['produtos_previstos'] }} / {{ $dado['capacidade'] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span>0</span>
                                            <span>Capacidade: {{ $dado['capacidade'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Lista de Produtos Previstos -->
                                    @if($dado['produtos']->count() > 0)
                                        <div class="mt-4 border-t dark:border-slate-700 pt-4 produtos-previstos-section"
                                             x-data="{ open: {{ (request()->boolean('expand_produtos') || ($usuarioRestrito ?? false)) ? 'true' : 'false' }} }"
                                             x-on:toggle-all.window="open = $event.detail.show"
                                             x-init="$watch('open', value => $el.dataset.open = value)"
                                             data-open="{{ (request()->boolean('expand_produtos') || ($usuarioRestrito ?? false)) ? 'true' : 'false' }}">
                                            <button @click="open = !open" class="produtos-toggle-btn w-full flex items-center justify-between text-left p-2 hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <span class="font-semibold text-gray-700 dark:text-gray-200">Produtos Previstos ({{ $dado['produtos']->count() }})</span>
                                                </div>
                                                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 icon-expand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 icon-collapse" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" x-transition class="mt-3 produtos-content" style="display: none;">
                                                @php
                                                    // Agrupar produtos por referência + descrição + marca + grupo + qtd total + data + status
                                                    $produtosAgrupados = $dado['produtos']->groupBy(function($produto) {
                                                        // Usar as localizações já carregadas (que já estão filtradas por mês/ano)
                                                        $primeiraData = $produto->localizacoes
                                                            ->whereNotNull('pivot.data_prevista_faccao')
                                                            ->sortBy('pivot.data_prevista_faccao')
                                                            ->first();

                                                        $dataFormatada = 'N/A';
                                                        if ($primeiraData && $primeiraData->pivot->data_prevista_faccao) {
                                                            $dataFormatada = is_string($primeiraData->pivot->data_prevista_faccao)
                                                                ? \Carbon\Carbon::parse($primeiraData->pivot->data_prevista_faccao)->format('Y-m-d')
                                                                : $primeiraData->pivot->data_prevista_faccao->format('Y-m-d');
                                                        }

                                                        return $produto->id . '|' .
                                                               $produto->referencia . '|' .
                                                               $produto->descricao . '|' .
                                                               ($produto->marca ? $produto->marca->id : 'sem_marca') . '|' .
                                                               ($produto->grupoProduto ? $produto->grupoProduto->id : 'sem_grupo') . '|' .
                                                               $produto->quantidade . '|' .
                                                               $dataFormatada . '|' .
                                                               ($produto->status ? $produto->status->id : 'sem_status');
                                                    });

                                                    $corClasses = [
                                                        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'green' => 'bg-green-100 text-green-800 border-green-200',
                                                        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'red' => 'bg-red-100 text-red-800 border-red-200',
                                                        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                        'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                        'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                        'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
                                                        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                    ];
                                                @endphp

                                                <div class="hidden md:block">
                                                    @include('localizacao-capacidade.partials.desktop-products', [
                                                        'produtosAgrupados' => $produtosAgrupados,
                                                        'etapasProducao' => $etapasProducao,
                                                        'corClasses' => $corClasses
                                                    ])
                                                </div>

                                                <div class="md:hidden">
                                                    @include('localizacao-capacidade.partials.mobile-products', [
                                                        'produtosAgrupados' => $produtosAgrupados,
                                                        'etapasProducao' => $etapasProducao,
                                                        'corClasses' => $corClasses
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Modal de Observação para Mudança de Etapa -->
                        <div x-show="modalEtapaAberto"
                             x-transition
                             class="fixed inset-0 z-50 overflow-y-auto"
                             style="display: none;"
                             role="dialog"
                             aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="fecharModalEtapa()"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                    <form :action="modalEtapaAction" method="POST">
                                        @csrf
                                        <input type="hidden" name="etapa_id" :value="modalEtapaId">
                                        <input type="hidden" name="back_url" value="{{ request()->fullUrlWithQuery(['expand_produtos' => 1]) }}">

                                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Confirmar mudança de etapa</h3>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                Etapa selecionada: <span class="font-medium" x-text="modalEtapaNome"></span>
                                            </p>

                                            <div class="mt-4">
                                                <label for="observacao_etapa_planejamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observação (opcional)</label>
                                                <textarea id="observacao_etapa_planejamento"
                                                          name="observacao"
                                                          rows="4"
                                                          maxlength="255"
                                                          x-model="modalEtapaObservacao"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                                          placeholder="Digite uma observação para registrar no histórico..."></textarea>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                                                Confirmar
                                            </button>
                                            <button type="button" @click="fecharModalEtapa()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de Confirmar Chegada do Motorista (Logística) -->
                        <div x-show="modalChegadaAberto"
                             x-transition
                             class="fixed inset-0 z-50 overflow-y-auto"
                             style="display: none;"
                             role="dialog"
                             aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="fecharModalChegada()"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                                    <form :action="modalChegadaAction" method="POST">
                                        @csrf
                                        <input type="hidden" name="back_url" :value="modalChegadaBackUrl">

                                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Confirmar Chegada do Motorista</h3>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                Produto: <strong x-text="modalChegadaReferencia"></strong>
                                            </p>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                                Motorista: <strong x-text="modalChegadaMotorista"></strong> · Veículo: <strong x-text="modalChegadaVeiculo"></strong>
                                            </p>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observação (opcional)</label>
                                                <textarea name="observacao_origem"
                                                          rows="3"
                                                          maxlength="1000"
                                                          x-model="modalChegadaObservacao"
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                                          placeholder="Ex: Motorista chegou às 14h..."></textarea>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 sm:ml-3 sm:w-auto sm:text-sm">
                                                Confirmar Chegada
                                            </button>
                                            <button type="button" @click="fecharModalChegada()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium text-lg mb-2">Nenhuma capacidade cadastrada para este período</p>
                            <p class="text-gray-500">Cadastre capacidades mensais para visualizar o dashboard.</p>
                            <p class="text-gray-500 text-sm mt-2">Use o botão "Nova Capacidade" no topo da página.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Gerar Capacidades -->
    <div id="modalGerarCapacidades" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Gerar Capacidades do Mês</h3>
                <button onclick="fecharModalGerarCapacidades()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Esta ação criará registros de capacidade mensal para <strong>todas as localizações ativas</strong> que possuem capacidade maior que zero, usando o valor padrão cadastrado na localização.
                    </p>
                </div>

                <div>
                    <label for="mesGerar" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                    <select id="mesGerar" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @php
                            $mesesNomes = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                            $mesAtual = date('n');
                        @endphp
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $mesAtual ? 'selected' : '' }}>
                                {{ $mesesNomes[$m] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="anoGerar" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                    <select id="anoGerar" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @php $anoAtual = date('Y'); @endphp
                        @foreach(range($anoAtual - 1, $anoAtual + 2) as $a)
                            <option value="{{ $a }}" {{ $a == $anoAtual ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs text-yellow-800">
                        <strong>Atenção:</strong> Registros já existentes para o mesmo mês/ano não serão duplicados.
                    </p>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button onclick="fecharModalGerarCapacidades()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button id="btnGerarCapacidades" onclick="gerarCapacidades()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Gerar Capacidades
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ===== MODAL DE GERAR CAPACIDADES =====
        function openGerarCapacidadesModal() {
            document.getElementById('modalGerarCapacidades').classList.remove('hidden');
        }

        function fecharModalGerarCapacidades() {
            document.getElementById('modalGerarCapacidades').classList.add('hidden');
        }

        function gerarCapacidades() {
            const mes = document.getElementById('mesGerar').value;
            const ano = document.getElementById('anoGerar').value;

            if (!mes || !ano) {
                alert('Por favor, selecione mês e ano');
                return;
            }

            const btn = document.getElementById('btnGerarCapacidades');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Gerando...';

            fetch('{{ route("localizacao-capacidade.gerar-mes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ mes, ano })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    fecharModalGerarCapacidades();
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao gerar capacidades');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao gerar capacidades');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Gerar Capacidades';
            });
        }

        // Fechar modal ao clicar fora
        document.getElementById('modalGerarCapacidades')?.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalGerarCapacidades();
            }
        });

        // ===== TOGGLE GLOBAL DE PRODUTOS PREVISTOS =====
        let allProductsVisible = {{ (request()->boolean('expand_produtos') || ($usuarioRestrito ?? false)) ? 'true' : 'false' }};

        function toggleAllProductsVisibility() {
            allProductsVisible = !allProductsVisible;

            // Disparar evento global para todos os componentes Alpine
            window.dispatchEvent(new CustomEvent('toggle-all', { detail: { show: allProductsVisible } }));

            const toggleText = document.getElementById('toggleText');
            const toggleIconShow = document.getElementById('toggleIconShow');
            const toggleIconHide = document.getElementById('toggleIconHide');

            // Atualizar texto e ícone do botão
            if (allProductsVisible) {
                toggleText.textContent = 'Ocultar Todos os Produtos';
                toggleIconShow.classList.add('hidden');
                toggleIconHide.classList.remove('hidden');
            } else {
                toggleText.textContent = 'Mostrar Todos os Produtos';
                toggleIconShow.classList.remove('hidden');
                toggleIconHide.classList.add('hidden');
            }
        }

        // Inicializar Select2 para localização
        $(document).ready(function() {
            $('#localizacao_id').select2({
                placeholder: "Selecione as localizações...",
                allowClear: true,
                width: '100%',
                language: "pt-BR",
                closeOnSelect: false,
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    return $result;
                }
            });
        });

        // Inicializar estado do botão baseado no estado inicial (usuário restrito)
        document.addEventListener('DOMContentLoaded', function() {
            if (allProductsVisible) {
                const toggleText = document.getElementById('toggleText');
                const toggleIconShow = document.getElementById('toggleIconShow');
                const toggleIconHide = document.getElementById('toggleIconHide');

                if (toggleText) {
                    toggleText.textContent = 'Ocultar Todos os Produtos';
                    toggleIconShow.classList.add('hidden');
                    toggleIconHide.classList.remove('hidden');
                }
            }
        });
    </script>
    @endpush
    <script>
        function abrirModalDataEntrega(produtoLocalizacaoId, dataAtual, nomeLocalizacao, produtoId) {
            const modal = document.getElementById('modal-data-entrega');
            const form = document.getElementById('form-data-entrega');
            const input = document.getElementById('input_data_entrega_faccao');
            const titulo = document.getElementById('modal-data-entrega-titulo');

            titulo.textContent = 'Data de Entrega: ' + nomeLocalizacao;
            input.value = dataAtual;

            // Gerar URL dinamicamente
            let url = "{{ route('produtos.localizacoes.update-data-entrega', ['produto' => 'PRODUTO_ID', 'produtoLocalizacao' => 'PLACEHOLDER']) }}";
            url = url.replace('PRODUTO_ID', produtoId).replace('PLACEHOLDER', produtoLocalizacaoId);
            form.action = url;

            modal.classList.remove('hidden');
        }

        function fecharModalDataEntrega() {
            document.getElementById('modal-data-entrega').classList.add('hidden');
        }
    </script>

    <!-- Modal Data Entrega Facção -->
    <div id="modal-data-entrega" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-data-entrega-titulo">Data de Entrega</h3>
                <form id="form-data-entrega" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="data_entrega_faccao" class="block text-sm font-medium text-gray-700 mb-2">Selecione a Data</label>
                        <input type="date" name="data_entrega_faccao" id="input_data_entrega_faccao"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="fecharModalDataEntrega()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
