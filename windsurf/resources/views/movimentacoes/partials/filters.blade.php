<div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
    <div class="p-6">
        <!-- Cabeçalho dos Filtros com Toggle -->
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Filtros</h3>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <button type="submit" form="filters-form" class="btn-ghost-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filtrar
                </button>
                <a href="{{ route('movimentacoes.filtro.status-dias', ['limpar_filtros' => 1]) }}" id="btn-clear-filters" class="btn-ghost-secondary">
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
            <form action="{{ route('movimentacoes.filtro.status-dias') }}" method="GET" id="filters-form" autocomplete="off" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                    <input type="text" name="referencia" id="referencia" value="{{ request('referencia') }}" autocomplete="off" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
                </div>

                <div class="md:col-span-2">
                    <label for="produto" class="block text-sm font-medium text-gray-700 mb-1">Produto</label>
                    <input type="text" name="produto" id="produto" value="{{ request('produto') }}" autocomplete="off" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência ou descrição do produto">
                </div>

                <div>
                    <label for="tipo_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="tipo_id" id="tipo_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todos</option>
                        @foreach($tipos ?? [] as $tipo)
                            <option value="{{ $tipo->id }}" {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->descricao }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="grupo_produto_id" class="block text-sm font-medium text-gray-700 mb-1">Grupo de Produto</label>
                    <select name="grupo_produto_id[]" id="grupo_produto_id" class="select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                        @foreach($grupoProdutos ?? [] as $grupo)
                            <option value="{{ $grupo->id }}" {{ in_array($grupo->id, (array)request('grupo_produto_id', [])) ? 'selected' : '' }}>
                                {{ $grupo->descricao }}{{ !$grupo->ativo ? ' (Inativo)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="situacao_id" class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                    <select name="situacao_id[]" id="situacao_id" class="select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                        @foreach($situacoes ?? [] as $situacao)
                            <option value="{{ $situacao->id }}" {{ in_array($situacao->id, (array)request('situacao_id', [])) ? 'selected' : '' }}>{{ $situacao->descricao }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                    <select name="localizacao_id[]" id="localizacao_id" class="select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                        @foreach($localizacoes ?? [] as $localizacao)
                            <option value="{{ $localizacao->id }}" {{ in_array($localizacao->id, (array)request('localizacao_id', [])) ? 'selected' : '' }}>
                                {{ $localizacao->nome_localizacao }}{{ !$localizacao->ativo ? ' (Inativa)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="comprometido" class="block text-sm font-medium text-gray-700 mb-1">Comprometido</label>
                    <select name="comprometido" id="comprometido" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todos</option>
                        <option value="1" {{ request('comprometido') == '1' ? 'selected' : '' }}>Sim</option>
                        <option value="0" {{ request('comprometido') == '0' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>

                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Entrada (Início)</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" autocomplete="off" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Entrada (Fim)</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" autocomplete="off" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="concluido" class="block text-sm font-medium text-gray-700 mb-1">Status Conclusão</label>
                    <select name="concluido" id="concluido" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" {{ request('concluido') === null ? 'selected' : '' }}>Todos</option>
                        <option value="1" {{ request('concluido') === '1' ? 'selected' : '' }}>Concluídas</option>
                        <option value="0" {{ request('concluido') === '0' ? 'selected' : '' }}>Não Concluídas</option>
                    </select>
                </div>

                <div>
                    <label for="status_dias" class="block text-sm font-medium text-gray-700 mb-1">Status de Dias</label>
                    <select name="status_dias" id="status_dias" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" {{ request('status_dias') === null ? 'selected' : '' }}>Todos</option>
                        <option value="atrasados" {{ request('status_dias') === 'atrasados' ? 'selected' : '' }}>Atrasados</option>
                        <option value="em_dia" {{ request('status_dias') === 'em_dia' ? 'selected' : '' }}>Em Dia</option>
                    </select>
                </div>

                <div>
                    <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <select name="marca_id" id="marca_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todas</option>
                        @foreach($marcas ?? [] as $marca)
                            <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nome_marca }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_id" id="status_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todos</option>
                        @foreach($status ?? [] as $st)
                            <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>{{ $st->descricao }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tecido_id" class="block text-sm font-medium text-gray-700 mb-1">Tecido</label>
                    <select name="tecido_id[]" id="tecido_id" class="select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                        @foreach($tecidos ?? [] as $tecido)
                            <option value="{{ $tecido->id }}" {{ in_array($tecido->id, (array)request('tecido_id', [])) ? 'selected' : '' }}>{{ $tecido->descricao }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="direcionamento_comercial_id" class="block text-sm font-medium text-gray-700 mb-1">Direcionamento Comercial</label>
                    <select name="direcionamento_comercial_id[]" id="direcionamento_comercial_id" class="select2-multi w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                        @foreach($direcionamentosComerciais ?? [] as $direcionamento)
                            <option value="{{ $direcionamento->id }}" {{ in_array($direcionamento->id, (array)request('direcionamento_comercial_id', [])) ? 'selected' : '' }}>{{ $direcionamento->descricao }}</option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>
    </div>
</div>
