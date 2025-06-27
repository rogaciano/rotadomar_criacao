<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produtos Ativos por Localização') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtro de Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Filtrar por Status</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($todosStatus as $status)
                                <a href="{{ route('consultas.produtos-ativos-por-localizacao', ['status_id' => $status->id]) }}" 
                                   class="px-4 py-2 rounded-md text-sm font-medium {{ $statusSelecionado && $statusSelecionado->id == $status->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    {{ $status->descricao }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Resumo</h3>
                        <p class="text-gray-600">Total de produtos com status <span class="font-medium">{{ $statusSelecionado ? $statusSelecionado->descricao : 'Desconhecido' }}</span>: <span class="font-bold">{{ $totalProdutos }}</span></p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Localização
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total de Produtos
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Percentual
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($produtosPorLocalizacao as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item['nome_localizacao'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item['total'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($totalProdutos > 0)
                                                {{ number_format(($item['total'] / $totalProdutos) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $totalProdutos > 0 ? ($item['total'] / $totalProdutos) * 100 : 0 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
