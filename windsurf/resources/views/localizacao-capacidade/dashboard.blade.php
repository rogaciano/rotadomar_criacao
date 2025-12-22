<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard de Capacidade das Localizações') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('localizacao-capacidade.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Capacidade
                </a>

                <button onclick="openGerarCapacidadesModal()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Gerar Capacidades do Mês
                </button>

                <a href="{{ route('localizacao-capacidade.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Ver Listagem
                </a>

                <a href="{{ route('localizacao-capacidade.relatorio-pdf', ['mes' => $mes, 'ano' => $ano, 'localizacao_id' => $localizacaoId ?? '']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Gerar PDF
                </a>

                <a href="{{ route('etapas-producao.visualizar-fluxo-quantidades') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 10-2 0M15 8l-1.333-2.001L12 4.001l-1.667 1.999L9 8m6 0v8a2 2 0 01-2 2H9a2 2 0 01-2-2V8m8 0h-6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10M7 16h10" />
                    </svg>
                    Fluxo com Quantidades
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form action="{{ route('localizacao-capacidade.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Localização
                                @if($usuarioRestrito ?? false)
                                    <span class="text-xs text-gray-500">(Seu acesso)</span>
                                @endif
                            </label>
                            <select name="localizacao_id" id="localizacao_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ ($usuarioRestrito ?? false) ? 'bg-gray-100' : '' }}" {{ ($usuarioRestrito ?? false) ? 'disabled' : '' }}>
                                @if($usuarioRestrito ?? false)
                                    {{-- Usuário restrito: mostrar apenas a localização dele --}}
                                    @php
                                        $localizacaoSelecionada = $localizacoes->firstWhere('id', $localizacaoId);
                                    @endphp
                                    <option value="{{ $localizacaoId }}" selected>
                                        {{ $localizacaoSelecionada->nome_localizacao ?? 'Localização' }}
                                    </option>
                                @else
                                    {{-- Usuário normal: pode ver todas --}}
                                    <option value="">Todas as Localizações</option>
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ ($localizacaoId ?? '') == $localizacao->id ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if($usuarioRestrito ?? false)
                                {{-- Hidden input para manter o valor mesmo com o select disabled --}}
                                <input type="hidden" name="localizacao_id" value="{{ $localizacaoId }}">
                            @endif
                        </div>

                        <div>
                            <label for="mes" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                            <select name="mes" id="mes" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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

                        <div>
                            <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                            <select name="ano" id="ano" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach(range(now()->year - 1, now()->year + 2) as $a)
                                    <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="etapa_id" class="block text-sm font-medium text-gray-700 mb-1">Etapa de Produção</label>
                            <select name="etapa_id" id="etapa_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todas as Etapas</option>
                                @foreach($etapasProducao as $etapa)
                                    <option value="{{ $etapa->id }}" {{ ($etapaId ?? '') == $etapa->id ? 'selected' : '' }}>
                                        {{ $etapa->icone ?? '' }} {{ $etapa->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Atualizar
                            </button>
                        </div>
                    </form>
                    
                    <!-- Botão de Toggle Global para Produtos Previstos -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button type="button" id="toggleAllProducts" onclick="toggleAllProductsVisibility()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg id="toggleIconShow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="toggleIconHide" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <span id="toggleText">Mostrar Todos os Produtos</span>
                        </button>
                        <span class="ml-3 text-sm text-gray-500">Expande ou recolhe todas as listas de produtos previstos</span>
                    </div>
                </div>
            </div>

            <!-- Resumo Geral -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @php
                    $totalCapacidade = $dadosDashboard->sum('capacidade');
                    $totalPrevistos = $dadosDashboard->sum('produtos_previstos');
                    $totalSaldo = $totalCapacidade - $totalPrevistos;
                    $totalPercentual = $totalCapacidade > 0 ? round(($totalPrevistos / $totalCapacidade) * 100, 1) : 0;
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Capacidade Total</p>
                                <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $totalCapacidade }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Produtos Previstos</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalPrevistos > $totalCapacidade ? 'text-red-600' : 'text-green-600' }}">{{ $totalPrevistos }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalPrevistos > $totalCapacidade ? 'text-red-600' : 'text-green-600' }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Saldo Disponível</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalSaldo < 0 ? 'text-red-600' : 'text-green-600' }}">{{ $totalSaldo }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalSaldo < 0 ? 'text-red-600' : 'text-green-600' }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Taxa de Ocupação</p>
                                <p class="mt-1 text-3xl font-semibold {{ $totalPercentual > 100 ? 'text-red-600' : ($totalPercentual > 80 ? 'text-yellow-600' : 'text-green-600') }}">{{ $totalPercentual }}%</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 {{ $totalPercentual > 100 ? 'text-red-600' : ($totalPercentual > 80 ? 'text-yellow-600' : 'text-green-600') }} opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalhes por Localização -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Capacidade por Localização</h3>

                    @if($dadosDashboard->count() > 0)
                        <div class="space-y-4">
                            @foreach($dadosDashboard as $dado)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $dado['acima_capacidade'] ? 'bg-red-50 border-red-200' : 'bg-white' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-lg font-semibold text-gray-800">
                                            {{ $dado['localizacao']->nome_localizacao }}
                                        </h4>
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
                                            <p class="text-xs text-gray-500 uppercase">Capacidade</p>
                                            <p class="text-2xl font-bold text-blue-600">{{ $dado['capacidade'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase">Previstos</p>
                                            <p class="text-2xl font-bold {{ $dado['acima_capacidade'] ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $dado['produtos_previstos'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase">Saldo</p>
                                            <p class="text-2xl font-bold {{ $dado['saldo'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $dado['saldo'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase mb-1">Ocupação</p>
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-4 mr-2">
                                                    <div class="h-4 rounded-full {{ $dado['percentual'] > 100 ? 'bg-red-600' : ($dado['percentual'] > 80 ? 'bg-yellow-600' : 'bg-green-600') }}" style="width: {{ min($dado['percentual'], 100) }}%"></div>
                                                </div>
                                                <span class="text-sm font-bold {{ $dado['percentual'] > 100 ? 'text-red-600' : 'text-gray-700' }}">
                                                    {{ $dado['percentual'] }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Barra de Comparação Visual -->
                                    <div class="mt-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-8 bg-gray-100 rounded-lg relative overflow-hidden">
                                                <div class="absolute top-0 left-0 h-full bg-blue-200" style="width: 100%"></div>
                                                <div class="absolute top-0 left-0 h-full {{ $dado['acima_capacidade'] ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ min($dado['percentual'], 100) }}%"></div>
                                                <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-gray-700">
                                                    {{ $dado['produtos_previstos'] }} / {{ $dado['capacidade'] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span>0</span>
                                            <span>Capacidade: {{ $dado['capacidade'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Lista de Produtos Previstos -->
                                    @if($dado['produtos']->count() > 0)
                                        <div class="mt-4 border-t pt-4 produtos-previstos-section" 
                                             x-data="{ open: {{ ($usuarioRestrito ?? false) ? 'true' : 'false' }} }" 
                                             x-on:toggle-all.window="open = $event.detail.show"
                                             x-init="$watch('open', value => $el.dataset.open = value)" 
                                             data-open="{{ ($usuarioRestrito ?? false) ? 'true' : 'false' }}">
                                            <button @click="open = !open" class="produtos-toggle-btn w-full flex items-center justify-between text-left p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <span class="font-semibold text-gray-700">Produtos Previstos ({{ $dado['produtos']->count() }})</span>
                                                </div>
                                                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 icon-expand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 icon-collapse" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" x-transition class="mt-3 produtos-content" style="display: none;">
                                                <!-- Vista Desktop (Tabela) -->
                                                <div class="hidden md:block overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50/50">
                                                            <tr>
                                                                <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Referência</th>
                                                                <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Descrição</th>
                                                                <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Marca / Grupo</th>
                                                                <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produção e Detalhes</th>
                                                                <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Qtd Total</th>
                                                                <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-100">
                                                            @php
                                                                $produtosAgrupados = $dado['produtos']->groupBy(function($produto) {
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
                                                                    'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                                    'green' => 'bg-green-50 text-green-700 border-green-100',
                                                                    'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                                    'red' => 'bg-red-50 text-red-700 border-red-100',
                                                                    'purple' => 'bg-purple-50 text-purple-700 border-purple-100',
                                                                    'gray' => 'bg-gray-50 text-gray-700 border-gray-100',
                                                                    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                                                    'pink' => 'bg-pink-50 text-pink-700 border-pink-100',
                                                                    'orange' => 'bg-orange-50 text-orange-700 border-orange-100',
                                                                ];
                                                            @endphp

                                                            @foreach($produtosAgrupados as $chave => $produtosGrupo)
                                                                @php
                                                                    $produtoPrincipal = $produtosGrupo->first();
                                                                    $etapaIdsNoGrupo = $produtosGrupo->flatMap(function($p) {
                                                                        return $p->localizacoes->pluck('pivot.etapa_atual_id');
                                                                    })->unique()->filter()->toArray();
                                                                    $etapasNoGrupo = $etapasProducao->whereIn('id', $etapaIdsNoGrupo);
                                                                @endphp
                                                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                                                    <td class="px-6 py-4">
                                                                        <div class="flex flex-col">
                                                                            <a href="{{ route('produtos.show', $produtoPrincipal->id) }}?back_url={{ urlencode(request()->fullUrl()) }}" class="text-sm font-black text-indigo-600 hover:underline">
                                                                                {{ $produtoPrincipal->referencia }}
                                                                            </a>
                                                                            @php
                                                                                $dataPrevista = null;
                                                                                foreach($produtosGrupo as $produto) {
                                                                                    $locComData = $produto->localizacoes->whereNotNull('pivot.data_prevista_faccao')->sortBy('pivot.data_prevista_faccao')->first();
                                                                                    if ($locComData && $locComData->pivot->data_prevista_faccao) {
                                                                                        $dataPrevista = is_string($locComData->pivot->data_prevista_faccao) ? \Carbon\Carbon::parse($locComData->pivot->data_prevista_faccao)->format('d/m/Y') : $locComData->pivot->data_prevista_faccao->format('d/m/Y');
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            @if($dataPrevista)
                                                                                <span class="text-[10px] font-bold text-gray-400 mt-1">PREV: {{ $dataPrevista }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <span class="text-sm text-gray-600">{{ $produtoPrincipal->descricao }}</span>
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <div class="flex flex-col gap-1">
                                                                            @if($produtoPrincipal->marca)
                                                                                <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded-full w-fit" style="background-color: {{ $produtoPrincipal->marca->cor_fundo ?? '#EEF2FF' }}; color: {{ $produtoPrincipal->marca->cor_fonte ?? '#4338CA' }};">
                                                                                    {{ $produtoPrincipal->marca->nome_marca }}
                                                                                </span>
                                                                            @endif
                                                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $produtoPrincipal->grupoProduto->descricao ?? 'N/A' }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        @php
                                                                            $todasObsLocalizacoes = collect();
                                                                            foreach($produtosGrupo as $produto) {
                                                                                $obsLoc = $produto->localizacoes->filter(fn($loc) => !empty($loc->pivot->ordem_producao) || !empty($loc->pivot->observacao));
                                                                                $todasObsLocalizacoes = $todasObsLocalizacoes->merge($obsLoc);
                                                                            }
                                                                            $todasObsLocalizacoes = $todasObsLocalizacoes->unique(fn($loc) => $loc->pivot->ordem_producao . '|' . $loc->pivot->observacao);
                                                                        @endphp

                                                                        @if($todasObsLocalizacoes->count() > 0)
                                                                            <div class="space-y-2">
                                                                                @php $totalQtdOps = 0; @endphp
                                                                                @foreach($todasObsLocalizacoes as $loc)
                                                                                    @php
                                                                                        $qtdAlocada = 0;
                                                                                        foreach($produtosGrupo as $produto) {
                                                                                            $localizacaoAtual = $produto->localizacoes->firstWhere('pivot.ordem_producao', $loc->pivot->ordem_producao);
                                                                                            if ($localizacaoAtual) { $qtdAlocada = $localizacaoAtual->pivot->quantidade; break; }
                                                                                        }
                                                                                        $totalQtdOps += $qtdAlocada;
                                                                                        $etapaLinha = $loc->pivot->etapa_atual_id ? $etapasProducao->firstWhere('id', $loc->pivot->etapa_atual_id) : null;
                                                                                    @endphp
                                                                                    <div class="flex items-center gap-3 text-xs p-2 bg-gray-50 rounded-lg border border-gray-100">
                                                                                        <span class="font-black text-indigo-700 w-20">OP: {{ $loc->pivot->ordem_producao ?: 'N/A' }}</span>
                                                                                        @if($etapaLinha)
                                                                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase border {{ $corClasses[$etapaLinha->cor] ?? 'bg-gray-100 text-gray-700' }}">
                                                                                                {{ $etapaLinha->icone }} {{ $etapaLinha->nome }}
                                                                                            </span>
                                                                                        @endif
                                                                                        <span class="font-black text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200">{{ number_format($qtdAlocada, 0, ',', '.') }}</span>
                                                                                       
                                                                                        @if($loc->pivot->data_entrega_faccao)
                                                                                            <span class="text-[9px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded border border-purple-100">ENT: {{ \Carbon\Carbon::parse($loc->pivot->data_entrega_faccao)->format('d/m') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                @endforeach

                                                                                @if($todasObsLocalizacoes->count() > 1)
                                                                                    <div class="flex items-center justify-end pr-2 py-1">
                                                                                        <span class="text-[10px] font-bold text-gray-400 uppercase mr-2">Total OPs:</span>
                                                                                        <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ number_format($totalQtdOps, 0, ',', '.') }}</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif

                                                                        {{-- Direcionamento e Observações --}}
                                                                        @php
                                                                            $direcionamentoComercial = $produtosGrupo->first(fn($p) => $p->direcionamentoComercial)?->direcionamentoComercial;
                                                                            $obsProduto = \App\Models\ProdutoObservacao::where('produto_id', $produtoPrincipal->id)->get();
                                                                        @endphp

                                                                        @if($direcionamentoComercial || $obsProduto->count() > 0)
                                                                            <div class="mt-3 pt-2 border-t border-gray-100 space-y-2">
                                                                                @if($direcionamentoComercial)
                                                                                    <div class="flex items-center gap-2">
                                                                                        <span class="text-[10px] font-extrabold text-purple-500 uppercase">Direcionamento:</span>
                                                                                        <span class="text-[11px] text-gray-600 font-medium">{{ $direcionamentoComercial->descricao }}</span>
                                                                                    </div>
                                                                                @endif

                                                                                @if($obsProduto->count() > 0)
                                                                                    <div class="bg-amber-50/50 p-2 rounded-lg border border-amber-100/50">
                                                                                        <span class="text-[10px] font-extrabold text-amber-600 uppercase block mb-1">Observações:</span>
                                                                                        @foreach($obsProduto as $obs)
                                                                                            <div class="text-[10px] text-amber-900 leading-tight mb-1">{!! Str::limit(strip_tags($obs->observacao), 150) !!}</div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-6 py-4 text-center">
                                                                        <span class="text-sm font-black text-gray-900">{{ number_format($produtoPrincipal->quantidade, 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-6 py-4 text-right">
                                                                        <span class="px-2 py-1 text-[10px] font-black uppercase rounded-full {{ $produtoPrincipal->status->descricao == 'Ativo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                                            {{ $produtoPrincipal->status->descricao }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Vista Mobile (Cards) -->
                                                <div class="md:hidden space-y-4 pt-2">
                                                    @foreach($produtosAgrupados as $chave => $produtosGrupo)
                                                        @php
                                                            $produtoPrincipal = $produtosGrupo->first();
                                                        @endphp
                                                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                                                            <div class="flex justify-between items-start mb-3">
                                                                <div>
                                                                    <a href="{{ route('produtos.show', $produtoPrincipal->id) }}?back_url={{ urlencode(request()->fullUrl()) }}" class="text-base font-black text-indigo-600">
                                                                        {{ $produtoPrincipal->referencia }}
                                                                    </a>
                                                                    <div class="flex gap-2 mt-1">
                                                                        @if($produtoPrincipal->marca)
                                                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full" style="background-color: {{ $produtoPrincipal->marca->cor_fundo ?? '#EEF2FF' }}; color: {{ $produtoPrincipal->marca->cor_fonte ?? '#4338CA' }};">
                                                                                {{ $produtoPrincipal->marca->nome_marca }}
                                                                            </span>
                                                                        @endif
                                                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase">
                                                                            {{ $produtoPrincipal->status->descricao }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Qtd Total</span>
                                                                    <span class="text-lg font-black text-gray-900">{{ number_format($produtoPrincipal->quantidade, 0, ',', '.') }}</span>
                                                                </div>
                                                            </div>

                                                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $produtoPrincipal->descricao }}</p>

                                                            <!-- Alocações/Produção -->
                                                            <div class="space-y-2 border-t border-gray-100 pt-3">
                                                                @php
                                                                    $todasObsLocalizacoes = collect();
                                                                    foreach($produtosGrupo as $produto) {
                                                                        $obsLoc = $produto->localizacoes->filter(fn($loc) => !empty($loc->pivot->ordem_producao) || !empty($loc->pivot->observacao));
                                                                        $todasObsLocalizacoes = $todasObsLocalizacoes->merge($obsLoc);
                                                                    }
                                                                    $todasObsLocalizacoes = $todasObsLocalizacoes->unique(fn($loc) => $loc->pivot->ordem_producao . '|' . $loc->pivot->observacao);
                                                                @endphp

                                                                @foreach($todasObsLocalizacoes as $loc)
                                                                    @php
                                                                        $qtdAlocada = 0;
                                                                        foreach($produtosGrupo as $produto) {
                                                                            $localizacaoAtual = $produto->localizacoes->firstWhere('pivot.ordem_producao', $loc->pivot->ordem_producao);
                                                                            if ($localizacaoAtual) { $qtdAlocada = $localizacaoAtual->pivot->quantidade; break; }
                                                                        }
                                                                        $etapaLinha = $loc->pivot->etapa_atual_id ? $etapasProducao->firstWhere('id', $loc->pivot->etapa_atual_id) : null;
                                                                    @endphp
                                                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                                                        <div class="flex justify-between items-center mb-2">
                                                                            <span class="text-xs font-black text-indigo-700">OP: {{ $loc->pivot->ordem_producao ?: 'N/A' }}</span>
                                                                            <span class="px-2 py-0.5 bg-white border border-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg">
                                                                                {{ number_format($qtdAlocada, 0, ',', '.') }} un
                                                                            </span>
                                                                        </div>
                                                                        
                                                                        @if($etapaLinha)
                                                                            <div class="flex items-center gap-2 mb-2">
                                                                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase border {{ $corClasses[$etapaLinha->cor] ?? 'bg-gray-100 text-gray-700' }}">
                                                                                    {{ $etapaLinha->icone }} {{ $etapaLinha->nome }}
                                                                                </span>
                                                                            </div>
                                                                        @endif

                                                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                                                            @if($loc->pivot->data_prevista_faccao)
                                                                                <div class="text-[10px]">
                                                                                    <span class="text-gray-400 font-bold block uppercase tracking-tighter">Previsão</span>
                                                                                    <span class="text-gray-900 font-black">{{ \Carbon\Carbon::parse($loc->pivot->data_prevista_faccao)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            @endif
                                                                            @if($loc->pivot->data_entrega_faccao)
                                                                                <div class="text-[10px]">
                                                                                    <span class="text-purple-400 font-bold block uppercase tracking-tighter">P. Entrega</span>
                                                                                    <span class="text-purple-700 font-black">{{ \Carbon\Carbon::parse($loc->pivot->data_entrega_faccao)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        @if($loc->pivot->observacao)
                                                                            <div class="mt-2 text-[11px] text-gray-500 italic bg-white p-2 rounded-lg border border-gray-100">
                                                                                {!! strip_tags($loc->pivot->observacao) !!}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            {{-- Direcionamento e Observações (Mobile) --}}
                                                            @php
                                                                $direcionamentoComercial = $produtosGrupo->first(fn($p) => $p->direcionamentoComercial)?->direcionamentoComercial;
                                                                $obsProduto = \App\Models\ProdutoObservacao::where('produto_id', $produtoPrincipal->id)->get();
                                                            @endphp

                                                            @if($direcionamentoComercial || $obsProduto->count() > 0)
                                                                <div class="mt-3 space-y-2">
                                                                    @if($direcionamentoComercial)
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="text-[10px] font-extrabold text-purple-500 uppercase">Comercial:</span>
                                                                            <span class="text-[11px] text-gray-600">{{ $direcionamentoComercial->descricao }}</span>
                                                                        </div>
                                                                    @endif

                                                                    @if($obsProduto->count() > 0)
                                                                        <div class="bg-amber-50 p-2 rounded-xl border border-amber-100">
                                                                            <span class="text-[10px] font-extrabold text-amber-600 uppercase block mb-1">Obs Produto:</span>
                                                                            @foreach($obsProduto as $obs)
                                                                                <div class="text-[10px] text-amber-900 leading-tight mb-1">{!! Str::limit(strip_tags($obs->observacao), 100) !!}</div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="mt-4">
                                                                <a href="{{ route('produtos.show', $produtoPrincipal->id) }}?back_url={{ urlencode(request()->fullUrl()) }}" class="flex items-center justify-center w-full py-2 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all">
                                                                    Ver Detalhes do Produto
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
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
        let allProductsVisible = {{ ($usuarioRestrito ?? false) ? 'true' : 'false' }};

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
                        <label for="data_entrega_faccao" class="block text-sm font-medium text-gray-700 mb-2">Selecione a Data *</label>
                        <input type="date" name="data_entrega_faccao" id="input_data_entrega_faccao" required
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
