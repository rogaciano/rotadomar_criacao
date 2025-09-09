<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <!-- Logos das marcas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-3">
                    <div class="flex justify-between items-center">
                        @php
                            // Busca marcas ativas e que tenham logo
                            $marcas = \App\Models\Marca::where('ativo', true)->where('logo_path', '!=', null)->get();
                            $totalMarcas = $marcas->count();
                        @endphp

                        @foreach($marcas as $index => $marca)
                            <div class="text-center flex-shrink-0" style="min-width: 70px;">
                                @if($marca->logo_path)
                                    <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="{{ $marca->nome_marca }}" class="h-12 w-auto object-contain mx-auto">
                                @else
                                    <div class="h-12 w-20 flex items-center justify-center bg-gray-100 rounded-lg">
                                        <span class="text-xs font-semibold text-gray-700">{{ $marca->nome_marca }}</span>
                                    </div>
                                @endif
                                <p class="mt-1 text-xs font-medium text-gray-600 truncate">{{ $marca->nome_marca }}</p>
                            </div>

                            @if($index < $totalMarcas - 1)
                                <div class="h-8 border-r border-gray-200"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
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
                        <a href="{{ route('tecidos.index') }}" class="flex justify-center items-center text-white hover:text-purple-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Ver Tecidos</span>
                        </a>
                    </div>
                </div>

                <!-- Card Estilistas -->
                <div class="bg-blue-500 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
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
                        <a href="{{ route('estilistas.index') }}" class="flex justify-center items-center text-white hover:text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Ver Estilista</span>
                        </a>
                    </div>
                </div>

