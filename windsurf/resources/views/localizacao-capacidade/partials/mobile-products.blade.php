<div class="md:hidden space-y-4">
    @foreach($produtosAgrupados as $chave => $produtosGrupo)
        @php
            $produtoPrincipal = $produtosGrupo->first();
            
            // Identificar todas as etapas presentes neste grupo de produtos
            $etapaIdsNoGrupo = $produtosGrupo->flatMap(function($p) {
                return $p->localizacoes->pluck('pivot.etapa_atual_id');
            })->unique()->filter()->toArray();

            $etapasNoGrupo = $etapasProducao->whereIn('id', $etapaIdsNoGrupo);
        @endphp
        
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-4 shadow-sm">
            {{-- Header: Referência, Descrição e Badge Data --}}
            <div class="flex justify-between items-start mb-2">
                <div>
                    <a href="{{ route('produtos.show', $produtoPrincipal->id) }}?back_url={{ urlencode(request()->fullUrl()) }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                        {{ $produtoPrincipal->referencia }}
                    </a>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $produtoPrincipal->descricao }}
                    </div>
                </div>
                
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
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">PREV</span>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $dataPrevista }}</span>
                    </div>
                @endif
            </div>

            {{-- Badges: Marca, Grupo, Status --}}
            <div class="flex flex-wrap gap-2 mb-3">
                @if($produtoPrincipal->marca)
                    @if($produtoPrincipal->marca->cor_fundo && $produtoPrincipal->marca->cor_fonte)
                        <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded-full" style="background-color: {{ $produtoPrincipal->marca->cor_fundo }}; color: {{ $produtoPrincipal->marca->cor_fonte }};">
                            {{ $produtoPrincipal->marca->nome_marca }}
                        </span>
                    @else
                        <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $produtoPrincipal->marca->nome_marca }}
                        </span>
                    @endif
                @endif

                <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700">
                    {{ $produtoPrincipal->grupoProduto->descricao ?? 'N/A' }}
                </span>

                @if($produtoPrincipal->status)
                    <span class="px-2 py-0.5 inline-flex text-[10px] font-semibold rounded-full {{ $produtoPrincipal->status->descricao == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $produtoPrincipal->status->descricao }}
                    </span>
                @endif
            </div>
            
            <div class="border-t border-gray-100 dark:border-slate-800 my-2"></div>
            
            {{-- Detalhes de Produção --}}
            @php
                // Carregar observações do produto (apenas uma vez)
                $obs = \App\Models\ProdutoObservacao::where('produto_id', $produtoPrincipal->id)->get();

                // Carregar todas as observações das localizações de todas as alocações
                $todasObsLocalizacoes = collect();
                foreach($produtosGrupo as $produto) {
                    $obsLoc = $produto->localizacoes->filter(function($loc) {
                        return !empty($loc->pivot->ordem_producao) || !empty($loc->pivot->observacao);
                    });
                    $todasObsLocalizacoes = $todasObsLocalizacoes->merge($obsLoc);
                }

                // Remover duplicatas baseadas em ordem_producao + observacao
                $todasObsLocalizacoes = $todasObsLocalizacoes->unique(function($loc) {
                    return $loc->pivot->ordem_producao . '|' . $loc->pivot->observacao;
                });

                $temObservacoes = $obs->count() > 0 || $todasObsLocalizacoes->count() > 0;
            @endphp
            
            @if($todasObsLocalizacoes->count() > 0)
                <div class="space-y-3 mb-3">
                    @php $totalQuantidades = 0; @endphp
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
                        
                        <div class="bg-gray-50 dark:bg-slate-800/50 rounded p-2 text-xs">
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center gap-1">
                                    @if($loc->pivot->ordem_producao)
                                        <a href="{{ $loc->pivot->ordem_producao_url }}" target="_blank" class="font-bold text-blue-700 dark:text-blue-400 hover:underline">
                                            OP: {{ $loc->pivot->ordem_producao }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Sem OP</span>
                                    @endif
                                    
                                    @if($loc->pivot->concluido == 1)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                
                                <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 px-1.5 py-0.5 rounded font-bold">
                                    {{ number_format($qtdAlocada, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            {{-- Etapa --}}
                            @php
                                $etapaLinhaId = $loc->pivot->etapa_atual_id;
                                $etapaLinha = $etapaLinhaId ? $etapasProducao->firstWhere('id', $etapaLinhaId) : null;
                            @endphp
                            @if($etapaLinha)
                                <div class="mb-1">
                                    <span class="inline-flex items-center px-1 py-0.5 rounded text-[9px] font-bold border {{ $corClasses[$etapaLinha->cor] ?? 'bg-gray-100 text-gray-800' }} uppercase">
                                        {{ $etapaLinha->icone ?? '' }} {{ $etapaLinha->nome }}
                                    </span>
                                    <a href="{{ route('produtos.localizacoes.historico-etapas', [$produtoPrincipal->id, $loc->pivot->id]) }}"
                                       class="ml-1 text-gray-400 hover:text-indigo-600 inline-block align-middle" title="Ver histórico de etapas">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                            
                            {{-- Datas Grid (Envio, Retorno, Entrega) --}}
                            <div class="grid grid-cols-3 gap-1 mt-1.5">
                                <div class="text-center">
                                    <div class="text-[9px] text-gray-500 uppercase">Envio</div>
                                    @if($dataEnvio)
                                         <div class="font-bold text-yellow-700 dark:text-yellow-400">{{ $dataEnvio }}</div>
                                    @else
                                         <div class="text-gray-400">-</div>
                                    @endif
                                </div>
                                <div class="text-center border-l border-gray-200 dark:border-slate-700">
                                    <div class="text-[9px] text-gray-500 uppercase">Retorno</div>
                                    @if($dataRetorno)
                                         <div class="font-bold text-green-700 dark:text-green-400">{{ $dataRetorno }}</div>
                                    @else
                                         <div class="text-gray-400">-</div>
                                    @endif
                                </div>
                                <div class="text-center border-l border-gray-200 dark:border-slate-700">
                                    <div class="text-[9px] text-gray-500 uppercase">Entrega</div>
                                    @if($dataEntrega)
                                         <div class="font-bold text-purple-700 dark:text-purple-400">{{ $dataEntrega }}</div>
                                    @else
                                         <div class="text-gray-400">-</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Controles de Etapa --}}
                            @php
                                $podeGerenciarEtapa = auth()->user()->podeGerenciarEtapa($loc->id);
                                $transicoes = collect([]);
                                if ($etapaLinha) {
                                    $transicoes = $etapaLinha->transicoesOrigem ?? collect([]);
                                }
                                
                                $btnCorClasses = [
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
                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-slate-700">
                                    {{-- 1. Transições (Botões de Ação Principal) --}}
                                    @if($transicoes && $transicoes->count() > 0)
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            @foreach($transicoes as $transicao)
                                                <form action="{{ route('produtos.localizacoes.avancar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST" class="flex-1 min-w-[100px]">
                                                    @csrf
                                                    <input type="hidden" name="etapa_id" value="{{ $transicao->etapa_destino_id }}">
                                                    <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                    <button type="submit" class="w-full py-1.5 rounded text-[10px] font-bold text-white shadow-sm {{ $btnCorClasses[$transicao->cor_botao] ?? 'bg-blue-600' }}">
                                                        {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }}
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    @elseif(!$etapaLinha)
                                        {{-- 2. Definir Etapa Inicial --}}
                                        <div x-data="{ openMenu: false }" class="relative mb-2">
                                            <button @click="openMenu = !openMenu" type="button" class="w-full py-2 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 text-indigo-700 dark:text-indigo-300 rounded text-xs font-bold flex justify-center items-center gap-2 hover:bg-indigo-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                DEFINIR ETAPA
                                            </button>
                                            
                                            <div x-show="openMenu" @click.away="openMenu = false" class="absolute left-0 right-0 bottom-full mb-1 bg-white dark:bg-slate-800 rounded shadow-xl border border-gray-200 dark:border-slate-600 z-50 overflow-hidden max-h-48 overflow-y-auto" style="display: none;">
                                                @foreach($etapasProducao as $etapa)
                                                    <form action="{{ route('produtos.localizacoes.definir-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="etapa_id" value="{{ $etapa->id }}">
                                                        <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                        <button type="submit" class="w-full text-left px-4 py-3 text-xs hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 last:border-0 text-gray-700 dark:text-gray-200">
                                                            <span class="w-5 text-center">{{ $etapa->icone ?? '•' }}</span>
                                                            <span class="font-medium">{{ $etapa->nome }}</span>
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 3. Ações Secundárias (Voltar / Limpar) --}}
                                    @if($etapaLinha)
                                        <div class="flex justify-center items-center gap-4">
                                            @if(isset($loc->pivot->etapa_anterior_id) && $loc->pivot->etapa_anterior_id)
                                                <form action="{{ route('produtos.localizacoes.voltar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                    <button type="submit" class="text-[10px] text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 flex items-center gap-1 py-1 px-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" onclick="return confirm('Voltar etapa?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                                                        Voltar
                                                    </button>
                                                </form>
                                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                            @endif

                                            <form action="{{ route('produtos.localizacoes.limpar-etapa', [$produtoPrincipal->id, $loc->pivot->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                <button type="submit" class="text-[10px] text-red-400 hover:text-red-600 flex items-center gap-1 py-1 px-2 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" onclick="return confirm('Limpar etapa?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                    Limpar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            {{-- Observação da Localização --}}
                            @if($loc->pivot->observacao)
                                @php
                                    $obsTextoOriginalLoc = $loc->pivot->observacao;
                                    // ...existing regex logic...
                                    $obsTextoOriginalLoc = preg_replace('/<red>(.*?)<\/red>/i', '<span style="color: #DC2626; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<blue>(.*?)<\/blue>/i', '<span style="color: #2563EB; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<green>(.*?)<\/green>/i', '<span style="color: #16A34A; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<yellow>(.*?)<\/yellow>/i', '<span style="color: #CA8A04; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<orange>(.*?)<\/orange>/i', '<span style="color: #EA580C; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<purple>(.*?)<\/purple>/i', '<span style="color: #9333EA; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);
                                    $obsTextoOriginalLoc = preg_replace('/<pink>(.*?)<\/pink>/i', '<span style="color: #DB2777; font-weight: 600;">$1</span>', $obsTextoOriginalLoc);

                                    $textoCompletoLoc = $obsTextoOriginalLoc;
                                @endphp
                                <div class="mt-1.5 pt-1 border-t border-gray-200 dark:border-slate-700 text-[10px] italic text-gray-600 dark:text-gray-400 break-words">
                                    Obs: {!! $textoCompletoLoc !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    {{-- Total Quantidade --}}
                    @if($todasObsLocalizacoes->count() > 1)
                        <div class="flex justify-between items-center px-2 py-1 bg-gray-100 dark:bg-slate-800 rounded font-bold text-xs">
                            <span class="text-gray-700 dark:text-gray-300">TOTAL PRODUTO:</span>
                            <span class="text-green-600 dark:text-green-400">{{ number_format($totalQuantidades, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Direcionamento Comercial --}}
            @php
                $direcionamentoComercial = null;
                foreach($produtosGrupo as $produto) {
                    if($produto->direcionamentoComercial) {
                        $direcionamentoComercial = $produto->direcionamentoComercial;
                        break;
                    }
                }
            @endphp
            @if($direcionamentoComercial)
                <div class="mb-2 text-xs">
                    <span class="font-bold text-purple-700 dark:text-purple-400">Dir. Comercial:</span>
                    <span class="text-gray-600 dark:text-gray-400">{{ $direcionamentoComercial->descricao }}</span>
                </div>
            @endif

            {{-- Observações do Produto --}}
            @if($obs->count() > 0)
                <div class="text-xs bg-yellow-50 dark:bg-yellow-900/10 p-2 rounded border border-yellow-100 dark:border-yellow-900/30">
                    <div class="font-bold text-yellow-800 dark:text-yellow-500 mb-1">📝 Observações:</div>
                    @foreach($obs as $observacao)
                        <div class="mb-1 last:mb-0 text-gray-700 dark:text-gray-300">
                             {!! $observacao->observacao !!} 
                        </div>
                    @endforeach
                </div>
            @endif
            
            <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100 dark:border-slate-800">
               <span class="text-xs text-gray-500 uppercase font-bold">Qtd Total</span>
               <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($produtoPrincipal->quantidade ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    @endforeach
</div>
