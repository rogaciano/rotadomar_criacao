<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pivot Table: Estilistas x Status') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="bg-indigo-600 text-white p-4 rounded-t-lg">
                        <h5 class="text-lg font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Pivot Table: Estilistas x Status ({{ $titulo ?? 'Últimos 12 meses' }})
                        </h5>
                    </div>
                    <div class="bg-white p-6 rounded-b-lg shadow">
                        <div class="mb-6">
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 text-indigo-700 p-4 rounded">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>Esta tabela cruzada mostra a quantidade de produtos criados por cada estilista, organizados por status no período selecionado.
                                    Os estilistas estão ordenados por total de produtos (decrescente).</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <form action="{{ route('consultas.pivot-estilistas-status') }}" method="GET" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-700 mb-3">Filtros</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Período Predefinido</label>
                                        <select id="periodo" name="periodo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="ultimos_12_meses" {{ request('periodo', 'ultimos_12_meses') == 'ultimos_12_meses' ? 'selected' : '' }}>Últimos 12 meses</option>
                                            <option value="ultimos_6_meses" {{ request('periodo') == 'ultimos_6_meses' ? 'selected' : '' }}>Últimos 6 meses</option>
                                            <option value="ultimos_3_meses" {{ request('periodo') == 'ultimos_3_meses' ? 'selected' : '' }}>Últimos 3 meses</option>
                                            <option value="ano_atual" {{ request('periodo') == 'ano_atual' ? 'selected' : '' }}>Ano atual</option>
                                            <option value="ano_anterior" {{ request('periodo') == 'ano_anterior' ? 'selected' : '' }}>Ano anterior</option>
                                            <option value="personalizado" {{ request('periodo') == 'personalizado' ? 'selected' : '' }}>Período personalizado</option>
                                        </select>
                                    </div>
                                    
                                    <div id="data-inicio-container" class="{{ request('periodo') == 'personalizado' ? '' : 'hidden' }}">
                                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                                        <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio', Carbon\Carbon::now()->subMonths(12)->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div id="data-fim-container" class="{{ request('periodo') == 'personalizado' ? '' : 'hidden' }}">
                                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                                        <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim', Carbon\Carbon::now()->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Atualizar Tabela
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Pivot Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-800 text-white">
                                        <th class="py-3 px-4 text-left border-r border-gray-600 sticky left-0 bg-gray-800 z-10">Estilista</th>
                                        @foreach($todosStatus as $status)
                                            <th class="py-3 px-4 text-center border-r border-gray-600 min-w-[100px]">
                                                {{ $status->descricao }}
                                            </th>
                                        @endforeach
                                        <th class="py-3 px-4 text-center bg-gray-700 font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($pivotData as $linha)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-900 border-r border-gray-200 sticky left-0 bg-white z-10">
                                            <a href="{{ route('estilistas.show', $linha['estilista_id']) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $linha['nome_estilista'] }}
                                            </a>
                                        </td>
                                        @foreach($todosStatus as $status)
                                            <td class="py-3 px-4 text-center border-r border-gray-200">
                                                @php
                                                    $quantidade = $linha['status'][$status->id] ?? 0;
                                                @endphp
                                                @if($quantidade > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $quantidade }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center font-bold bg-gray-50">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                                {{ $linha['total_estilista'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ count($todosStatus) + 2 }}" class="py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                <p class="text-lg font-medium">Nenhum dados encontrado</p>
                                                <p class="text-sm">Não há produtos no período selecionado.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr class="font-bold">
                                        <td class="py-3 px-4 text-left border-r border-gray-300 sticky left-0 bg-gray-100 z-10">
                                            Total por Status
                                        </td>
                                        @foreach($todosStatus as $status)
                                            <td class="py-3 px-4 text-center border-r border-gray-300">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-200 text-gray-800">
                                                    {{ $totaisPorStatus[$status->id] ?? 0 }}
                                                </span>
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-4 text-center bg-gray-200">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-200 text-indigo-800">
                                                {{ $totalGeral }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Estatísticas Resumidas -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">Total de Estilistas</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ count($pivotData) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-600">Total de Produtos</p>
                                        <p class="text-2xl font-bold text-green-800">{{ $totalGeral }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-purple-600">Período</p>
                                        <p class="text-sm font-bold text-purple-800">{{ $periodoInicio }} a {{ $periodoFim }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 my-6 pt-6"></div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Voltar ao Dashboard
                            </a>
                            <button onclick="exportarCSV()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Exportar como CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Controle de exibição dos campos de data personalizada
            const periodoSelect = document.getElementById('periodo');
            const dataInicioContainer = document.getElementById('data-inicio-container');
            const dataFimContainer = document.getElementById('data-fim-container');
            
            periodoSelect.addEventListener('change', function() {
                if (this.value === 'personalizado') {
                    dataInicioContainer.classList.remove('hidden');
                    dataFimContainer.classList.remove('hidden');
                } else {
                    dataInicioContainer.classList.add('hidden');
                    dataFimContainer.classList.add('hidden');
                }
            });
        });
        
        // Função para exportar a tabela como CSV
        function exportarCSV() {
            const titulo = '{{ $titulo ?? "Ultimos-12-meses" }}'.replace(/ /g, '-').normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const filename = `pivot-estilistas-status-${titulo}-{{ date('Y-m-d') }}.csv`;
            
            // Criar cabeçalho CSV
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Estilista,";
            
            // Adicionar cabeçalhos dos status
            @foreach($todosStatus as $status)
                csvContent += "{{ $status->descricao }},";
            @endforeach
            csvContent += "Total\n";
            
            // Adicionar dados
            @foreach($pivotData as $linha)
                csvContent += "{{ $linha['nome_estilista'] }},";
                @foreach($todosStatus as $status)
                    csvContent += "{{ $linha['status'][$status->id] ?? 0 }},";
                @endforeach
                csvContent += "{{ $linha['total_estilista'] }}\n";
            @endforeach
            
            // Adicionar linha de totais
            csvContent += "Total por Status,";
            @foreach($todosStatus as $status)
                csvContent += "{{ $totaisPorStatus[$status->id] ?? 0 }},";
            @endforeach
            csvContent += "{{ $totalGeral }}\n";
            
            // Criar e baixar o arquivo
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-app-layout>