<!-- Card Grupo de Produtos -->
<div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex justify-center mb-2">
            <div class="rounded-full bg-yellow-400/50 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <h3 class="text-center text-white font-medium">Grupos de Produtos</h3>
        <p class="text-center text-4xl font-bold text-white my-2">{{ $totalGrupoProdutos }}</p>
        <p class="text-center text-white text-sm">Total cadastrado</p>
    </div>
    <div class="bg-yellow-600 p-3">
        <a href="{{ route('grupo_produtos.create') }}" class="flex justify-center items-center text-white hover:text-yellow-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Ver Grupos</span>
        </a>
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
                        <a href="{{ route('produtos.index') }}" class="flex justify-center items-center text-white hover:text-orange-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
                            <span>Ver Produtos</span>
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
                        <a href="{{ route('movimentacoes.index') }}" class="flex justify-center items-center text-white hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
                            <span>Ver Movimentações</span>
                        </a>
                    </div>
                </div>
                <!-- Card Comparação Ano Atual vs Ano Passado -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white">Produtos {{ now()->year }} vs {{ now()->year - 1 }}</h3>
                                <p class="text-blue-100 mt-1">Até {{ now()->format('d/m') }}</p>
                            </div>
                            <div class="rounded-full bg-blue-400/30 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-white">{{ $produtosAnoAtualAteHoje }}</span>
                                <span class="text-lg text-blue-100">{{ $produtosAnoPassadoAteHoje }}</span>
                            </div>
                            <div class="flex items-center mt-2">
                                @if($variacaoPercentual > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($variacaoPercentual < 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586l-4.293-4.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                <span class="ml-1 text-sm font-medium {{ $variacaoPercentual > 0 ? 'text-green-300' : ($variacaoPercentual < 0 ? 'text-red-300' : 'text-yellow-300') }}">
                                    {{ abs($variacaoPercentual) }}% {{ $variacaoPercentual > 0 ? 'aumento' : ($variacaoPercentual < 0 ? 'redução' : 'sem alteração') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Projeção Anual -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white">Projeção {{ now()->year }}</h3>
                                <p class="text-indigo-100 mt-1">Baseado no ritmo atual</p>
                            </div>
                            <div class="rounded-full bg-indigo-400/30 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-white">{{ $projecaoProdutosAnoAtual }}</span>
                                <span class="text-lg text-indigo-100">produtos</span>
                            </div>
                            <div class="flex items-center mt-2">
                                @php
                                    $percentualConcluido = round((now()->dayOfYear / (now()->isLeapYear() ? 366 : 365)) * 100);
                                @endphp
                                <div class="w-full bg-indigo-700 rounded-full h-2.5">
                                    <div class="bg-white h-2.5 rounded-full" style="width: {{ $percentualConcluido }}%"></div>
                                </div>
                                <span class="ml-2 text-xs text-indigo-100">{{ $percentualConcluido }}%</span>
                            </div>
                            @php
                                $tendencia = $produtosAnoAtual > 0 ? round(($projecaoProdutosAnoAtual / $produtosAnoAtual) * 100) - 100 : 0;
                                $tendenciaTexto = $tendencia > 0 ? 'aumento' : ($tendencia < 0 ? 'redução' : 'estabilidade');
                                $tendenciaClasse = $tendencia > 0 ? 'text-green-300' : ($tendencia < 0 ? 'text-red-300' : 'text-yellow-300');
                            @endphp
                            <div class="mt-3 p-2 bg-indigo-600/50 rounded-md">
                                <p class="text-xs text-indigo-100">Tendência para o final do ano:</p>
                                <p class="text-sm font-medium {{ $tendenciaClasse }}">
                                    {{ abs($tendencia) }}% de {{ $tendenciaTexto }} em relação ao ano passado
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filtro de Ano -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Filtrar Dados por Ano</h3>
                    <div class="flex items-center space-x-2">
                        <label for="filtroAno" class="text-sm font-medium text-gray-700">Selecione o Ano:</label>
                        <select id="filtroAno" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @php
                                $anoAtual = date('Y');
                                $anoInicial = 2020; // Defina o ano inicial conforme necessidade
                            @endphp
                            @for ($ano = $anoAtual; $ano >= $anoInicial; $ano--)
                                <option value="{{ $ano }}" {{ $ano == $anoAtual ? 'selected' : '' }}>{{ $ano }}</option>
                            @endfor
                        </select>
                        <button id="btnFiltrarAno" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Filtrar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Gráfico de Produtos Ativos por Estilista -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Produtos Ativos por Estilista
                            </h3>
                            <a href="{{ route('dashboard.produtos-por-estilista') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22" />
                                </svg>
                                Ver gráfico De Estilistas
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <canvas id="produtosAtivosPorEstilistaChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Evolução de Produtos Ativos -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Evolução de Produtos Ativos (Últimos 12 Meses)
                        </h3>
                    </div>
                    <div class="p-4">
                        <canvas id="evolucaoProdutosAtivosChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico de Produtos por Ano -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Produtos Cadastrados por Ano
                        </h3>
                    </div>
                    <div class="p-4">
                        <canvas id="produtosAnoChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Comparação de Anos -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Comparação de Anos até {{ now()->format('d/m') }}
                        </h3>
                    </div>
                    <div class="p-4">
                        <canvas id="comparacaoAnosChart" height="250"></canvas>
                    </div>
                </div>

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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Variáveis globais para os gráficos
        let produtosAtivosPorEstilistaChart;
        let evolucaoProdutosAtivosChart;
        let produtosAnoChart;
        let comparacaoAnosChart;

        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Produtos por Ano
            const produtosAnoCtx = document.getElementById('produtosAnoChart').getContext('2d');

            const anos = @json(array_keys($estatisticasUltimos5Anos));
            const produtosPorAno = @json(array_values($estatisticasUltimos5Anos));

            // Gráfico de Comparação de Anos
            const comparacaoAnosCtx = document.getElementById('comparacaoAnosChart').getContext('2d');

            const anosComparacao = @json(array_keys($comparacaoAnos));
            const produtosComparacao = @json(array_values($comparacaoAnos));

            produtosAnoChart = new Chart(produtosAnoCtx, {
                type: 'bar',
                data: {
                    labels: anos,
                    datasets: [{
                        label: 'Produtos Cadastrados',
                        data: produtosPorAno,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' produtos';
                                }
                            }
                        }
                    }
                }
            });

            // Inicializar gráfico de comparação de anos
            comparacaoAnosChart = new Chart(comparacaoAnosCtx, {
                type: 'line',
                data: {
                    labels: ['Até {{ now()->format("d/m") }}'],
                    datasets: [
                        @foreach($comparacaoAnos as $ano => $quantidade)
                        {
                            label: '{{ $ano }}',
                            data: [{{ $quantidade }}],
                            backgroundColor: '{{ $ano == now()->year ? "rgba(54, 162, 235, 0.2)" : ($ano == now()->year - 1 ? "rgba(255, 99, 132, 0.2)" : "rgba(75, 192, 192, 0.2)") }}',
                            borderColor: '{{ $ano == now()->year ? "rgba(54, 162, 235, 1)" : ($ano == now()->year - 1 ? "rgba(255, 99, 132, 1)" : "rgba(75, 192, 192, 1)") }}',
                            borderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.1
                        },
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' produtos';
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Produtos Ativos por Estilista
            const produtosAtivosPorEstilistaCtx = document.getElementById('produtosAtivosPorEstilistaChart').getContext('2d');

            // Preparar dados para o gráfico
            const estilistaLabels = {!! json_encode(is_object($produtosAtivosPorEstilista) && method_exists($produtosAtivosPorEstilista, 'pluck') ? $produtosAtivosPorEstilista->pluck('nome_estilista')->toArray() : collect($produtosAtivosPorEstilista)->pluck('nome_estilista')->toArray()) !!};
            const estilistaData = {!! json_encode(is_object($produtosAtivosPorEstilista) && method_exists($produtosAtivosPorEstilista, 'pluck') ? $produtosAtivosPorEstilista->pluck('total')->toArray() : collect($produtosAtivosPorEstilista)->pluck('total')->toArray()) !!};

            // Preparar cores para cada barra (destaque para "Outros")
            const backgroundColors = estilistaLabels.map(label =>
                label === 'Outros' ? 'rgba(169, 169, 169, 0.7)' : 'rgba(75, 192, 192, 0.7)'
            );

            const borderColors = estilistaLabels.map(label =>
                label === 'Outros' ? 'rgba(169, 169, 169, 1)' : 'rgba(75, 192, 192, 1)'
            );

            produtosAtivosPorEstilistaChart = new Chart(produtosAtivosPorEstilistaCtx, {
                type: 'bar',
                data: {
                    labels: estilistaLabels,
                    datasets: [{
                        label: 'Produtos Ativos',
                        data: estilistaData,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' produtos';
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Evolução de Produtos Ativos
            const evolucaoProdutosAtivosCtx = document.getElementById('evolucaoProdutosAtivosChart').getContext('2d');
            evolucaoProdutosAtivosChart = new Chart(evolucaoProdutosAtivosCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($mesesLabels) !!},
                    datasets: [{
                        label: 'Produtos Ativos',
                        data: {!! json_encode($produtosAtivosPorMes) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' produtos';
                                }
                            }
                        }
                    }
                }
            });

            // Adicionar evento de clique ao botão de filtrar
            document.getElementById('btnFiltrarAno').addEventListener('click', function() {
                const anoSelecionado = document.getElementById('filtroAno').value;
                atualizarGraficosPorAno(anoSelecionado);
            });

            // Função para atualizar os gráficos por ano via AJAX
            function atualizarGraficosPorAno(ano) {
                // Mostrar indicador de carregamento ou desabilitar botão
                const btnFiltrar = document.getElementById('btnFiltrarAno');
                const textoOriginal = btnFiltrar.textContent;
                btnFiltrar.textContent = 'Carregando...';
                btnFiltrar.disabled = true;

                // Fazer requisição AJAX
                fetch(`{{ route('dashboard.dados-por-ano') }}?ano=${ano}`)
                    .then(response => response.json())
                    .then(data => {
                        // Atualizar gráfico de produtos ativos por estilista
                        atualizarGraficoProdutosAtivosPorEstilista(data.produtosAtivosPorEstilista);

                        // Atualizar gráfico de evolução de produtos ativos
                        atualizarGraficoEvolucaoProdutosAtivos(data.produtosAtivosPorMes);

                        // Restaurar botão
                        btnFiltrar.textContent = textoOriginal;
                        btnFiltrar.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao buscar dados:', error);
                        btnFiltrar.textContent = textoOriginal;
                        btnFiltrar.disabled = false;
                        alert('Erro ao buscar dados. Por favor, tente novamente.');
                    });
            }

            // Função para atualizar o gráfico de produtos ativos por estilista
            function atualizarGraficoProdutosAtivosPorEstilista(dados) {
                // Preparar cores para cada barra (destaque para "Outros")
                const backgroundColors = dados.labels.map(label =>
                    label === 'Outros' ? 'rgba(169, 169, 169, 0.7)' : 'rgba(75, 192, 192, 0.7)'
                );

                const borderColors = dados.labels.map(label =>
                    label === 'Outros' ? 'rgba(169, 169, 169, 1)' : 'rgba(75, 192, 192, 1)'
                );

                // Atualizar dados do gráfico
                produtosAtivosPorEstilistaChart.data.labels = dados.labels;
                produtosAtivosPorEstilistaChart.data.datasets[0].data = dados.data;
                produtosAtivosPorEstilistaChart.data.datasets[0].backgroundColor = backgroundColors;
                produtosAtivosPorEstilistaChart.data.datasets[0].borderColor = borderColors;

                // Atualizar gráfico
                produtosAtivosPorEstilistaChart.update();
            }

            // Função para atualizar o gráfico de evolução de produtos ativos
            function atualizarGraficoEvolucaoProdutosAtivos(dados) {
                // Atualizar dados do gráfico
                evolucaoProdutosAtivosChart.data.labels = dados.labels;
                evolucaoProdutosAtivosChart.data.datasets[0].data = dados.data;

                // Atualizar gráfico
                evolucaoProdutosAtivosChart.update();
            }
        });
    </script>
</x-app-layout>
