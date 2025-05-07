<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards estatísticos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                <!-- Card Tecidos -->
                <div class="bg-purple-600 rounded-lg shadow-md overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-center mb-2">
                            <div class="rounded-full bg-purple-500/50 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-center text-white font-medium">Total de Tecidos</h3>
                        <p class="text-center text-4xl font-bold text-white my-2">{{ \App\Models\Tecido::count() }}</p>
                        <p class="text-center text-white text-sm">Cadastrados no sistema</p>
                    </div>
                    <div class="bg-purple-700 p-3">
                        <a href="{{ route('tecidos.create') }}" class="flex justify-center items-center text-white hover:text-purple-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Novo Tecido</span>
                        </a>
                    </div>
                </div>

                <!-- Card Estilistas -->
                <div class="bg-blue-500 rounded-lg shadow-md overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-center mb-2">
                            <div class="rounded-full bg-blue-400/50 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-center text-white font-medium">Total de Estilistas</h3>
                        <p class="text-center text-4xl font-bold text-white my-2">{{ \App\Models\Estilista::count() }}</p>
                        <p class="text-center text-white text-sm">Cadastrados no sistema</p>
                    </div>
                    <div class="bg-blue-600 p-3">
                        <a href="{{ route('estilistas.create') }}" class="flex justify-center items-center text-white hover:text-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Novo Estilista</span>
                        </a>
                    </div>
                </div>

                <!-- Card Marcas -->
                <div class="bg-green-500 rounded-lg shadow-md overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-center mb-2">
                            <div class="rounded-full bg-green-400/50 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-center text-white font-medium">Total de Marcas</h3>
                        <p class="text-center text-4xl font-bold text-white my-2">{{ \App\Models\Marca::count() }}</p>
                        <p class="text-center text-white text-sm">Cadastradas no sistema</p>
                    </div>
                    <div class="bg-green-600 p-3">
                        <a href="{{ route('marcas.create') }}" class="flex justify-center items-center text-white hover:text-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Nova Marca</span>
                        </a>
                    </div>
                </div>

                <!-- Card Grupo de Produtos -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white">Grupos de Produtos</h3>
                                <p class="text-3xl font-bold text-white mt-2">{{ $totalGrupoProdutos }}</p>
                                <p class="text-yellow-100 mt-1">Total cadastrado</p>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('grupo_produtos.create') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 rounded-lg font-semibold transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Adicionar Grupo
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Produtos -->
                <div class="bg-orange-500 rounded-lg shadow-md overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-center mb-2">
                            <div class="rounded-full bg-orange-400/50 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-center text-white font-medium">Total de Produtos</h3>
                        <p class="text-center text-4xl font-bold text-white my-2">{{ \App\Models\Produto::count() }}</p>
                        <p class="text-center text-white text-sm">Cadastrados no sistema</p>
                    </div>
                    <div class="bg-orange-600 p-3">
                        <a href="{{ route('produtos.create') }}" class="flex justify-center items-center text-white hover:text-orange-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Novo Produto</span>
                        </a>
                    </div>
                </div>

                <!-- Card Movimentações -->
                <div class="bg-gray-500 rounded-lg shadow-md overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-center mb-2">
                            <div class="rounded-full bg-gray-400/50 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-center text-white font-medium">Movimentações</h3>
                        <p class="text-center text-4xl font-bold text-white my-2">{{ \App\Models\Movimentacao::count() }}</p>
                        <p class="text-center text-white text-sm">Registradas no sistema</p>
                    </div>
                    <div class="bg-gray-600 p-3">
                        <a href="{{ route('movimentacoes.create') }}" class="flex justify-center items-center text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Nova Movimentação</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gráficos e tabelas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Top Produtos -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Top Produtos
                        </h3>
                    </div>
                    <div class="p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referência</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse(\App\Models\Produto::orderBy('created_at', 'desc')->take(5)->get() as $produto)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $produto->referencia }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $produto->descricao }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-900">{{ $produto->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">Nenhum produto cadastrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Últimas Movimentações -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Últimas Movimentações
                        </h3>
                    </div>
                    <div class="p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse(\App\Models\Movimentacao::with('produto')->orderBy('created_at', 'desc')->take(5)->get() as $movimentacao)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movimentacao->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $movimentacao->produto ? $movimentacao->produto->descricao : 'Produto não encontrado' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right {{ $movimentacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">Nenhuma movimentação registrada</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico de Movimentações -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Movimentações por Mês</h3>
                    </div>
                    <div class="p-4 h-64">
                        <div class="text-center text-gray-500 h-full flex items-center justify-center">
                            <p>Gráfico de movimentações será exibido aqui</p>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Valor Total -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Valor Total do Estoque</h3>
                    </div>
                    <div class="p-4 h-64">
                        <div class="text-center text-gray-500 h-full flex items-center justify-center">
                            <p>Gráfico de valor total será exibido aqui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
