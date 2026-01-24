<!-- Localizações -->
<div class="bg-gray-50 dark:bg-slate-800/50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    @php
        $canCreateProdutoLocalizacoes = auth()->user()->canCreate('produto_localizacao');
        $canUpdateProdutoLocalizacoes = auth()->user()->canUpdate('produto_localizacao');
        $canDeleteProdutoLocalizacoes = auth()->user()->canDelete('produto_localizacao');
        $canAnyProdutoLocalizacoes = $canUpdateProdutoLocalizacoes || $canDeleteProdutoLocalizacoes;
    @endphp
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Localizações do Produto</h3>
        @if($canCreateProdutoLocalizacoes)
            <button type="button" onclick="document.getElementById('modal-adicionar-localizacao').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-700 focus:outline-none focus:border-purple-700 focus:ring focus:ring-purple-300 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Adicionar Localização
            </button>
        @endif
    </div>

    @php
        $totalLocalizacoes = $produto->localizacoes->sum('pivot.quantidade');
        $quantidadeProduto = $produto->quantidade ?? 0;
        $divergencia = $totalLocalizacoes - $quantidadeProduto;
    @endphp

    @if($produto->localizacoes->count() > 0 && $divergencia != 0)
        <div class="mb-4 rounded-md p-4 {{ $divergencia > 0 ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'bg-red-50 border-l-4 border-red-400' }}">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 {{ $divergencia > 0 ? 'text-yellow-400' : 'text-red-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm {{ $divergencia > 0 ? 'text-yellow-800' : 'text-red-800' }}">
                        <strong>Atenção:</strong>
                        @if($divergencia > 0)
                            O total das localizações (<strong>{{ number_format($totalLocalizacoes, 0, ',', '.') }}</strong>) está
                            <strong>{{ number_format(abs($divergencia), 0, ',', '.') }} unidade(s) acima</strong>
                            da quantidade pretendida do produto (<strong>{{ number_format($quantidadeProduto, 0, ',', '.') }}</strong>).
                        @else
                            O total das localizações (<strong>{{ number_format($totalLocalizacoes, 0, ',', '.') }}</strong>) está
                            <strong>{{ number_format(abs($divergencia), 0, ',', '.') }} unidade(s) abaixo</strong>
                            da quantidade pretendida do produto (<strong>{{ number_format($quantidadeProduto, 0, ',', '.') }}</strong>).
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @elseif($produto->localizacoes->count() > 0 && $divergencia == 0)
        <div class="hidden md:block mb-4 rounded-md bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">
                        <strong>Perfeito!</strong> O total das localizações (<strong>{{ number_format($totalLocalizacoes, 0, ',', '.') }}</strong>)
                        está de acordo com a quantidade pretendida do produto.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($produto->localizacoes->count() > 0)
        <!-- Tabela Desktop -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Localização</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordem Produção</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantidade</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prev. Facção</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Envio Facção</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Retorno Facção</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Entrega Prevista Facção</th>
                        <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Concluído</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Etapa Atual</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Próximas Etapas</th>
                        @if($canAnyProdutoLocalizacoes)
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($produto->localizacoes as $localizacao)
                        @php
                            $etapaAtualId = $localizacao->pivot->etapa_atual_id;
                            $etapaAnteriorId = $localizacao->pivot->etapa_anterior_id;
                            $etapaAtual = $etapaAtualId ? $etapasProducao->firstWhere('id', $etapaAtualId) : null;
                            $transicoes = $etapaAtual ? ($etapaAtual->transicoesOrigem ?? collect([])) : collect([]);
                            $podeGerenciarEtapa = auth()->user()->podeGerenciarEtapa($localizacao->id);
                            $dataEntregaRaw = $localizacao->pivot->data_entrega_faccao;
                            $possuiDataEntrega = !empty($dataEntregaRaw) && $dataEntregaRaw != '0000-00-00';

                            $corClasses = [
                                'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'green' => 'bg-green-100 text-green-800 border-green-200',
                                'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'red' => 'bg-red-100 text-red-800 border-red-200',
                                'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                                'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
                                'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                            ];
                            $btnCorClasses = [
                                'blue' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm',
                                'green' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm',
                                'yellow' => 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm',
                                'red' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
                                'purple' => 'bg-purple-600 hover:bg-purple-700 text-white shadow-sm',
                                'gray' => 'bg-gray-600 hover:bg-gray-700 text-white shadow-sm',
                                'indigo' => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm',
                                'pink' => 'bg-pink-500 hover:bg-pink-600 text-white shadow-sm',
                                'orange' => 'bg-orange-500 hover:bg-orange-600 text-white shadow-sm',
                            ];
                            // Fallback para cores não mapeadas
                            $defaultCorClass = 'bg-gray-100 text-gray-800 border-gray-200';
                            $defaultBtnClass = 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm';
                        @endphp
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $localizacao->nome_localizacao }}
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                @if($localizacao->pivot->ordem_producao)
                                    <a href="{{ $localizacao->pivot->ordem_producao_url }}" target="_blank" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                        {{ $localizacao->pivot->ordem_producao }}
                                    </a>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($localizacao->pivot->quantidade, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($localizacao->pivot->data_prevista_faccao)
                                    {{ is_string($localizacao->pivot->data_prevista_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_prevista_faccao)->format('d/m/Y') : $localizacao->pivot->data_prevista_faccao->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($localizacao->pivot->data_envio_faccao)
                                    {{ is_string($localizacao->pivot->data_envio_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_envio_faccao)->format('d/m/Y') : $localizacao->pivot->data_envio_faccao->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($localizacao->pivot->data_retorno_faccao)
                                    {{ is_string($localizacao->pivot->data_retorno_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_retorno_faccao)->format('d/m/Y') : $localizacao->pivot->data_retorno_faccao->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-2">
                                    @if($localizacao->pivot->data_entrega_faccao)
                                        <span class="text-gray-900 font-medium bg-yellow-50 px-2 py-0.5 rounded border border-yellow-200">
                                            {{ is_string($localizacao->pivot->data_entrega_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_entrega_faccao)->format('d/m/Y') : $localizacao->pivot->data_entrega_faccao->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif

                                    @php
                                        $podeEditarEntrega = auth()->user()->podeGerenciarEtapa($localizacao->id);
                                    @endphp

                                    @if($podeEditarEntrega)
                                        <button type="button"
                                            onclick="abrirModalDataEntrega({{ $localizacao->pivot->id }}, {{ Js::from($localizacao->pivot->data_entrega_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->nome_localizacao) }})"
                                            class="text-blue-600 hover:text-blue-800 transition-colors p-1" title="Editar Data de Entrega">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-center">
                                @if($localizacao->pivot->concluido == 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 inline-block" viewBox="0 0 20 20" fill="currentColor" title="Concluído">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm" x-data="{ showEtapaMenu: false }">
                                @if($etapaAtual)
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $corClasses[$etapaAtual->cor] ?? $defaultCorClass }}">
                                                {{ $etapaAtual->icone ?? '' }} {{ $etapaAtual->nome }}
                                            </span>
                                            <a href="{{ route('produtos.localizacoes.historico-etapas', [$produto->id, $localizacao->pivot->id]) }}"
                                               class="text-gray-400 hover:text-indigo-600 transition-colors" title="Ver histórico">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </a>
                                        </div>
                                        @if($podeGerenciarEtapa)
                                            <div class="flex items-center gap-2 opacity-60 hover:opacity-100 transition-opacity">
                                                @if($etapaAnteriorId)
                                                    <form action="{{ route('produtos.localizacoes.voltar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-[9px] font-bold text-gray-400 hover:text-gray-700 hover:underline uppercase" onclick="return confirm('Voltar etapa?')">
                                                            Voltar
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('produtos.localizacoes.limpar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[9px] font-bold text-red-300 hover:text-red-500 hover:underline uppercase" onclick="return confirm('Isso irá resetar a etapa atual para Não Definida, mas manterá as quantidades e datas. Confirmar?')">
                                                        Limpar
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="relative" @click.away="showEtapaMenu = false">
                                        @if($podeGerenciarEtapa)
                                            <button type="button" @click="showEtapaMenu = !showEtapaMenu" class="px-2 py-1 text-xs rounded border border-dashed bg-gray-100 hover:bg-gray-200 border-gray-300">+ Definir</button>
                                        @endif
                                        <div x-show="showEtapaMenu" x-transition class="absolute z-20 mt-1 w-40 bg-white rounded-md shadow-lg border border-gray-200">
                                            @foreach($etapasProducao as $etapa)
                                                <form action="{{ route('produtos.localizacoes.definir-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="etapa_id" value="{{ $etapa->id }}">
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-50 flex items-center gap-1">{{ $etapa->icone ?? '' }} {{ $etapa->nome }}</button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if($transicoes->count() > 0 && $podeGerenciarEtapa)
                                    <div class="space-y-1">
                                        @foreach($transicoes as $transicao)
                                            <form action="{{ route('produtos.localizacoes.avancar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="etapa_id" value="{{ $transicao->etapa_destino_id }}">
                                                <button type="submit" class="w-full text-left px-2 py-0.5 rounded text-[10px] font-bold {{ $btnCorClasses[$transicao->cor_botao] ?? $defaultBtnClass }}">→ {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            @if($canAnyProdutoLocalizacoes)
                                <td class="px-4 py-2 whitespace-nowrap text-sm space-x-3">
                                    @if($canUpdateProdutoLocalizacoes)
                                        <button type="button" onclick="abrirModalEditarLocalizacao({{ $localizacao->pivot->id }}, {{ $localizacao->id }}, {{ Js::from($localizacao->nome_localizacao) }}, {{ $localizacao->pivot->quantidade }}, {{ Js::from($localizacao->pivot->data_prevista_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->ordem_producao) }}, {{ Js::from($localizacao->pivot->observacao) }}, {{ $localizacao->pivot->concluido }}, {{ Js::from($localizacao->pivot->data_envio_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->data_retorno_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->data_entrega_faccao?->format('Y-m-d')) }})" class="text-indigo-600 hover:text-indigo-800">Editar</button>
                                    @endif
                                    @if($canDeleteProdutoLocalizacoes)
                                        <form action="{{ route('produtos.localizacoes.destroy', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja REMOVER COMPLETAMENTE esta fábrica e todos os seus dados de produção?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir toda a alocação">Remover Fábrica</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-slate-700">
                    <tr class="border-t-2 border-gray-300 dark:border-slate-600">
                        <td colspan="2" class="px-4 py-2 text-sm font-medium dark:text-white">Total:</td>
                        <td class="px-4 py-2 text-sm font-bold dark:text-white">{{ number_format($totalLocalizacoes, 0, ',', '.') }}</td>
                        <td colspan="8"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Cards Mobile - Escondido em telas >= 1024px -->
        <div class="space-y-4" style="display: block;" id="cards-mobile-localizacoes">
        <style>
            @media (min-width: 1024px) {
                #cards-mobile-localizacoes { display: none !important; }
            }
        </style>
            @foreach($produto->localizacoes as $localizacao)
                @php
                    $etapaAtualId = $localizacao->pivot->etapa_atual_id;
                    $etapaAnteriorId = $localizacao->pivot->etapa_anterior_id;
                    $etapaAtual = $etapaAtualId ? $etapasProducao->firstWhere('id', $etapaAtualId) : null;
                    $transicoes = $etapaAtual ? ($etapaAtual->transicoesOrigem ?? collect([])) : collect([]);
                    $podeGerenciarEtapa = auth()->user()->podeGerenciarEtapa($localizacao->id);
                    $podeEditarEntrega = auth()->user()->podeGerenciarEtapa($localizacao->id);

                    $corClasses = [
                        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'green' => 'bg-green-100 text-green-800 border-green-200',
                        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'red' => 'bg-red-100 text-red-800 border-red-200',
                        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                        'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
                        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                    ];
                    $btnCorClasses = [
                        'blue' => 'bg-blue-600 text-white shadow-sm',
                        'green' => 'bg-green-600 text-white shadow-sm',
                        'yellow' => 'bg-yellow-500 text-white shadow-sm',
                        'red' => 'bg-red-600 text-white shadow-sm',
                        'purple' => 'bg-purple-600 text-white shadow-sm',
                        'gray' => 'bg-gray-600 text-white shadow-sm',
                        'indigo' => 'bg-indigo-600 text-white shadow-sm',
                        'pink' => 'bg-pink-500 text-white shadow-sm',
                        'orange' => 'bg-orange-500 text-white shadow-sm',
                    ];
                    $defaultCorClass = 'bg-gray-100 text-gray-800 border-gray-200';
                    $defaultBtnClass = 'bg-blue-600 text-white shadow-sm';
                @endphp
                <div x-data="{ showEtapaMenu: false }" class="bg-white border rounded-xl shadow-sm overflow-hidden border-purple-100">
                    <!-- Header -->
                    <div class="bg-purple-50 px-4 py-3 border-b border-purple-100 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold text-purple-700 uppercase tracking-wider block">Localização</span>
                            <span class="font-bold text-gray-900">{{ $localizacao->nome_localizacao }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider block">OP</span>
                            @if($localizacao->pivot->ordem_producao)
                                <a href="{{ $localizacao->pivot->ordem_producao_url }}" target="_blank" class="font-bold text-blue-800 hover:underline">{{ $localizacao->pivot->ordem_producao }}</a>
                            @else
                                <span class="font-bold text-gray-400">—</span>
                            @endif
                        </div>
                    </div>

                    <!-- Corpo -->
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Quantidade</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($localizacao->pivot->quantidade, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Etapa Atual</span>
                                @if($etapaAtual)
                                    <div class="flex items-center gap-1">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $corClasses[$etapaAtual->cor] ?? $defaultCorClass }}">
                                            {{ $etapaAtual->icone ?? '' }} {{ $etapaAtual->nome }}
                                        </span>
                                        <a href="{{ route('produtos.localizacoes.historico-etapas', [$produto->id, $localizacao->pivot->id]) }}"
                                           class="text-gray-400 hover:text-indigo-600 transition-colors" title="Ver histórico">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">Não definida</span>
                                @endif
                            </div>
                        </div>

                        <!-- Datas -->
                        <div class="bg-gray-50 rounded-lg p-3 grid grid-cols-2 gap-y-3 gap-x-4">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Prev. Facção</span>
                                <span class="text-xs text-gray-700">{{ $localizacao->pivot->data_prevista_faccao ? \Carbon\Carbon::parse($localizacao->pivot->data_prevista_faccao)->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Envio</span>
                                <span class="text-xs text-gray-700">{{ $localizacao->pivot->data_envio_faccao ? \Carbon\Carbon::parse($localizacao->pivot->data_envio_faccao)->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Entrega Prevista</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs font-bold text-yellow-700">{{ $localizacao->pivot->data_entrega_faccao ? \Carbon\Carbon::parse($localizacao->pivot->data_entrega_faccao)->format('d/m/Y') : '—' }}</span>
                                    @if($podeEditarEntrega)
                                        <button type="button" onclick="abrirModalDataEntrega({{ $localizacao->pivot->id }}, {{ Js::from($localizacao->pivot->data_entrega_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->nome_localizacao) }})" class="text-blue-500 p-1.5 bg-blue-50 rounded-lg border border-blue-100 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Status</span>
                                @if($localizacao->pivot->concluido == 1)
                                    <span class="text-[10px] font-bold text-green-600 bg-green-100 px-1.5 py-0.5 rounded">CONCLUÍDO</span>
                                @else
                                    <span class="text-[10px] font-bold text-gray-400 bg-gray-200 px-1.5 py-0.5 rounded">EM ANDAMENTO</span>
                                @endif
                            </div>
                        </div>

                        <!-- Controles de Etapa -->
                        @if($podeGerenciarEtapa)
                            <div class="pt-3 border-t border-gray-200 space-y-2 relative">
                                @if($transicoes->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($transicoes as $transicao)
                                            <form action="{{ route('produtos.localizacoes.avancar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="flex-1 min-w-[120px]">
                                                @csrf
                                                <input type="hidden" name="etapa_id" value="{{ $transicao->etapa_destino_id }}">
                                                <button type="submit" class="w-full py-2 rounded-lg text-xs font-bold {{ $btnCorClasses[$transicao->cor_botao] ?? $defaultBtnClass }}">
                                                    → {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                @elseif(!$etapaAtual)
                                    <button type="button" @click="showEtapaMenu = !showEtapaMenu" class="w-full py-2 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-lg text-xs font-bold">
                                        + Definir Etapa
                                    </button>
                                    <div x-show="showEtapaMenu" @click.away="showEtapaMenu = false" class="absolute left-0 right-0 bottom-full mb-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 divide-y divide-gray-100">
                                        @foreach($etapasProducao as $etapa)
                                            <form action="{{ route('produtos.localizacoes.definir-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="etapa_id" value="{{ $etapa->id }}">
                                                <button type="submit" class="w-full text-left px-4 py-3 text-xs hover:bg-gray-50 flex items-center gap-2">
                                                    <span>{{ $etapa->icone ?? '•' }}</span>
                                                    <span>{{ $etapa->nome }}</span>
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                @endif
                                @if($etapaAtual)
                                    <div class="flex flex-col items-center gap-2">
                                        @if($etapaAnteriorId)
                                            <form action="{{ route('produtos.localizacoes.voltar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 underline" onclick="return confirm('Voltar etapa?')">← Voltar Etapa</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('produtos.localizacoes.limpar-etapa', [$produto->id, $localizacao->pivot->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-500 text-xs underline" onclick="return confirm('Resetar etapa atual?')">Limpar Etapa</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Rodapé Ações -->
                    @if($canAnyProdutoLocalizacoes)
                        <div class="bg-gray-50 px-4 py-3 border-t flex justify-end gap-6">
                            @if($canUpdateProdutoLocalizacoes)
                                <button type="button" onclick="abrirModalEditarLocalizacao({{ $localizacao->pivot->id }}, {{ $localizacao->id }}, {{ Js::from($localizacao->nome_localizacao) }}, {{ $localizacao->pivot->quantidade }}, {{ Js::from($localizacao->pivot->data_prevista_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->ordem_producao) }}, {{ Js::from($localizacao->pivot->observacao) }}, {{ $localizacao->pivot->concluido }}, {{ Js::from($localizacao->pivot->data_envio_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->data_retorno_faccao?->format('Y-m-d')) }}, {{ Js::from($localizacao->pivot->data_entrega_faccao?->format('Y-m-d')) }})" class="text-indigo-600 font-bold text-xs uppercase hover:text-indigo-800">Editar</button>
                            @endif
                            @if($canDeleteProdutoLocalizacoes)
                                <form action="{{ route('produtos.localizacoes.destroy', [$produto->id, $localizacao->pivot->id]) }}" method="POST" onsubmit="return confirm('Remover esta fábrica?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 font-bold text-xs uppercase hover:text-red-800">Remover</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Total Mobile -->
            <div class="bg-indigo-50 border-2 border-indigo-100 rounded-xl p-4 shadow-sm flex justify-between items-center">
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Total em Produção</span>
                <span class="text-xl font-black text-indigo-900">{{ number_format($totalLocalizacoes, 0, ',', '.') }}</span>
            </div>
        </div>
    @endif
</div>
