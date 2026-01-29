<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Referência -->
    <div>
        <label for="referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referência</label>
        <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $produto->referencia) }}" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
    </div>

    <!-- Descrição -->
    <div>
        <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
        <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $produto->descricao) }}" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
    </div>

    <!-- Data de Cadastro -->
    <div>
        <label for="data_cadastro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Cadastro</label>
        <input type="date" name="data_cadastro" id="data_cadastro" value="{{ old('data_cadastro', $produto->data_cadastro ? $produto->data_cadastro->format('Y-m-d') : date('Y-m-d')) }}" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
    </div>

    <!-- Data Prevista para Produção -->
    <div>
        <label for="data_prevista_producao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Prevista para Produção</label>
        <input type="date" name="data_prevista_producao" id="data_prevista_producao" value="{{ old('data_prevista_producao', $produto->data_prevista_producao ? $produto->data_prevista_producao->format('Y-m-d') : '') }}" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    </div>

    <!-- Marca -->
    <div>
        <label for="marca_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
        <select name="marca_id" id="marca_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
            <option value="">Selecione uma marca</option>
            @foreach($marcas as $marca)
            <option value="{{ $marca->id }}" {{ old('marca_id', $produto->marca_id) == $marca->id ? 'selected' : '' }} class="text-gray-700">
                {{ $marca->nome_marca }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Quantidade -->
    <div>
        <label for="quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade</label>
        <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', $produto->quantidade) }}" min="0" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    </div>

    <!-- Estilista -->
    <div>
        <label for="estilista_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estilista</label>
        <select name="estilista_id" id="estilista_id" class="estilista-select block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
            <option value="">Selecione um estilista</option>
            @foreach($estilistas as $estilista)
            <option value="{{ $estilista->id }}" {{ old('estilista_id', $produto->estilista_id) == $estilista->id ? 'selected' : '' }} class="text-gray-700">
                {{ $estilista->nome_estilista }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Grupo -->
    <div>
        <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo</label>
        <select name="grupo_id" id="grupo_id" class="grupo-select block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
            <option value="">Selecione um grupo</option>
            @foreach($grupos as $grupo)
            <option value="{{ $grupo->id }}" {{ old('grupo_id', $produto->grupo_id) == $grupo->id ? 'selected' : '' }} class="text-gray-700">
                {{ $grupo->descricao }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Preço Atacado -->
    <div>
        <label for="preco_atacado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Atacado (R$)</label>
        <input type="number" name="preco_atacado" id="preco_atacado" value="{{ old('preco_atacado', $produto->preco_atacado) }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    </div>

    <!-- Preço Varejo -->
    <div>
        <label for="preco_varejo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Varejo (R$)</label>
        <input type="number" name="preco_varejo" id="preco_varejo" value="{{ old('preco_varejo', $produto->preco_varejo) }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    </div>

    <!-- Status -->
    <div>
        <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
        <select name="status_id" id="status_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700 bg-white" required>
            <option value="">Selecione um status</option>
            @foreach($statuses as $status)
            <option value="{{ $status->id }}" {{ old('status_id', $produto->status_id) == $status->id ? 'selected' : '' }} class="text-gray-700">
                {{ $status->descricao }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Direcionamento Comercial -->
    <div>
        <label for="direcionamento_comercial_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Direcionamento Comercial</label>
        <select name="direcionamento_comercial_id" id="direcionamento_comercial_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700 bg-white">
            <option value="">Selecione um direcionamento</option>
            @foreach($direcionamentosComerciais as $direcionamento)
            <option value="{{ $direcionamento->id }}" {{ old('direcionamento_comercial_id', $produto->direcionamento_comercial_id) == $direcionamento->id ? 'selected' : '' }} class="text-gray-700">
                {{ $direcionamento->descricao }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Foto Principal -->
    @include('produtos.partials.form-images')

    <!-- Número de Reprogramação (só aparece se for reprogramação) -->
    @if($produto->isReprogramacao())
    <div>
        <label for="numero_reprogramacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Número de Reprogramação
            <span class="text-xs text-gray-500 dark:text-gray-400">(para sistemas antigos)</span>
        </label>
        <input type="number" name="numero_reprogramacao" id="numero_reprogramacao" value="{{ old('numero_reprogramacao', $produto->numero_reprogramacao) }}" min="1" max="99" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <p class="text-xs text-gray-500 mt-1">
            Campo para ajuste manual quando reprogramações foram iniciadas em sistemas antigos
        </p>
    </div>
    @endif

    <!-- Anexos Flexíveis -->
    <div class="col-span-1 md:col-span-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-md font-medium text-gray-700">Anexos</h3>
            <button type="button" onclick="document.getElementById('modal-anexo').classList.remove('hidden')" class="btn-ghost-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar Anexo
            </button>
        </div>

        @include('produtos.partials.form-attachments')

        <p class="text-sm text-gray-500 dark:text-gray-400">Você pode adicionar múltiplos anexos com descrições clicando no botão "Adicionar Anexo".</p>
    </div>
</div>
