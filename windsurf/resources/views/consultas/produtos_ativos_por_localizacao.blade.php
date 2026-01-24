<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Produtos Ativos por Localização') }}
        </h2>
    </x-slot>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-center mb-4">
                <svg class="animate-spin h-8 w-8 text-indigo-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-lg font-medium">Processando dados...</span>
            </div>
            <p class="text-gray-600 text-center">Esta consulta pode levar alguns instantes. Por favor, aguarde.</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cabeçalho e Filtros -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-4 px-2">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Produtos por Localização</h2>
                        <p class="text-sm text-gray-500 mt-1">Visão geral da distribuição de produtos no fluxo produtivo.</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between gap-8">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Geral</span>
                            <span class="text-2xl font-black text-indigo-600 tracking-tighter">{{ number_format($totalProdutos, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-10 w-px bg-gray-100"></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Atual</span>
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100 uppercase tracking-wide">
                                {{ $statusSelecionado ? $statusSelecionado->descricao : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filtro de Status -->
                <div class="mt-4 px-2">
                    <div class="flex items-center gap-2 mb-3 overflow-x-auto pb-2 scrollbar-hide">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Status:</span>
                        @foreach($todosStatus as $status)
                            <a href="{{ route('consultas.produtos-ativos-por-localizacao', ['status_id' => $status->id]) }}" 
                               class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $statusSelecionado && $statusSelecionado->id == $status->id ? 'bg-indigo-600 text-white shadow-md scale-105' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                                {{ $status->descricao }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <!-- Vista Desktop (Tabela) -->
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Localização</th>
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Qtd. Produtos</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Distribuição</th>
                                <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($produtosPorLocalizacao as $item)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-900">{{ $item['nome_localizacao'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="text-base font-black text-gray-800">{{ number_format($item['total'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php
                                            $percentual = $totalProdutos > 0 ? ($item['total'] / $totalProdutos) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            <div class="flex-grow bg-gray-100 rounded-full h-2.5">
                                                <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentual }}%"></div>
                                            </div>
                                            <span class="text-xs font-black text-indigo-600 w-10 text-right">{{ number_format($percentual, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('produtos.index', ['localizacao' => $item['nome_localizacao']]) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all">
                                            Ver Itens
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vista Mobile (Cards) -->
                <div class="md:hidden space-y-4 p-4 bg-gray-50/50">
                    @foreach($produtosPorLocalizacao as $item)
                        @php
                            $percentual = $totalProdutos > 0 ? ($item['total'] / $totalProdutos) * 100 : 0;
                        @endphp
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 leading-none">{{ $item['nome_localizacao'] }}</h4>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 block">Localização</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-black text-gray-900">{{ number_format($item['total'], 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Produtos</span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-3 rounded-xl">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Impacto no Total</span>
                                        <span class="text-xs font-black text-indigo-600">{{ number_format($percentual, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $percentual }}%"></div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('produtos.index', ['localizacao' => $item['nome_localizacao']]) }}" class="flex items-center justify-center w-full py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 active:scale-95 transition-all">
                                    Ver Produtos desta Localização
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($produtosPorLocalizacao->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-base font-medium">Nenhum produto encontrado com o status selecionado.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        // Hide loading overlay when page is fully loaded
        window.addEventListener('load', function() {
            document.getElementById('loading-overlay').style.display = 'none';
        });
        
        // Show loading overlay when navigating away
        document.addEventListener('click', function(e) {
            // Check if the clicked element is a link or contains a link
            const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
            if (link && !link.hasAttribute('onclick')) {
                document.getElementById('loading-overlay').style.display = 'flex';
            }
        });
    </script>
</x-app-layout>
