<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movimentações') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('movimentacoes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Movimentação
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                        <form action="{{ route('movimentacoes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ request('referencia') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
                            </div>

                            <div class="md:col-span-2">
                                <label for="produto_id" class="block text-sm font-medium text-gray-700 mb-1">Produto</label>
                                <select name="produto_id" id="produto_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($produtos ?? [] as $produto)
                                        <option value="{{ $produto->id }}" {{ request('produto_id') == $produto->id ? 'selected' : '' }}>{{ $produto->referencia }} - {{ $produto->descricao }}</option>
                                    @endforeach
                                </select>
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
                                <label for="situacao_id" class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                                <select name="situacao_id" id="situacao_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($situacoes ?? [] as $situacao)
                                        <option value="{{ $situacao->id }}" {{ request('situacao_id') == $situacao->id ? 'selected' : '' }}>{{ $situacao->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                                <select name="localizacao_id" id="localizacao_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($localizacoes ?? [] as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ request('localizacao_id') == $localizacao->id ? 'selected' : '' }}>{{ $localizacao->nome_localizacao }}</option>
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
                                <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Entrada (Fim)</label>
                                <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('movimentacoes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

                    <!-- Tabela de Movimentações -->
                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Localização
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Situação
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data Entrada
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data Saída
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data Devolução
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Comprometido
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Observação
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Criado em
                                    </th>
                                    <th scope="col" class="sticky right-0 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50 shadow-md z-10">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($movimentacoes as $movimentacao)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            @if($movimentacao->produto_id)
                                                {{ $movimentacao->produto->referencia }} - {{ $movimentacao->produto->descricao }}
                                            @else
                                                <span class="text-red-500">Produto não encontrado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($movimentacao->situacao)
                                                <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $movimentacao->situacao->descricao ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->data_devolucao ? $movimentacao->data_devolucao->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->comprometido ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $movimentacao->comprometido ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->observacao ?: 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movimentacao->created_at ? $movimentacao->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="sticky right-0 px-6 py-4 whitespace-nowrap text-right text-sm font-medium bg-white shadow-md z-10">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('movimentacoes.show', $movimentacao) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('movimentacoes.edit', $movimentacao) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('movimentacoes.destroy', $movimentacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                            Nenhuma movimentação encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $movimentacoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-filters');
            const filterContainer = document.getElementById('filter-container');

            // Inicialmente mostrar os filtros
            // filterContainer.style.display = 'block';

            toggleButton.addEventListener('click', function() {
                if (filterContainer.style.display === 'none') {
                    filterContainer.style.display = 'block';
                } else {
                    filterContainer.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
