<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()->canCreate('produtos'))
                    <a href="{{ route('produtos.create') }}" class="btn-ghost-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Adicionar Produto
                    </a>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(auth()->user()->canRead('produtos'))
                        <button id="btn-gerar-pdf-landscape" class="btn-ghost-rose">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            PDF Paisagem
                        </button>
                        <button id="btn-gerar-pdf-portrait" class="btn-ghost-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            PDF Retrato
                        </button>
                    @endif
                </div>
            </div>

            <!-- Filtros -->
            <div class="glass dark:glass-dark overflow-hidden border-none ring-1 ring-black/5 rounded-2xl mb-8">
                <div class="p-6">

                    <!-- Cabeçalho dos Filtros com Toggle -->
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filtros</h3>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <button type="submit" form="filter-form" class="btn-ghost-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('produtos.index', ['limpar_filtros' => 1]) }}" id="btn-clear-filters" class="btn-ghost-secondary">
                                Limpar Filtros
                            </a>
                            <button type="button" id="toggle-filters-btn" class="btn-ghost-secondary">
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

                    <!-- Filtros Ativos (visível quando filtros estão ocultos) -->
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

                    <!-- Formulário de Filtros -->
                    <div id="filters-container" class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form id="filter-form" action="{{ route('produtos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-1">
                                <label for="referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ $filters['referencia'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
                            </div>

                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ $filters['descricao'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a descrição do produto">
                            </div>

                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
                                <select name="marca_id" id="marca_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ (($filters['marca_id'] ?? '') == $marca->id || (($filters['marca'] ?? '') == $marca->nome_marca)) ? 'selected' : '' }}>
                                            {{ $marca->nome_marca }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('marca'))
                                    <input type="hidden" name="marca" value="{{ request('marca') }}">
                                @endif
                            </div>

                            <div>
                                <label for="tecido_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tecido (um ou mais)</label>
                                <select name="tecido_id[]" id="tecido_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($tecidos as $tecido)
                                        <option value="{{ $tecido->id }}" {{ !empty($filters['tecido_id']) && in_array($tecido->id, (array)$filters['tecido_id']) ? 'selected' : '' }}>
                                            {{ $tecido->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="estilista_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estilista</label>
                                <select name="estilista_id" id="estilista_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($estilistas as $estilista)
                                        <option value="{{ $estilista->id }}" {{ (($filters['estilista_id'] ?? '') == $estilista->id || (($filters['estilista'] ?? '') == $estilista->nome_estilista)) ? 'selected' : '' }}>
                                            {{ $estilista->nome_estilista }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('estilista'))
                                    <input type="hidden" name="estilista" value="{{ request('estilista') }}">
                                @endif
                            </div>

                            <div>
                                <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo (um ou mais)</label>
                                <select name="grupo_id[]" id="grupo_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ !empty($filters['grupo_id']) && in_array($grupo->id, (array)$filters['grupo_id']) ? 'selected' : '' }}>
                                            {{ $grupo->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status (um ou mais)</label>
                                <select name="status_id[]" id="status_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ !empty($filters['status_id']) && in_array($status->id, (array)$filters['status_id']) ? 'selected' : '' }}>
                                            {{ $status->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="direcionamento_comercial_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Direcionamento Comercial (um ou mais)</label>
                                <select name="direcionamento_comercial_id[]" id="direcionamento_comercial_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($direcionamentosComerciais as $direcionamento)
                                        <option value="{{ $direcionamento->id }}" {{ !empty($filters['direcionamento_comercial_id']) && in_array($direcionamento->id, (array)$filters['direcionamento_comercial_id']) ? 'selected' : '' }}>
                                            {{ $direcionamento->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização (uma ou mais)</label>
                                <select name="localizacao_id[]" id="localizacao_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ !empty($filters['localizacao_id']) && in_array($localizacao->id, (array)$filters['localizacao_id']) ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="situacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Situação (uma ou mais)</label>
                                <select name="situacao_id[]" id="situacao_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($situacoes as $situacao)
                                        <option value="{{ $situacao->id }}" {{ !empty($filters['situacao_id']) && in_array($situacao->id, (array)$filters['situacao_id']) ? 'selected' : '' }}>
                                            {{ $situacao->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="localizacao_planejamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização Planejamento (uma ou mais)</label>
                                <select name="localizacao_planejamento_id[]" id="localizacao_planejamento_id" multiple class="js-select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($localizacoesPlanejamento as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ !empty($filters['localizacao_planejamento_id']) && in_array($localizacao->id, (array)$filters['localizacao_planejamento_id']) ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status_concluido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status de Conclusão</label>
                                <select name="status_concluido" id="status_concluido" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="todos_em_processo" {{ ($filters['status_concluido'] ?? '') == 'todos_em_processo' ? 'selected' : '' }}>🔄 Todos em Processo</option>
                                    <option value="concluido" {{ ($filters['status_concluido'] ?? '') == 'concluido' ? 'selected' : '' }}>✅ Concluídos</option>
                                    <option value="nao_concluido" {{ ($filters['status_concluido'] ?? '') == 'nao_concluido' ? 'selected' : '' }}>⏳ Não Concluídos</option>
                                    <option value="sem_movimentacao" {{ ($filters['status_concluido'] ?? '') == 'sem_movimentacao' ? 'selected' : '' }}>📋 Sem Movimentação</option>
                                </select>
                            </div>

                                                        <div class="md:col-span-1 flex items-end pb-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="incluir_excluidos" id="incluir_excluidos" value="1" {{ isset($filters['incluir_excluidos']) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <label for="incluir_excluidos" class="ml-2 block text-sm text-gray-700">Incluir excluídos</label>
                                </div>
                            </div>


                            <!-- Seção de Filtros por Data -->
                            <div class="md:col-span-4 border-t pt-4 mt-2">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Filtro por Datas</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                    <!-- Data de Cadastro -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Cadastro</label>
                                        <div class="space-y-2">
                                            <div>
                                                <label for="data_inicio" class="block text-xs text-gray-600 mb-1">Início</label>
                                                <input type="date" name="data_inicio" id="data_inicio" value="{{ $filters['data_inicio'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            <div>
                                                <label for="data_fim" class="block text-xs text-gray-600 mb-1">Fim</label>
                                                <input type="date" name="data_fim" id="data_fim" value="{{ $filters['data_fim'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Prevista Produção -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Prevista Produção</label>
                                        <div class="space-y-2">
                                            <div>
                                                <label for="data_prevista_inicio" class="block text-xs text-gray-600 mb-1">Início</label>
                                                <input type="date" name="data_prevista_inicio" id="data_prevista_inicio" value="{{ $filters['data_prevista_inicio'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            <div>
                                                <label for="data_prevista_fim" class="block text-xs text-gray-600 mb-1">Fim</label>
                                                <input type="date" name="data_prevista_fim" id="data_prevista_fim" value="{{ $filters['data_prevista_fim'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Prevista para Facção -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Prevista para Facção</label>
                                        <div class="space-y-2">
                                            <div>
                                                <label for="data_prevista_faccao_inicio" class="block text-xs text-gray-600 mb-1">Início</label>
                                                <input type="date" name="data_prevista_faccao_inicio" id="data_prevista_faccao_inicio" value="{{ $filters['data_prevista_faccao_inicio'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                            <div>
                                                <label for="data_prevista_faccao_fim" class="block text-xs text-gray-600 mb-1">Fim</label>
                                                <input type="date" name="data_prevista_faccao_fim" id="data_prevista_faccao_fim" value="{{ $filters['data_prevista_faccao_fim'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>
                    </div>

            <!-- Mensagem de sucesso -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

                    <!-- Tabela de Produtos -->
                    <div class="table-container">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th scope="col" class="table-header-cell">
                                        Referência
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Descrição
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Prev. Produção
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Facção (1ª Data)
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Marca
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Grupo
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Direcionamento
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Status
                                    </th>
                                    <th scope="col" class="table-header-cell text-center w-8">
                                        OK
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Localização
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Situação
                                    </th>
                                    <th scope="col" class="sticky right-0 table-header-cell text-right bg-gray-50 dark:bg-slate-800 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($produtos as $produto)
                                    <tr class="{{ $produto->trashed() ? 'table-row-trashed' : 'table-row' }}">
                                        <td class="table-cell table-cell-primary line-clamp-1">
                                            <div class="flex items-center">
                                                @if($produto->foto_principal)
                                                    <img src="{{ asset('storage/' . $produto->foto_principal) }}" alt="" class="h-10 w-10 rounded-md object-cover mr-3 border border-gray-200 dark:border-gray-700 shadow-sm">
                                                @endif
                                                <span>{{ $produto->referencia }}</span>
                                            </div>
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $produto->descricao }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $produto->data_prevista_producao_mes_ano }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($produto->primeira_data_prevista_faccao)
                                                {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem data</span>
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($produto->marca && $produto->marca->logo_path)
                                                <img src="{{ asset('storage/' . $produto->marca->logo_path) }}" alt="{{ $produto->marca->nome_marca }}" class="h-6 w-auto object-contain" title="{{ $produto->marca->nome_marca }}">
                                            @else
                                                {{ $produto->marca->nome_marca ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $produto->grupoProduto->descricao ?? 'N/A' }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($produto->direcionamentoComercial)
                                                <span class="font-semibold">
                                                    {{ $produto->direcionamentoComercial->descricao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem direcionamento</span>
                                            @endif
                                        </td>
                                        <td class="table-cell">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                                {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center w-8">
                                            @if($produto->concluido_atual)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mx-auto text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mx-auto text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($produto->localizacao_atual)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                    {{ $produto->localizacao_atual->nome_localizacao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs italic">Não localizado</span>
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($produto->situacao_atual)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
                                                    {{ $produto->situacao_atual->descricao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem situação</span>
                                            @endif
                                        </td>
                                        <td class="sticky right-0 table-cell text-right bg-white dark:bg-slate-900 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                                            <div class="flex items-center justify-end space-x-2">
                                                @if(auth()->user()->canRead('produtos'))
                                                    <a href="{{ route('produtos.show', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-view">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if(!$produto->trashed() && auth()->user()->canUpdate('produtos'))
                                                    <a href="{{ route('produtos.edit', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if(!$produto->trashed() && auth()->user()->canDelete('produtos'))
                                                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($produto->trashed() && auth()->user()->canDelete('produtos'))
                                                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja restaurar este produto?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-restore">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="table-cell table-empty">
                                            Nenhum produto encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $produtos->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicialização do JavaScript

            // Inicializar Select2 simples (single select)
            $('.select2').select2({
                placeholder: "Selecione uma opção",
                allowClear: true,
                width: '100%'
            });

            // Inicializar Select2 multi-select
            $('.js-select2-multi').select2({
                placeholder: "Selecione uma ou mais opções",
                allowClear: true,
                width: '100%',
                language: 'pt-BR',
                closeOnSelect: false
            });

            // Ajustar estilo do Select2 para combinar com Tailwind
            $('.select2-container--default .select2-selection--single').css({
                'height': '38px',
                'padding': '5px',
                'border-color': 'rgb(209, 213, 219)'
            });

            // Ajustar estilo do Select2 multi para combinar com Tailwind
            $('.select2-container--default .select2-selection--multiple').css({
                'min-height': '38px',
                'border-color': 'rgb(209, 213, 219)'
            });

            // Função para gerar PDF com orientação específica
            function gerarPdf(btn, orientation) {
                if (!btn) return;

                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Mostrar indicador de carregamento
                    const originalContent = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Gerando...';

                    // Obter os filtros do formulário
                    const formData = new FormData(document.getElementById('filter-form'));
                    const queryParams = new URLSearchParams(formData).toString();

                    // Gerar o PDF diretamente com force_generate=1 para evitar o erro de contagem
                    const pdfUrl = '{{ route("produtos.lista.pdf") }}?' + queryParams + '&force_generate=1&orientation=' + orientation;

                    // Abrir o PDF em uma nova aba
                    const pdfWindow = window.open(pdfUrl, '_blank');

                    // Restaurar o botão após um curto período
                    setTimeout(function() {
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }, 1500);

                    // Verificar se a janela do PDF foi bloqueada pelo navegador
                    if (!pdfWindow || pdfWindow.closed || typeof pdfWindow.closed === 'undefined') {
                        alert('Por favor, permita pop-ups para este site para visualizar o PDF.');
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                });
            }

            // Gerar PDF Landscape
            gerarPdf(document.getElementById('btn-gerar-pdf-landscape'), 'landscape');

            // Gerar PDF Portrait
            gerarPdf(document.getElementById('btn-gerar-pdf-portrait'), 'portrait');

            // Limpar filtros: função utilitária e bind do botão
            const form = document.getElementById('filter-form');
            const clearButton = document.getElementById('btn-clear-filters');

            function resetFiltersUI() {
                if (!form) return;
                // Limpar inputs de texto e data
                form.querySelectorAll('input[type="text"], input[type="date"]').forEach(function(el) {
                    el.value = '';
                });
                // Limpar selects (inclui Select2)
                form.querySelectorAll('select').forEach(function(sel) {
                    sel.value = '';
                });
                // Resetar Select2 simples explicitamente
                if (typeof $ !== 'undefined' && $('.select2').length) {
                    $('.select2').val(null).trigger('change');
                }
                // Resetar Select2 multi explicitamente
                if (typeof $ !== 'undefined' && $('.js-select2-multi').length) {
                    $('.js-select2-multi').val(null).trigger('change');
                }
            }

            // Se a página foi carregada sem query string, garantir que a UI dos filtros esteja limpa
            if (!window.location.search) {
                resetFiltersUI();
            }

            // Ao clicar em Limpar, limpar a UI e submeter o formulário vazio para atualizar a listagem
            if (clearButton) {
                clearButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    resetFiltersUI();
                    const url = this.getAttribute('href');
                    // Substitui a entrada no histórico para evitar back que traga filtros antigos
                    window.location.replace(url);
                });
            }

            // Sistema de Toggle de Filtros com Filtros Ativos
            const toggleFiltersBtn = document.getElementById('toggle-filters-btn');
            const filtersContainer = document.getElementById('filters-container');
            const activeFiltersSummary = document.getElementById('active-filters-summary');
            const activeFiltersList = document.getElementById('active-filters-list');
            const filterToggleText = document.getElementById('filter-toggle-text');
            const filterIconShow = document.getElementById('filter-icon-show');
            const filterIconHide = document.getElementById('filter-icon-hide');

            // Mapeamento de nomes de filtros para labels amigáveis
            const filterLabels = {
                'referencia': 'Referência',
                'descricao': 'Descrição',
                'concluido': 'Status de Conclusão',
                'status_concluido': 'Status de Conclusão',
                'incluir_excluidos': 'Incluir Excluídos',
                'marca_id': 'Marca',
                'tecido_id': 'Tecido',
                'estilista_id': 'Estilista',
                'grupo_id': 'Grupo',
                'status_id': 'Status',
                'direcionamento_comercial_id': 'Direcionamento Comercial',
                'localizacao_id': 'Localização',
                'localizacao_planejamento_id': 'Localização Planejamento',
                'situacao_id': 'Situação',
                'data_inicio': 'Data Cadastro (De)',
                'data_fim': 'Data Cadastro (Até)',
                'data_prevista_inicio': 'Data Prev. Produção (De)',
                'data_prevista_fim': 'Data Prev. Produção (Até)',
                'data_prevista_faccao_inicio': 'Data Prev. Facção (De)',
                'data_prevista_faccao_fim': 'Data Prev. Facção (Até)'
            };

            // Função para obter o texto de um select pelo valor (suporta arrays)
            function getSelectText(selectId, value) {
                const select = document.getElementById(selectId);
                if (!select) return Array.isArray(value) ? value.join(', ') : value;

                // Se for array, mapear cada valor para o texto correspondente
                if (Array.isArray(value)) {
                    return value.map(v => {
                        const option = select.querySelector(`option[value="${v}"]`);
                        return option ? option.textContent.trim() : v;
                    }).join(', ');
                }

                const option = select.querySelector(`option[value="${value}"]`);
                return option ? option.textContent.trim() : value;
            }

            // Função para atualizar a lista de filtros ativos
            function updateActiveFilters() {
                const filters = @json($filters ?? []);
                activeFiltersList.innerHTML = '';

                let hasActiveFilters = false;

                Object.keys(filters).forEach(key => {
                    const value = filters[key];
                    // Verifica se tem valor (string não vazia ou array não vazio)
                    const hasValue = Array.isArray(value) ? value.length > 0 : (value && value !== '');

                    if (hasValue && key !== 'page') {
                        hasActiveFilters = true;
                        let displayValue = value;

                        // Formatar valor baseado no tipo de filtro
                        if (key === 'concluido') {
                            displayValue = value === '1' ? 'Concluídos' : 'Não Concluídos';
                        } else if (key === 'status_concluido') {
                            const statusMap = {
                                'todos_em_processo': '🔄 Todos em Processo',
                                'concluido': '✅ Concluídos',
                                'nao_concluido': '⏳ Não Concluídos',
                                'sem_movimentacao': '📋 Sem Movimentação'
                            };
                            displayValue = statusMap[value] || value;
                        } else if (key === 'incluir_excluidos') {
                            displayValue = 'Sim';
                        } else if (key.endsWith('_id')) {
                            displayValue = getSelectText(key, value);
                        } else if (key.includes('data_')) {
                            // Formatar datas
                            const date = new Date(value + 'T00:00:00');
                            displayValue = date.toLocaleDateString('pt-BR');
                        } else if (Array.isArray(value)) {
                            displayValue = value.join(', ');
                        }

                        const badge = document.createElement('span');
                        badge.className = 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 shadow-sm';
                        badge.innerHTML = `
                            <span class="font-bold mr-1 text-indigo-900 dark:text-indigo-200">${filterLabels[key] || key}:</span>
                            <span>${displayValue}</span>
                        `;
                        activeFiltersList.appendChild(badge);
                    }
                });

                return hasActiveFilters;
            }

            // Função para alternar visibilidade dos filtros
            const initialFiltersVisible = @json($filtersVisible ?? true);

            async function salvarPreferenciaFiltrosVisiveis(visible) {
                try {
                    await fetch('{{ route("ui.filters-visibility") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            page_type: 'produtos',
                            filters_visible: !!visible
                        })
                    });
                } catch (e) {
                }
            }

            function toggleFilters() {
                const isHidden = filtersContainer.classList.contains('hidden');

                if (isHidden) {
                    // Mostrar filtros
                    filtersContainer.classList.remove('hidden');
                    activeFiltersSummary.classList.add('hidden');
                    filterToggleText.textContent = 'Ocultar Filtros';
                    filterIconShow.classList.remove('hidden');
                    filterIconHide.classList.add('hidden');
                    salvarPreferenciaFiltrosVisiveis(true);
                } else {
                    // Ocultar filtros
                    filtersContainer.classList.add('hidden');
                    const hasFilters = updateActiveFilters();
                    if (hasFilters) {
                        activeFiltersSummary.classList.remove('hidden');
                    }
                    filterToggleText.textContent = 'Mostrar Filtros';
                    filterIconShow.classList.add('hidden');
                    filterIconHide.classList.remove('hidden');
                    salvarPreferenciaFiltrosVisiveis(false);
                }
            }

            // Event listener para o botão de toggle
            if (toggleFiltersBtn) {
                toggleFiltersBtn.addEventListener('click', toggleFilters);
            }

            // Restaurar estado dos filtros pelo valor salvo para o usuário
            if (initialFiltersVisible === false) {
                // Ocultar filtros na carga da página
                filtersContainer.classList.add('hidden');
                const hasFilters = updateActiveFilters();
                if (hasFilters) {
                    activeFiltersSummary.classList.remove('hidden');
                }
                filterToggleText.textContent = 'Mostrar Filtros';
                filterIconShow.classList.add('hidden');
                filterIconHide.classList.remove('hidden');
            }

            // Atualizar filtros ativos na carga inicial
            updateActiveFilters();

            // Função para destacar campos de filtro com valores preenchidos
            function highlightFilledFilters() {
                const filterForm = document.getElementById('filter-form');
                if (!filterForm) return;

                // Selecionar todos os inputs de texto e data
                const textInputs = filterForm.querySelectorAll('input[type="text"], input[type="date"]');
                textInputs.forEach(field => {
                    const hasValue = field.value && field.value.trim() !== '';
                    if (hasValue) {
                        field.classList.add('bg-yellow-100', 'border-yellow-400');
                        field.classList.remove('bg-white');
                    } else {
                        field.classList.remove('bg-yellow-100', 'border-yellow-400');
                    }
                });

                // Selecionar todos os selects (simples e múltiplos)
                const selects = filterForm.querySelectorAll('select');
                selects.forEach(select => {
                    let hasValue = false;

                    if (select.multiple) {
                        // Select múltiplo - verificar se tem alguma opção selecionada
                        hasValue = select.selectedOptions.length > 0;
                    } else {
                        // Select simples - verificar se o valor não é vazio
                        hasValue = select.value !== '' && select.value !== null;
                    }

                    // Verificar se usa Select2
                    const select2Container = select.nextElementSibling;
                    if (select2Container && select2Container.classList.contains('select2-container')) {
                        // Aplicar estilo ao container do Select2
                        const selection = select2Container.querySelector('.select2-selection');
                        if (selection) {
                            if (hasValue) {
                                selection.style.backgroundColor = '#fef9c3'; // yellow-100
                                selection.style.borderColor = '#facc15'; // yellow-400
                            } else {
                                selection.style.backgroundColor = '';
                                selection.style.borderColor = '';
                            }
                        }
                    } else {
                        // Select normal sem Select2
                        if (hasValue) {
                            select.classList.add('bg-yellow-100', 'border-yellow-400');
                            select.classList.remove('bg-white');
                        } else {
                            select.classList.remove('bg-yellow-100', 'border-yellow-400');
                        }
                    }
                });

                // Destacar checkboxes marcados
                const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    const label = checkbox.closest('div');
                    if (checkbox.checked && label) {
                        label.classList.add('bg-yellow-100', 'rounded', 'px-2', 'py-1');
                    } else if (label) {
                        label.classList.remove('bg-yellow-100', 'rounded', 'px-2', 'py-1');
                    }
                });
            }

            // Chamar função de destaque na inicialização (com delay para Select2 carregar)
            setTimeout(highlightFilledFilters, 100);

            // Atualizar destaque quando campos mudarem
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.addEventListener('change', highlightFilledFilters);
                filterForm.addEventListener('input', highlightFilledFilters);
            }

            // Escutar eventos do Select2
            $(document).on('select2:select select2:unselect select2:clear', function() {
                highlightFilledFilters();
            });

        });
    </script>

</x-app-layout>
