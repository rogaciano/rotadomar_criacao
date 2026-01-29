
<div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
    <form action="{{ route('tecidos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por descrição</label>
            <input type="text" name="descricao" id="descricao" value="{{ request('descricao') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" placeholder="Digite a descrição do tecido">
        </div>
        <div class="md:col-span-2">
            <label for="referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por referência</label>
            <input type="text" name="referencia" id="referencia" value="{{ request('referencia') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" placeholder="Digite a referência do tecido">
        </div>

        <div class="md:col-span-2 border-t pt-3 mt-2 dark:border-gray-700">
            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Filtro por Data de Cadastro</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fim</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>

        <div class="md:col-span-2 border-t pt-3 mt-2 dark:border-gray-700">
            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Filtro por Data de Atualização do Estoque</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="estoque_data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Início</label>
                    <input type="date" name="estoque_data_inicio" id="estoque_data_inicio" value="{{ request('estoque_data_inicio') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="estoque_data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fim</label>
                    <input type="date" name="estoque_data_fim" id="estoque_data_fim" value="{{ request('estoque_data_fim') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>
        <div class="md:col-span-4 border-t pt-3 mt-2 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="ativo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="ativo" id="ativo" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
                <div class="md:col-span-3"></div>
            </div>
        </div>
        <div class="md:col-span-4 flex justify-end space-x-2">
            <button type="submit" class="btn-ghost-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filtrar
            </button>
            <a href="{{ route('tecidos.index') }}" class="btn-ghost-secondary">
                Limpar
            </a>
        </div>
    </form>
</div>
