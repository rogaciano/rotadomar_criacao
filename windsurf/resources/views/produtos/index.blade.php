<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('produtos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Produto
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                        <form action="{{ route('produtos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ request('referencia') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
                            </div>

                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ request('descricao') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a descrição do produto">
                            </div>

                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <select name="marca_id" id="marca_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ (request('marca_id') == $marca->id || (request('marca') == $marca->nome_marca)) ? 'selected' : '' }}>
                                            {{ $marca->nome_marca }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('marca'))
                                    <input type="hidden" name="marca" value="{{ request('marca') }}">
                                @endif
                            </div>

                            <div>
                                <label for="tecido_id" class="block text-sm font-medium text-gray-700 mb-1">Tecido</label>
                                <select name="tecido_id" id="tecido_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($tecidos as $tecido)
                                        <option value="{{ $tecido->id }}" {{ request('tecido_id') == $tecido->id ? 'selected' : '' }}>
                                            {{ $tecido->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="estilista_id" class="block text-sm font-medium text-gray-700 mb-1">Estilista</label>
                                <select name="estilista_id" id="estilista_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($estilistas as $estilista)
                                        <option value="{{ $estilista->id }}" {{ (request('estilista_id') == $estilista->id || (request('estilista') == $estilista->nome_estilista)) ? 'selected' : '' }}>
                                            {{ $estilista->nome_estilista }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('estilista'))
                                    <input type="hidden" name="estilista" value="{{ request('estilista') }}">
                                @endif
                            </div>

                            <div>
                                <label for="grupo_id" class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                                <select name="grupo_id" id="grupo_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ (request('grupo_id') == $grupo->id || (request('grupo') == $grupo->descricao)) ? 'selected' : '' }}>
                                            {{ $grupo->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('grupo'))
                                    <input type="hidden" name="grupo" value="{{ request('grupo') }}">
                                @endif
                            </div>

                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_id" id="status_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ (request('status_id') == $status->id || (request('status') == $status->descricao)) ? 'selected' : '' }}>
                                            {{ $status->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                            </div>

                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                                <select name="localizacao_id" id="localizacao_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ (request('localizacao_id') == $localizacao->id || (request('localizacao') == $localizacao->nome_localizacao)) ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('localizacao'))
                                    <input type="hidden" name="localizacao" value="{{ request('localizacao') }}">
                                @endif
                            </div>

                            <div class="flex items-center mt-6">
                                <input type="checkbox" name="incluir_excluidos" id="incluir_excluidos" value="1" {{ request('incluir_excluidos') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                <label for="incluir_excluidos" class="ml-2 block text-sm text-gray-700">Incluir excluídos</label>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('produtos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

                    <!-- Tabela de Produtos -->
                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Referência
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Descrição
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Marca
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grupo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Localização
                                    </th>
                                    <th scope="col" class="sticky right-0 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50 shadow-md z-10">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($produtos as $produto)
                                    <tr class="{{ $produto->trashed() ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $produto->referencia }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $produto->descricao }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($produto->marca && $produto->marca->logo_path)
                                                <img src="{{ asset('storage/' . $produto->marca->logo_path) }}" alt="{{ $produto->marca->nome_marca }}" class="h-6 w-auto object-contain" title="{{ $produto->marca->nome_marca }}">
                                            @else
                                                {{ $produto->marca->nome_marca ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $produto->grupoProduto->descricao ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($produto->localizacao_atual)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $produto->localizacao_atual->nome_localizacao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Não localizado</span>
                                            @endif
                                        </td>
                                        <td class="sticky right-0 px-6 py-4 whitespace-nowrap text-right text-sm font-medium bg-white shadow-md z-10">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('produtos.show', $produto->id) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                @if(!$produto->trashed())
                                                    <a href="{{ route('produtos.edit', $produto->id) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja restaurar este produto?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100">
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
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
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
            const toggleButton = document.getElementById('toggle-filters');
            const filterContainer = document.getElementById('filter-container');

            toggleButton.addEventListener('click', function() {
                if (filterContainer.style.display === 'none') {
                    filterContainer.style.display = 'block';
                    toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
                } else {
                    filterContainer.style.display = 'none';
                    toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>';
                }
            });
        });
    </script>
</x-app-layout>
