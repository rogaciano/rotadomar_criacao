<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Calendário de Produção') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Botão Voltar -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('localizacao-capacidade.dashboard', ['mes' => $mes, 'ano' => $ano, 'localizacao_id' => $localizacaoId, 'referencia' => $referencia ?? '']) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar ao Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('localizacao-capacidade.calendario') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                            <select name="localizacao_id" id="localizacao_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todas as Localizações</option>
                                @foreach($localizacoes as $localizacao)
                                    <option value="{{ $localizacao->id }}" {{ ($localizacaoId ?? '') == $localizacao->id ? 'selected' : '' }}>
                                        {{ $localizacao->nome_localizacao }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                            <input type="text" name="referencia" id="referencia" value="{{ $referencia ?? '' }}" placeholder="Ex: 1234" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="mes" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                            <select name="mes" id="mes" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'] as $index => $nomeMes)
                                    <option value="{{ $index + 1 }}" {{ $mes == ($index + 1) ? 'selected' : '' }}>{{ $nomeMes }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                            <select name="ano" id="ano" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @for($i = now()->year - 1; $i <= now()->year + 2; $i++)
                                    <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[38px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Legenda -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-4">
                    <div class="flex flex-wrap gap-4 justify-center">
                        @foreach($tiposCores as $tipo => $config)
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded" style="background-color: {{ $config['cor'] }}"></span>
                                <span class="text-sm font-medium text-gray-700">{{ $config['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Calendário -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ $mesNome }} {{ $ano }}</h3>

                    <!-- Cabeçalho dos dias da semana -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;" class="mb-2">
                        @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $diaSemana)
                            <div class="text-center font-bold text-sm text-gray-600 py-2 bg-gray-100 rounded">
                                {{ $diaSemana }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Dias do calendário -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
                        {{-- Dias vazios antes do primeiro dia do mês --}}
                        @for($i = 0; $i < $diaSemanaInicio; $i++)
                            <div style="min-height: 120px;" class="bg-gray-50 rounded border border-gray-100"></div>
                        @endfor

                        {{-- Dias do mês --}}
                        @for($dia = 1; $dia <= $diasNoMes; $dia++)
                            @php
                                $eventosDodia = $eventos[$dia] ?? [];
                                $totalEventos = count($eventosDodia);
                                $maxExibir = 3;
                                $isHoje = ($dia == now()->day && $mes == now()->month && $ano == now()->year);
                            @endphp
                            <div style="min-height: 120px;" class="bg-white rounded border {{ $isHoje ? 'border-indigo-500 border-2' : 'border-gray-200' }} p-1 hover:shadow-md transition-shadow cursor-pointer"
                                 @if($totalEventos > 0) onclick="abrirModalDia({{ $dia }})" @endif>
                                <div class="text-right mb-1">
                                    <span class="text-sm font-bold {{ $isHoje ? 'bg-indigo-600 text-white px-2 py-0.5 rounded-full' : 'text-gray-700' }}">
                                        {{ $dia }}
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    @if($totalEventos <= $maxExibir)
                                        @foreach($eventosDodia as $evento)
                                            <div class="px-1.5 py-0.5 rounded text-[10px] font-medium text-white truncate"
                                                 style="background-color: {{ $evento['cor'] }}"
                                                 title="{{ $evento['referencia'] }} - {{ $evento['localizacao'] }} ({{ $tiposCores[$evento['tipo']]['label'] }})">
                                                <span class="font-bold">{{ $evento['referencia'] }}</span>
                                                <span class="opacity-80">{{ $evento['localizacao'] }}</span>
                                            </div>
                                        @endforeach
                                    @elseif($totalEventos > 0)
                                        {{-- Mostrar resumo por tipo --}}
                                        @php
                                            $porTipo = collect($eventosDodia)->groupBy('tipo');
                                        @endphp
                                        @foreach($porTipo as $tipo => $eventosGrupo)
                                            <div class="px-1.5 py-0.5 rounded text-[10px] font-bold text-white"
                                                 style="background-color: {{ $tiposCores[$tipo]['cor'] }}">
                                                {{ count($eventosGrupo) }} {{ $tiposCores[$tipo]['label'] }}
                                            </div>
                                        @endforeach
                                        <div class="text-center text-[10px] text-gray-500 font-medium mt-1">
                                            Clique para ver
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor

                        {{-- Dias vazios após o último dia do mês --}}
                        @php
                            $totalCelulas = $diaSemanaInicio + $diasNoMes;
                            $celulasRestantes = (7 - ($totalCelulas % 7)) % 7;
                        @endphp
                        @for($i = 0; $i < $celulasRestantes; $i++)
                            <div style="min-height: 120px;" class="bg-gray-50 rounded border border-gray-100"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Navegação Mês Anterior/Próximo -->
            <div class="flex justify-between mt-4">
                @php
                    $mesAnterior = $mes - 1;
                    $anoAnterior = $ano;
                    if ($mesAnterior < 1) {
                        $mesAnterior = 12;
                        $anoAnterior = $ano - 1;
                    }

                    $mesProximo = $mes + 1;
                    $anoProximo = $ano;
                    if ($mesProximo > 12) {
                        $mesProximo = 1;
                        $anoProximo = $ano + 1;
                    }
                @endphp
                <a href="{{ route('localizacao-capacidade.calendario', ['mes' => $mesAnterior, 'ano' => $anoAnterior, 'localizacao_id' => $localizacaoId, 'referencia' => $referencia ?? '']) }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Mês Anterior
                </a>
                <a href="{{ route('localizacao-capacidade.calendario', ['mes' => $mesProximo, 'ano' => $anoProximo, 'localizacao_id' => $localizacaoId, 'referencia' => $referencia ?? '']) }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Próximo Mês
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

    <!-- Modal Detalhes do Dia -->
    <div id="modalDia" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalDiaTitulo" class="text-xl font-bold text-gray-800"></h3>
                <button onclick="fecharModalDia()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Legenda no Modal -->
            <div class="flex flex-wrap gap-3 mb-4 pb-3 border-b">
                @foreach($tiposCores as $tipo => $config)
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded" style="background-color: {{ $config['cor'] }}"></span>
                        <span class="text-xs font-medium text-gray-600">{{ $config['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <div id="modalDiaConteudo" class="max-h-[60vh] overflow-y-auto">
                <!-- Conteúdo será inserido via JavaScript -->
            </div>

            <div class="mt-4 pt-3 border-t text-right">
                <button onclick="fecharModalDia()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Dados dos eventos para o JavaScript
        const eventosCalendario = @json($eventos);
        const tiposCores = @json($tiposCores);
        const mesNome = @json($mesNome);
        const ano = @json($ano);

        function abrirModalDia(dia) {
            const eventos = eventosCalendario[dia] || [];
            if (eventos.length === 0) return;

            document.getElementById('modalDiaTitulo').textContent = `${dia} de ${mesNome} de ${ano}`;

            let html = '<div class="space-y-2">';

            // Agrupar por tipo
            const porTipo = {};
            eventos.forEach(evento => {
                if (!porTipo[evento.tipo]) {
                    porTipo[evento.tipo] = [];
                }
                porTipo[evento.tipo].push(evento);
            });

            // Renderizar cada grupo
            for (const tipo in porTipo) {
                const config = tiposCores[tipo];
                html += `<div class="mb-3">
                    <h4 class="font-bold text-sm mb-2 px-2 py-1 rounded text-white" style="background-color: ${config.cor}">${config.label} (${porTipo[tipo].length})</h4>
                    <div class="space-y-1">`;

                porTipo[tipo].forEach(evento => {
                    const url = `/produtos/${evento.produto_id}?back_url=${encodeURIComponent(window.location.href)}`;
                    html += `<a href="${url}" class="flex items-center justify-between p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                        <div>
                            <span class="font-bold text-indigo-600">${evento.referencia}</span>
                            <span class="text-gray-500 ml-2">${evento.localizacao}</span>
                            <span class="text-xs text-amber-600 ml-2">${evento.data}</span>
                        </div>
                        <span class="text-xs text-gray-400">Qtd: ${evento.quantidade}</span>
                    </a>`;
                });

                html += '</div></div>';
            }

            html += '</div>';

            document.getElementById('modalDiaConteudo').innerHTML = html;
            document.getElementById('modalDia').classList.remove('hidden');
        }

        function fecharModalDia() {
            document.getElementById('modalDia').classList.add('hidden');
        }

        // Fechar modal ao clicar fora
        document.getElementById('modalDia').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalDia();
            }
        });

        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                fecharModalDia();
            }
        });
    </script>
</x-app-layout>
