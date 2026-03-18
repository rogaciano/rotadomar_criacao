<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <!-- Seção de Marcas Dinâmica -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-2 overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-4 md:gap-8 py-4 px-4 md:px-12">
                    @php
                        $marcasDestaque = \App\Models\Marca::where('ativo', true)->get();
                    @endphp
                    @foreach($marcasDestaque as $marca)
                        <div class="flex flex-col items-center group transition-all duration-300">
                            @if($marca->logo_path)
                                <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="{{ $marca->nome_marca }}" class="h-10 md:h-12 w-auto object-contain transition-transform group-hover:scale-110 grayscale group-hover:grayscale-0 opacity-70 group-hover:opacity-100">
                            @else
                                <div class="h-10 w-20 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $marca->nome_marca }}</span>
                                </div>
                            @endif
                            <span class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">{{ $marca->nome_marca }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards estatísticos - Linha 1: Indicadores Base -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
                <!-- Card Tecidos -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Estoque</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Total de Tecidos</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ \App\Models\Tecido::count() }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Cadastrados no sistema</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('tecidos.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Ver detalhes <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Estilistas -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.123-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Equipe</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Total de Estilistas</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ \App\Models\Estilista::count() }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Cadastrados no sistema</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('estilistas.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Ver detalhes <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Grupos -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Configuração</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Grupos de Produtos</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ $totalGrupoProdutos }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Total cadastrado</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('grupo_produtos.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Ver detalhes <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Produtos -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Catálogo</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Total de Produtos</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ \App\Models\Produto::count() }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Cadastrados no sistema</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('produtos.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Ver catálogo <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Movimentações -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #475569 0%, #334155 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Fluxo</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Movimentações</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ \App\Models\Movimentacao::count() }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Registradas no sistema</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('movimentacoes.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Ver histórico <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Sugestões -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);">
                    <div class="p-6 relative text-white">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.945a2 2 0 002.22 0L21 8m-2 10H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Comunicados</span>
                        </div>
                        <h3 class="text-white/80 text-xs font-medium uppercase tracking-wider mb-1">Sugestões Não Lidas</h3>
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-black text-white">{{ $sugestoesNaoLidas ?? 0 }}</span>
                        </div>
                        <p class="text-[10px] text-white/60">Acompanhe e responda sugestões</p>
                    </div>
                    <div class="px-6 py-3 bg-black/10 flex justify-between items-center group-hover:bg-black/20 transition-colors">
                        <a href="{{ route('sugestoes.index') }}" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center">
                            Abrir módulo <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cards de Análise - Segunda Linha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Card Comparação Ano -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%) !important;">
                    <div class="p-8 relative">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Produtos {{ now()->year }} vs {{ now()->year - 1 }}</h3>
                                <p class="text-blue-100/80 text-xs font-bold uppercase tracking-widest mt-1">Comparativo até {{ now()->format('d/m') }}</p>
                            </div>
                            <div class="p-4 bg-white/20 backdrop-blur-md rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                                <span class="block text-[10px] uppercase font-black text-blue-200 mb-1">Ano Atual</span>
                                <span class="text-4xl font-black text-white">{{ $produtosAnoAtualAteHoje }}</span>
                            </div>
                            <div class="p-4 bg-black/10 rounded-2xl border border-white/5">
                                <span class="block text-[10px] uppercase font-black text-blue-200 mb-1">Ano Anterior</span>
                                <span class="text-4xl font-black text-white/50">{{ $produtosAnoPassadoAteHoje }}</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 py-3 px-6 bg-black/20 rounded-2xl inline-flex border border-white/10">
                            @if($variacaoPercentual >= 0)
                                <div class="p-1.5 bg-green-500/30 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @else
                                <div class="p-1.5 bg-red-500/30 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586l-4.293-4.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            <span class="text-xl font-black {{ $variacaoPercentual >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ abs($variacaoPercentual) }}% {{ $variacaoPercentual >= 0 ? 'de aumento' : 'de redução' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Projeção -->
                <div class="card-hover relative group rounded-2xl overflow-hidden shadow-lg border border-white/10" style="background: linear-gradient(135deg, #4338ca 0%, #312e81 100%) !important;">
                    <div class="p-8 relative">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Projeção {{ now()->year }}</h3>
                                <p class="text-indigo-100/80 text-xs font-bold uppercase tracking-widest mt-1">Ritmo atual de produção</p>
                            </div>
                            <div class="p-4 bg-white/20 backdrop-blur-md rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline mb-8">
                            <span class="text-6xl font-black text-white tracking-tighter">{{ $projecaoProdutosAnoAtual }}</span>
                            <span class="ml-3 text-indigo-200 text-sm font-bold uppercase tracking-widest">Produtos</span>
                        </div>

                        <div class="mb-8">
                            @php
                                $percentualConcluido = round((now()->dayOfYear / (now()->isLeapYear() ? 366 : 365)) * 100);
                            @endphp
                            <div class="flex justify-between text-[10px] font-black text-indigo-200 uppercase mb-2 tracking-widest">
                                <span>Progresso do Ano</span>
                                <span>{{ $percentualConcluido }}%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-3 border border-white/10 p-0.5">
                                <div class="bg-white h-full rounded-full shadow-[0_0_15px_rgba(255,255,255,0.7)] transition-all duration-1000" style="width: {{ $percentualConcluido }}%"></div>
                            </div>
                        </div>

                        @php
                            $tendencia = $produtosAnoAtual > 0 ? round(($projecaoProdutosAnoAtual / $produtosAnoAtual) * 100) - 100 : 0;
                            $tendenciaTexto = $tendencia > 0 ? 'aumento' : 'redução';
                        @endphp
                        <div class="p-4 bg-black/20 rounded-2xl border border-white/10">
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Expectativa Final:</p>
                            <p class="text-lg font-black {{ $tendencia >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ abs($tendencia) }}% de {{ $tendenciaTexto }} vs ano anterior
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Capacidade das Localizações -->
            @if(isset($capacidadeLocalizacoes) && count($capacidadeLocalizacoes) > 0)
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-md overflow-hidden mb-6 border border-slate-200 dark:border-slate-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Capacidade de Localizações - Próximos 3 Meses
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Comparação entre <strong class="text-blue-600">Capacidade Total</strong> e <strong class="text-orange-600">Previsto Total</strong> de todas as localizações
                        <span class="text-xs text-gray-400 ml-2">({{ count($capacidadeLocalizacoes) }} localização(ões))</span>
                    </p>
                    <p class="text-xs text-purple-600 mt-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                        <strong>Clique em um mês</strong> para ver detalhamento por localização
                    </p>
                </div>
                <div class="p-6" style="height: 400px;">
                    <canvas id="capacidadeLocalizacoesChart" style="cursor: pointer;"></canvas>
                </div>
            </div>

            <!-- Modal de Detalhamento por Mês -->
            <div id="modalDetalhamentoMes" class="hidden fixed inset-0 bg-gray-600/50 dark:bg-gray-900/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-slate-900 border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modalTitulo">Detalhamento - Mês</h3>
                        <button onclick="fecharModalDetalhamento()" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Localização</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Capacidade</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Previsto</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saldo</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ocupação</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelaDetalhamento" class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                                    <!-- Conteúdo será preenchido via JavaScript -->
                                </tbody>
                                <tfoot class="bg-gray-100 dark:bg-slate-800 font-semibold">
                                    <tr>
                                        <td class="px-6 py-3 text-left text-sm text-gray-900 dark:text-white">Total</td>
                                        <td id="totalCapacidade" class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white"></td>
                                        <td id="totalPrevisto" class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white"></td>
                                        <td id="totalSaldo" class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white"></td>
                                        <td id="totalOcupacao" class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button onclick="fecharModalDetalhamento()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white rounded transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            @elseif(isset($capacidadeLocalizacoes))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-900/50 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 dark:text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Nenhuma localização com capacidade encontrada</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            Configure localizações ativas com capacidade > 0 para visualizar o gráfico
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Filtro de Ano -->
            <div class="glass dark:glass-dark rounded-2xl mb-8 p-6 ring-1 ring-black/5">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Análise Temporal</h3>
                        <p class="text-sm text-slate-500">Selecione o ano para filtrar os indicadores de desempenho</p>
                    </div>
                    <div class="flex items-center space-x-3 w-full md:w-auto">
                        <select id="filtroAno" class="flex-1 md:w-32 rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            @php
                                $anoAtual = date('Y');
                                $anoInicial = 2020;
                            @endphp
                            @for ($ano = $anoAtual; $ano >= $anoInicial; $ano--)
                                <option value="{{ $ano }}" {{ $ano == $anoAtual ? 'selected' : '' }}>{{ $ano }}</option>
                            @endfor
                        </select>
                        <button id="btnFiltrarAno" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-primary-600/20 active:scale-95">
                            Filtrar
                        </button>
                    </div>
                </div>
            </div>


            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Produtos Ativos por Estilista -->
                <div class="glass dark:glass-dark rounded-2xl overflow-hidden border-none ring-1 ring-black/5">
                    <div class="p-6 border-b border-slate-200/50 dark:border-slate-800/50">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">
                                Produtos Ativos por Estilista
                            </h3>
                            <a href="{{ route('dashboard.produtos-por-estilista') }}" class="p-2 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg hover:bg-primary-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <canvas id="produtosAtivosPorEstilistaChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Evolução de Produtos Ativos -->
                <div class="glass dark:glass-dark rounded-2xl overflow-hidden border-none ring-1 ring-black/5">
                    <div class="p-6 border-b border-slate-200/50 dark:border-slate-800/50">
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">
                            Evolução Temporal
                        </h3>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold mt-1">Últimos 12 Meses</p>
                    </div>
                    <div class="p-6">
                        <canvas id="evolucaoProdutosAtivosChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Produtos por Ano -->
                <div class="glass dark:glass-dark rounded-2xl overflow-hidden border-none ring-1 ring-black/5">
                    <div class="p-6 border-b border-slate-200/50 dark:border-slate-800/50">
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center">
                            <span class="w-1.5 h-6 bg-primary-500 rounded-full mr-3"></span>
                            Produtos por Ano
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="produtosAnoChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Comparação de Anos -->
                <div class="glass dark:glass-dark rounded-2xl overflow-hidden border-none ring-1 ring-black/5">
                    <div class="p-6 border-b border-slate-200/50 dark:border-slate-800/50">
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center">
                            <span class="w-1.5 h-6 bg-green-500 rounded-full mr-3"></span>
                            Comparativo até {{ now()->format('d/m') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="comparacaoAnosChart" height="250"></canvas>
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

            // Gráfico de Capacidade das Localizações
            @if(isset($capacidadeLocalizacoes) && count($capacidadeLocalizacoes) > 0)
            const capacidadeLocalizacoesCtx = document.getElementById('capacidadeLocalizacoesChart');
            if (capacidadeLocalizacoesCtx) {
                // Preparar dados do PHP
                const capacidadeDados = @json($capacidadeLocalizacoes);
                const mesesLabelsCapacidade = @json(array_column($mesesCapacidade, 'label'));

                // Calcular totais por mês
                const totalCapacidadePorMes = Array(mesesLabelsCapacidade.length).fill(0);
                const totalPrevistoPorMes = Array(mesesLabelsCapacidade.length).fill(0);

                capacidadeDados.forEach(loc => {
                    loc.dados.forEach((dado, index) => {
                        // Garantir conversão para número inteiro
                        const capacidade = parseInt(dado.capacidade) || 0;
                        const previsto = parseInt(dado.previsto) || 0;

                        totalCapacidadePorMes[index] += capacidade;
                        totalPrevistoPorMes[index] += previsto;
                    });
                });

                // Preparar datasets com os totais
                const datasets = [{
                    label: 'Capacidade Total',
                    data: totalCapacidadePorMes,
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB',
                    borderWidth: 1,
                    tipo: 'capacidade'
                }, {
                    label: 'Previsto Total',
                    data: totalPrevistoPorMes,
                    backgroundColor: '#F97316',
                    borderColor: '#EA580C',
                    borderWidth: 1,
                    tipo: 'previsto'
                }];

                try {
                    const capacidadeLocalizacoesChart = new Chart(capacidadeLocalizacoesCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: mesesLabelsCapacidade,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                x: {
                                    stacked: false,
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    categoryPercentage: 0.8,
                                    barPercentage: 0.9
                                },
                                y: {
                                    stacked: false,
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        callback: function(value) {
                                            return value.toLocaleString('pt-BR');
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Quantidade de Produtos',
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                }
                            },
                            onClick: (event, activeElements) => {
                                if (activeElements.length > 0) {
                                    const index = activeElements[0].index;
                                    const mesLabel = mesesLabelsCapacidade[index];
                                    abrirModalDetalhamento(index, mesLabel);
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 12
                                        },
                                        padding: 15,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        title: function(context) {
                                            // Mostra o mês no título do tooltip
                                            return context[0].label;
                                        },
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            const value = context.parsed.y;
                                            return `${label}: ${value}`;
                                        },
                                        footer: function(tooltipItems) {
                                            const capacidade = tooltipItems.find(item => item.dataset.tipo === 'capacidade')?.parsed.y || 0;
                                            const previsto = tooltipItems.find(item => item.dataset.tipo === 'previsto')?.parsed.y || 0;

                                            const saldo = capacidade - previsto;
                                            const ocupacao = capacidade > 0 ? ((previsto / capacidade) * 100).toFixed(1) : 0;

                                            return `\nSaldo: ${saldo} | Ocupação: ${ocupacao}%`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Erro ao criar gráfico de capacidade:', error);
                }
            }
            @endif

            // Funções globais para o modal de detalhamento
            window.abrirModalDetalhamento = function(mesIndex, mesLabel) {
                @if(isset($capacidadeLocalizacoes) && count($capacidadeLocalizacoes) > 0)
                const dados = @json($capacidadeLocalizacoes);
                const modal = document.getElementById('modalDetalhamentoMes');
                const titulo = document.getElementById('modalTitulo');
                const tbody = document.getElementById('tabelaDetalhamento');

                // Atualizar título
                titulo.textContent = `Detalhamento - ${mesLabel}`;

                // Limpar tabela
                tbody.innerHTML = '';

                // Totais
                let totalCap = 0;
                let totalPrev = 0;
                let totalSaldo = 0;

                // Preencher tabela com dados de cada localização
                dados.forEach(loc => {
                    const dadoMes = loc.dados[mesIndex];
                    const capacidade = parseInt(dadoMes.capacidade) || 0;
                    const previsto = parseInt(dadoMes.previsto) || 0;
                    const saldo = capacidade - previsto;
                    const ocupacao = capacidade > 0 ? ((previsto / capacidade) * 100).toFixed(1) : 0;

                    totalCap += capacidade;
                    totalPrev += previsto;
                    totalSaldo += saldo;

                    const saldoClass = saldo >= 0 ? 'text-green-600' : 'text-red-600';
                    const ocupacaoClass = ocupacao > 100 ? 'text-red-600 font-semibold' : (ocupacao > 80 ? 'text-yellow-600' : 'text-green-600');

                    const row = `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                ${loc.nome}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                ${capacidade.toLocaleString('pt-BR')}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                ${previsto.toLocaleString('pt-BR')}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm ${saldoClass} text-right font-medium">
                                ${saldo.toLocaleString('pt-BR')}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm ${ocupacaoClass} text-right font-medium">
                                ${ocupacao}%
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });

                // Atualizar totais
                const totalOcupacao = totalCap > 0 ? ((totalPrev / totalCap) * 100).toFixed(1) : 0;
                const totalSaldoClass = totalSaldo >= 0 ? 'text-green-600' : 'text-red-600';
                const totalOcupacaoClass = totalOcupacao > 100 ? 'text-red-600' : (totalOcupacao > 80 ? 'text-yellow-600' : 'text-green-600');

                document.getElementById('totalCapacidade').textContent = totalCap.toLocaleString('pt-BR');
                document.getElementById('totalPrevisto').textContent = totalPrev.toLocaleString('pt-BR');
                document.getElementById('totalSaldo').innerHTML = `<span class="${totalSaldoClass} font-bold">${totalSaldo.toLocaleString('pt-BR')}</span>`;
                document.getElementById('totalOcupacao').innerHTML = `<span class="${totalOcupacaoClass} font-bold">${totalOcupacao}%</span>`;

                // Mostrar modal
                modal.classList.remove('hidden');
                @endif
            }

            window.fecharModalDetalhamento = function() {
                console.log('Fechando modal...');
                const modal = document.getElementById('modalDetalhamentoMes');
                if (modal) {
                    modal.classList.add('hidden');
                    console.log('Modal fechado!');
                } else {
                    console.error('Modal não encontrado!');
                }
            }

            // Confirmar que as funções estão disponíveis
            console.log('Funções do modal registradas:', {
                fechar: typeof window.fecharModalDetalhamento,
                abrir: typeof window.abrirModalDetalhamento
            });

            // Configurar eventos do modal (fechar ao clicar fora ou pressionar ESC)
            setTimeout(function() {
                const modal = document.getElementById('modalDetalhamentoMes');
                if (modal) {
                    // Fechar ao clicar no backdrop
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            window.fecharModalDetalhamento();
                        }
                    });
                }

                // Fechar ao pressionar ESC
                document.addEventListener('keydown', function(e) {
                    const modal = document.getElementById('modalDetalhamentoMes');
                    if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                        window.fecharModalDetalhamento();
                    }
                });
            }, 100);

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
