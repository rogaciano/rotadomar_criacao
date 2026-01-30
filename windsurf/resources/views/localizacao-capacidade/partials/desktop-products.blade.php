<div class="hidden md:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
        <thead class="bg-gray-50 dark:bg-slate-800">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 10%;">Referência</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 16%;">Descrição</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 10%;">Marca</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 10%;">Grupo</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 40%;">Produção e Detalhes</th>
                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" title="Quantidade total do produto" style="width: 10%;">Qtd Total</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase" style="width: 4%;">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($produtosAgrupados as $chave => $produtosGrupo)
                @php
                    $produtoPrincipal = $produtosGrupo->first();

                    // Identificar todas as etapas presentes neste grupo de produtos
                    $etapaIdsNoGrupo = $produtosGrupo->flatMap(function($p) {
                        return $p->localizacoes->pluck('pivot.etapa_atual_id');
                    })->unique()->filter()->toArray();

                    $etapasNoGrupo = $etapasProducao->whereIn('id', $etapaIdsNoGrupo);
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                    {{-- Coluna 1: Referência --}}
                    <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-white">
                        <a href="{{ route('produtos.show', $produtoPrincipal->id) }}?back_url={{ urlencode(request()->fullUrl()) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline">
                            {{ $produtoPrincipal->referencia }}
                        </a>
                        @php
                            // Buscar a data prevista para produção
                            $dataPrevista = null;
                            foreach($produtosGrupo as $produto) {
                                $locComData = $produto->localizacoes
                                    ->whereNotNull('pivot.data_prevista_faccao')
                                    ->sortBy('pivot.data_prevista_faccao')
                                    ->first();
                                if ($locComData && $locComData->pivot->data_prevista_faccao) {
                                    $dataPrevista = is_string($locComData->pivot->data_prevista_faccao)
                                        ? \Carbon\Carbon::parse($locComData->pivot->data_prevista_faccao)->format('d/m/Y')
                                        : $locComData->pivot->data_prevista_faccao->format('d/m/Y');
                                    break;
                                }
                            }
                        @endphp
                        @if($dataPrevista)
                            <div class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-tighter">
                                <svg class="w-3 h-3 inline-block mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                PREV: {{ $dataPrevista }}
                            </div>
                        @endif
                    </td>

                    {{-- Coluna 3: Descrição --}}
                    <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $produtoPrincipal->descricao }}</td>

                    {{-- Coluna 4: Marca --}}
                    <td class="px-3 py-2 text-sm">
                        @if($produtoPrincipal->marca)
                            @if($produtoPrincipal->marca->cor_fundo && $produtoPrincipal->marca->cor_fonte)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $produtoPrincipal->marca->cor_fundo }}; color: {{ $produtoPrincipal->marca->cor_fonte }};">
                                    {{ $produtoPrincipal->marca->nome_marca }}
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $produtoPrincipal->marca->nome_marca }}
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400 italic text-xs">N/A</span>
                        @endif
                    </td>

                    {{-- Coluna 5: Grupo --}}
                    <td class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $produtoPrincipal->grupoProduto->descricao ?? 'N/A' }}</td>

                    {{-- Coluna 6: Produção e Detalhes --}}
                    <td class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400">
                        @php
                            // Carregar observações do produto (apenas uma vez)
                            $obs = \App\Models\ProdutoObservacao::where('produto_id', $produtoPrincipal->id)->get();

                            // Carregar todas as observações das localizações de todas as alocações
                            // USAR localizacoes (sem parênteses) para pegar a collection já filtrada
                            $todasObsLocalizacoes = collect();
                            foreach($produtosGrupo as $produto) {
                                $obsLoc = $produto->localizacoes->filter(function($loc) {
                                    return !empty($loc->pivot->ordem_producao) || !empty($loc->pivot->observacao);
                                });
                                $todasObsLocalizacoes = $todasObsLocalizacoes->merge($obsLoc);
                            }

                            // Remover duplicatas baseado em ordem_producao + observacao
                            $todasObsLocalizacoes = $todasObsLocalizacoes->unique(function($loc) {
                                return $loc->pivot->ordem_producao . '|' . $loc->pivot->observacao;
                            });

                            $temObservacoes = $obs->count() > 0 || $todasObsLocalizacoes->count() > 0;
                        @endphp


                        {{-- Observações das Localizações (Ordem de Produção) - sem duplicatas --}}
                        @if($todasObsLocalizacoes->count() > 0)
                            <table class="w-full text-xs">

                                @php
                                    $totalQuantidades = 0;
                                @endphp
                                @foreach($todasObsLocalizacoes as $loc)
                                    @php
                                        // Buscar a quantidade alocada para esta ordem de produção
                                        $qtdAlocada = 0;
                                        foreach($produtosGrupo as $produto) {
                                            $localizacaoAtual = $produto->localizacoes()
                                                ->where('ordem_producao', $loc->pivot->ordem_producao)
                                                ->first();
                                            if ($localizacaoAtual) {
                                                $qtdAlocada = $localizacaoAtual->pivot->quantidade ?? 0;
                                                break;
                                            }
                                        }
                                        $totalQuantidades += $qtdAlocada;

                                        // Formatar datas
                                        $dataEnvio = $loc->pivot->data_envio_faccao
                                            ? (is_string($loc->pivot->data_envio_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_envio_faccao)->format('d/m/Y') : $loc->pivot->data_envio_faccao->format('d/m/Y'))
                                            : null;
                                        $dataRetorno = $loc->pivot->data_retorno_faccao
                                            ? (is_string($loc->pivot->data_retorno_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_retorno_faccao)->format('d/m/Y') : $loc->pivot->data_retorno_faccao->format('d/m/Y'))
                                            : null;
                                        $dataEntrega = $loc->pivot->data_entrega_faccao
                                            ? (is_string($loc->pivot->data_entrega_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_entrega_faccao)->format('d/m/Y') : $loc->pivot->data_entrega_faccao->format('d/m/Y'))
                                            : null;
                                    @endphp
                                    <tr class="{{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                        {{-- Coluna 1: OP --}}
                                        <td class="py-1 pr-2 align-top whitespace-nowrap">
                                            @if($loc->pivot->ordem_producao)
                                                <a href="{{ $loc->pivot->ordem_producao_url }}" target="_blank" class="font-semibold text-blue-700 hover:underline">
                                                    OP: {{ $loc->pivot->ordem_producao }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">Sem OP</span>
                                            @endif
                                            @if($loc->pivot->concluido == 1)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor" title="Concluído">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </td>
                                        {{-- Coluna 2: Etapa Específica --}}
                                        <td class="py-1 px-2 align-top whitespace-nowrap">
                                            @php
                                                $etapaLinhaId = $loc->pivot->etapa_atual_id;
                                                $etapaLinha = $etapaLinhaId ? $etapasProducao->firstWhere('id', $etapaLinhaId) : null;
                                            @endphp
                                            @if($etapaLinha)
                                                <div class="flex items-center gap-1">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $corClasses[$etapaLinha->cor] ?? 'bg-gray-100 text-gray-800' }} uppercase">
                                                        {{ $etapaLinha->icone ?? '' }} {{ $etapaLinha->nome }}
                                                    </span>
                                                    <a href="{{ route('produtos.localizacoes.historico-etapas', [$produtoPrincipal->id, $loc->pivot->id]) }}"
                                                       class="text-gray-400 hover:text-indigo-600 transition-colors" title="Ver histórico de etapas">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-[9px]">—</span>
                                            @endif
                                        </td>
                                        {{-- Coluna 3: Qtd --}}
                                        <td class="py-1 px-2 align-top text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">
                                                {{ number_format($qtdAlocada, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        {{-- Coluna 3: Envio --}}
                                        <td class="py-1 px-2 align-top whitespace-nowrap">
                                            @if($dataEnvio)
                                                 <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase">
                                                     ENVIO: {{ $dataEnvio }}
                                                 </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8M8 17a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 13V9a2 2 0 012-2h10a2 2 0 012 2v4m-2 4h4a1 1 0 001-1v-2.586a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0016.586 10H15" />
                                                    </svg>
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        {{-- Coluna 4: Retorno --}}
                                        <td class="py-1 px-2 align-top whitespace-nowrap">
                                            @if($dataRetorno)
                                                 <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 border border-green-200 uppercase">
                                                     RETORNO: {{ $dataRetorno }}
                                                 </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        {{-- Coluna 5: Entrega (NOVO) --}}
                                        <td class="py-1 px-2 align-top whitespace-nowrap">
                                            <div class="flex items-center space-x-1">
                                                @if($dataEntrega)
                                                     <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200 uppercase">
                                                         P.ENTREGA: {{ $dataEntrega }}
                                                     </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-50 text-gray-400 italic">
                                                        Sem Data
                                                    </span>
                                                @endif


                                            </div>
                                        </td>
                                        {{-- Coluna 5: Observação --}}
                                        <td class="py-1 pl-2 align-top">
                                            @if($loc->pivot->observacao)
                                                @php
                                                    // Processar tags de cor nas observações
                                                    $obsTextoOriginalLoc = $loc->pivot->observacao;

                                                    // Aplicar formatação de cores
                                                    $obsTextoOriginalLoc = preg_replace('/<red>(.*?)<\/red>/i', '<span style="color: #DC2626; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<blue>(.*?)<\/blue>/i', '<span style="color: #2563EB; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<green>(.*?)<\/green>/i', '<span style="color: #16A34A; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<yellow>(.*?)<\/yellow>/i', '<span style="color: #CA8A04; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<orange>(.*?)<\/orange>/i', '<span style="color: #EA580C; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<purple>(.*?)<\/purple>/i', '<span style="color: #9333EA; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                                    $obsTextoOriginalLoc = preg_replace('/<pink>(.*?)<\/pink>/i', '<span style="color: #DB2777; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);

                                                    $textoCompletoLoc = $obsTextoOriginalLoc;

                                                    // Extrair texto limpo para verificar tamanho
                                                    $textoLimpoLoc = strip_tags($textoCompletoLoc);
                                                    $isTruncatedLoc = strlen($textoLimpoLoc) > 40;

                                                    // Para versão truncada, truncar o texto limpo e adicionar reticências
                                                    $obsTextoTruncadoLoc = $isTruncatedLoc ? Str::limit($textoLimpoLoc, 40) : $textoCompletoLoc;
                                                @endphp
                                                @if($isTruncatedLoc)
                                                    <span class="text-gray-600 dark:text-gray-400" x-data="{ expanded: false }">
                                                        <span x-show="!expanded">{!! $obsTextoTruncadoLoc !!}</span>
                                                        <span x-show="expanded" x-cloak>{!! $textoCompletoLoc !!}</span>
                                                        <button @click="expanded = !expanded" class="ml-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold focus:outline-none">
                                                            <span x-show="!expanded">[+]</span>
                                                            <span x-show="expanded" x-cloak>[-]</span>
                                                        </button>
                                                    </span>
                                                @else
                                                    <span class="text-gray-600 dark:text-gray-400">{!! $textoCompletoLoc !!}</span>
                                                @endif
                                            @endif
                                        </td>
                                        {{-- Coluna 6: Ações (Botões de Etapa) --}}
                                        <td class="py-1 pl-2 align-top text-right" style="min-width: 140px;">
                                            @php
                                                $podeGerenciarEtapa = auth()->user()->podeGerenciarEtapa($loc->id);
                                                $transicoes = collect([]);
                                                if ($etapaLinha) {
                                                    $transicoes = $etapaLinha->transicoesOrigem ?? collect([]);
                                                }
                                                // Definindo cores aqui localmente caso não venha do pai
                                                $btnCorClassesDesktop = [
                                                    'blue' => 'bg-blue-600 hover:bg-blue-700',
                                                    'green' => 'bg-green-600 hover:bg-green-700',
                                                    'yellow' => 'bg-yellow-500 hover:bg-yellow-600',
                                                    'red' => 'bg-red-600 hover:bg-red-700',
                                                    'purple' => 'bg-purple-600 hover:bg-purple-700',
                                                    'gray' => 'bg-gray-600 hover:bg-gray-700',
                                                    'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
                                                    'pink' => 'bg-pink-500 hover:bg-pink-600',
                                                    'orange' => 'bg-orange-500 hover:bg-orange-600',
                                                ];
                                            @endphp

                                            @if($podeGerenciarEtapa)
                                                <div class="flex flex-col gap-1 items-end">
                                                    @if($transicoes->count() > 0)
                                                        @foreach($transicoes as $transicao)
                                                            <form action="{{ route('produtos.localizacoes.avancar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST" class="w-full">
                                                                @csrf
                                                                <input type="hidden" name="etapa_id" value="{{ $transicao->etapa_destino_id }}">
                                                                <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                                <button type="submit" class="w-full py-1 px-2 rounded text-[10px] font-bold text-white shadow-sm {{ $btnCorClassesDesktop[$transicao->cor_botao] ?? 'bg-blue-600' }} hover:shadow-md transition-shadow">
                                                                    {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }} →
                                                                </button>
                                                            </form>
                                                        @endforeach
                                                    @elseif(!$etapaLinha)
                                                        {{-- Definir Etapa Inicial --}}
                                                        <div x-data="{ openMenu: false }" class="relative w-full">
                                                            <button @click="openMenu = !openMenu" type="button" class="w-full py-1 px-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded text-[10px] font-bold flex justify-between items-center transition-colors">
                                                                <span>Definir</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                            </button>
                                                            
                                                            <div x-show="openMenu" @click.away="openMenu = false" class="absolute right-0 top-full mt-1 w-40 bg-white dark:bg-slate-800 rounded shadow-lg border border-gray-200 z-50 overflow-hidden" style="display: none;">
                                                                @foreach($etapasProducao as $etapa)
                                                                    <form action="{{ route('produtos.localizacoes.definir-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="etapa_id" value="{{ $etapa->id }}">
                                                                        <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center gap-2 border-b border-gray-100 last:border-0">
                                                                            <span class="w-4 text-center">{{ $etapa->icone ?? '•' }}</span>
                                                                            <span>{{ $etapa->nome }}</span>
                                                                        </button>
                                                                    </form>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($etapaLinha)
                                                        <div class="flex gap-1 justify-end w-full">
                                                            @if(isset($loc->pivot->etapa_anterior_id) && $loc->pivot->etapa_anterior_id)
                                                                <form action="{{ route('produtos.localizacoes.voltar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                                    <button type="submit" class="text-[9px] text-gray-400 hover:text-gray-600 underline" title="Voltar etapa" onclick="return confirm('Voltar etapa?')">Voltar</button>
                                                                </form>
                                                                <span class="text-gray-300 text-[9px]">|</span>
                                                            @endif
                                                            <form action="{{ route('produtos.localizacoes.limpar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                                <button type="submit" class="text-[9px] text-red-300 hover:text-red-500 underline" title="Limpar etapa atual" onclick="return confirm('Limpar etapa?')">Limpar</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Linha de Total quando houver mais de 1 item --}}
                                @if($todasObsLocalizacoes->count() > 1)
                                    <tr class="border-t-2 border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-800">
                                        <td colspan="2" class="py-2 pr-2 font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">TOTAL:</td>
                                        <td class="py-2 px-2 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md font-bold bg-green-600 text-white">
                                                {{ number_format($totalQuantidades, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                @endif
                            </table>
                        @endif

                        {{-- Direcionamento Comercial --}}
                        @php
                            // Verificar se pelo menos um produto tem direcionamento comercial
                            $direcionamentoComercial = null;
                            foreach($produtosGrupo as $produto) {
                                if($produto->direcionamentoComercial) {
                                    $direcionamentoComercial = $produto->direcionamentoComercial;
                                    break;
                                }
                            }
                        @endphp

                        @if($direcionamentoComercial)
                            <div class="mt-2 pt-2 border-t border-gray-200 dark:border-slate-700">
                                <div class="text-xs">
                                    <span class="font-semibold text-purple-700 dark:text-purple-400">Dir. Comercial:</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $direcionamentoComercial->descricao }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- Observações do Produto (movidas para o final) --}}
                        @if($obs->count() > 0)
                            <div class="mt-2 pt-2 border-t border-dashed border-gray-300 dark:border-slate-600">
                                <div class="text-xs mb-1">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">📝 Observações:</span>
                                </div>
                                @foreach($obs as $observacao)
                                    @php
                                        // Processar observações (suporta HTML do Quill e tags customizadas)
                                        $obsTextoOriginal = $observacao->observacao;

                                        // Aplicar formatação de cores
                                        if (strpos($obsTextoOriginal, '<p>') === false && strpos($obsTextoOriginal, '<span') === false) {
                                            $obsTextoOriginal = preg_replace('/<red>(.*?)<\/red>/i', '<span style="color: #DC2626; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<blue>(.*?)<\/blue>/i', '<span style="color: #2563EB; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<green>(.*?)<\/green>/i', '<span style="color: #16A34A; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<yellow>(.*?)<\/yellow>/i', '<span style="color: #CA8A04; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<orange>(.*?)<\/orange>/i', '<span style="color: #EA580C; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<purple>(.*?)<\/purple>/i', '<span style="color: #9333EA; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                            $obsTextoOriginal = preg_replace('/<pink>(.*?)<\/pink>/i', '<span style="color: #DB2777; font-weight: 600;">$1</span>', $obsTextoOriginal);
                                        }

                                        $textoCompleto = $obsTextoOriginal;

                                        // Extrair texto limpo para verificar tamanho
                                        $textoLimpo = strip_tags($textoCompleto);
                                        $isTruncated = strlen($textoLimpo) > 80;

                                        // Para versão truncada, truncar o texto limpo e adicionar reticências
                                        $obsTextoTruncado = $isTruncated ? Str::limit($textoLimpo, 80) : $textoCompleto;
                                    @endphp
                                    @if($isTruncated)
                                        <div class="text-xs text-gray-700 mb-1" x-data="{ expanded: false }">
                                            <span x-show="!expanded">{!! $obsTextoTruncado !!}</span>
                                            <span x-show="expanded" x-cloak>{!! $textoCompleto !!}</span>
                                            <button @click="expanded = !expanded" class="ml-1 text-blue-600 hover:text-blue-800 font-semibold focus:outline-none">
                                                <span x-show="!expanded">[+]</span>
                                                <span x-show="expanded" x-cloak>[-]</span>
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-700 mb-1">
                                            {!! $textoCompleto !!}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if(!$temObservacoes && !$direcionamentoComercial)
                            <div class="text-xs text-gray-400 italic">-</div>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-sm text-center font-semibold text-gray-900" title="Quantidade total do produto">
                        {{ number_format($produtoPrincipal->quantidade ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2 text-sm">
                        @if($produtoPrincipal->status)
                            <span class="px-2 py-1 text-xs rounded-full {{ $produtoPrincipal->status->descricao == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $produtoPrincipal->status->descricao }}
                            </span>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
