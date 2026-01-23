<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tecidos') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-3 mb-6">
                @if(auth()->user() && auth()->user()->canCreate('tecidos'))
                <a href="{{ route('tecidos.create') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Tecido
                </a>
                @endif
                @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                <a href="{{ route('tecidos.atualizar-todos-estoques') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Atualizar Estoques
                </a>
                @endif
                @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                <a href="{{ route('tecidos.importar-estoque-form') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Importar Estoque
                </a>
                @endif
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Filtros -->
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
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('tecidos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Tabela de Tecidos -->
                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Descrição
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Referência
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Data de Cadastro
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Estoque
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Produtos
                                    </th>
                                    <th scope="col" class="sticky right-0 px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-slate-800 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tecidos as $tecido)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $tecido->descricao }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $tecido->referencia ?: 'Não informada' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $tecido->created_at ? $tecido->created_at->format('d/m/Y') : 'Sem referência' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($tecido->quantidade_estoque)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                    {{ number_format($tecido->quantidade_estoque, 0, ',', '.') }}
                                                </span>
                                                <span class="text-xs text-gray-400 ml-1" title="Última atualização">
                                                    {{ $tecido->ultima_consulta_estoque ? $tecido->ultima_consulta_estoque->format('d/m/Y H:i') : '' }}
                                                </span>
                                            @else
                                                @if($tecido->referencia)
                                                    <span class="text-xs text-gray-400">Não consultado</span>
                                                @else
                                                    <span class="text-xs text-gray-400">Sem referência</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tecido->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' }}">
                                                {{ $tecido->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                            {{ $tecido->produtos->count() }}
                                        </td>
                                        <td class="sticky right-0 px-6 py-4 whitespace-nowrap text-right text-sm font-medium bg-white dark:bg-slate-900 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                                            <div class="flex justify-end space-x-2">
                                                @if(auth()->user() && auth()->user()->canRead('tecidos'))
                                                <a href="{{ route('tecidos.show', $tecido) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 p-1 rounded hover:bg-blue-100 dark:hover:bg-blue-900/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                                                <a href="{{ route('tecidos.edit', $tecido) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 p-1 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                @if(auth()->user() && auth()->user()->canDelete('tecidos'))
                                                <form action="{{ route('tecidos.destroy', $tecido) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este tecido?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 p-1 rounded hover:bg-red-100 dark:hover:bg-red-900/50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nenhum tecido encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $tecidos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
