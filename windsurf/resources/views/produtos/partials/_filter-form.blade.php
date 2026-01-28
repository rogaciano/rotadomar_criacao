{{-- Formulário de Filtros --}}
<div id="filters-container" class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800">
    <form id="filter-form" action="{{ route('produtos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Referência --}}
        <div class="md:col-span-1">
            <label for="referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referência</label>
            <input type="text" name="referencia" id="referencia" value="{{ $filters['referencia'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
        </div>

        {{-- Descrição --}}
        <div>
            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
            <input type="text" name="descricao" id="descricao" value="{{ $filters['descricao'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a descrição do produto">
        </div>

        {{-- Marca --}}
        <div>
            <label for="marca_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
            <select name="marca_id" id="marca_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todas</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" {{ ($filters['marca_id'] ?? '') == $marca->id ? 'selected' : '' }}>
                        {{ $marca->nome_marca }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tecido --}}
        <div>
            <label for="tecido_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tecido</label>
            <select name="tecido_id" id="tecido_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($tecidos as $tecido)
                    <option value="{{ $tecido->id }}" {{ ($filters['tecido_id'] ?? '') == $tecido->id ? 'selected' : '' }}>
                        {{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Estilista --}}
        <div>
            <label for="estilista_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estilista</label>
            <select name="estilista_id" id="estilista_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($estilistas as $estilista)
                    <option value="{{ $estilista->id }}" {{ ($filters['estilista_id'] ?? '') == $estilista->id ? 'selected' : '' }}>
                        {{ $estilista->nome_estilista }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Grupo --}}
        <div>
            <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo</label>
            <select name="grupo_id" id="grupo_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ ($filters['grupo_id'] ?? '') == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div>
            <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status_id" id="status_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ ($filters['status_id'] ?? '') == $status->id ? 'selected' : '' }}>
                        {{ $status->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Direcionamento Comercial --}}
        <div>
            <label for="direcionamento_comercial_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Direcionamento Comercial</label>
            <select name="direcionamento_comercial_id" id="direcionamento_comercial_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($direcionamentosComerciais as $direcionamento)
                    <option value="{{ $direcionamento->id }}" {{ ($filters['direcionamento_comercial_id'] ?? '') == $direcionamento->id ? 'selected' : '' }}>
                        {{ $direcionamento->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Localização (Múltipla) --}}
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

        {{-- Situação (Múltipla) --}}
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

        {{-- Localização Planejamento (Múltipla) --}}
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

        {{-- Status de Conclusão --}}
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

        {{-- Incluir Excluídos --}}
        <div>
            <div class="flex items-center">
                <input type="checkbox" name="incluir_excluidos" id="incluir_excluidos" value="1" {{ isset($filters['incluir_excluidos']) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                <label for="incluir_excluidos" class="ml-2 block text-sm text-gray-700">Incluir excluídos</label>
            </div>
        </div>

        {{-- Seção de Filtros por Data --}}
        <div class="md:col-span-2 lg:col-span-4 border-t pt-4 mt-2">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Filtro por Datas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">

                {{-- Data de Cadastro --}}
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

                {{-- Data Prevista Produção --}}
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

                {{-- Data Prevista para Facção --}}
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
