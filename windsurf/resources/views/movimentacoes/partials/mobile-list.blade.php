<div class="md:hidden space-y-4">
    @forelse($movimentacoes as $movimentacao)
        <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-4 border border-gray-200 dark:border-slate-800">
            <!-- Produto e Status -->
            <div class="mb-3 border-b border-gray-100 dark:border-slate-800 pb-2">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-900 dark:text-white">
                        @if($movimentacao->produto)
                            <div>{{ $movimentacao->produto->referencia }}</div>
                            @if($movimentacao->produto->data_prevista_producao)
                                <div class="text-blue-600 dark:text-blue-400 text-xs font-semibold">
                                    Prod: {{ $movimentacao->produto->data_prevista_producao->format('d/m/Y') }}
                                </div>
                            @endif
                        @else
                            <span class="text-red-500">Produto não encontrado</span>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($movimentacao->produto && $movimentacao->produto->status)
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    {{ $movimentacao->produto->status->descricao }}
                                </span>
                            </div>
                        @else
                            <div>
                                <span class="text-gray-400 dark:text-gray-500">N/A</span>
                            </div>
                        @endif

                        @if($movimentacao->produto && $movimentacao->produto->direcionamentoComercial)
                            <div class="mt-1 text-[11px] text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">
                                    {{ $movimentacao->produto->direcionamentoComercial->descricao }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                @if($movimentacao->produto)
                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate" title="{{ $movimentacao->produto->descricao }}">{{ Str::limit($movimentacao->produto->descricao, 40, '...') }}</div>
                @else
                    <div class="text-sm text-gray-400 dark:text-gray-500">N/A</div>
                @endif
            </div>

            <!-- Informações principais -->
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">LOCALIZAÇÃO</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">SITUAÇÃO</div>
                    @if($movimentacao->situacao)
                        <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            {{ $movimentacao->situacao->descricao ?? 'N/A' }}
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">N/A</span>
                    @endif
                </div>
            </div>

            <!-- Tipo e Dias -->
            <div class="flex justify-between mb-3">
                <div class="flex-1">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">TIPO</div>
                    <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200' }}">
                        {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                    </span>
                </div>
                <div class="flex-1 text-right">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">DIAS</div>
                    @php
                        $diasEntre = null;
                        $prazoExcedido = false;
                        $prazoSetor = null;

                        if ($movimentacao->data_entrada) {
                            if ($movimentacao->data_saida) {
                                $diasEntre = \App\Helpers\MovimentacaoHelper::calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                            } else {
                                $diasEntre = \App\Helpers\MovimentacaoHelper::calcularDiasUteis($movimentacao->data_entrada, now());
                            }

                            // Verificar prazo: prioridade para situação, depois localização
                            if ($movimentacao->situacao && $movimentacao->situacao->prazo) {
                                // Situação tem prazo definido (prioridade)
                                $prazoExcedido = $diasEntre > $movimentacao->situacao->prazo;
                                $prazoSetor = $movimentacao->situacao->prazo;
                            } elseif ($movimentacao->localizacao && $movimentacao->localizacao->prazo) {
                                // Usa prazo da localização se situação não tiver
                                $prazoExcedido = $diasEntre > $movimentacao->localizacao->prazo;
                                $prazoSetor = $movimentacao->localizacao->prazo;
                            }
                        }
                    @endphp

                    @if($diasEntre !== null)
                        <div class="text-sm {{ $prazoExcedido ? 'text-red-600 dark:text-red-400 font-bold' : 'text-blue-600 dark:text-blue-400' }}">
                            {{ $diasEntre }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                            @if($prazoSetor)
                                <span class="text-xs {{ $prazoExcedido ? 'text-red-500 dark:text-red-400' : 'text-blue-500 dark:text-blue-400' }}">(Prazo: {{ $prazoSetor }})</span>
                            @endif
                        </div>
                    @else
                        <span class="text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </div>
            </div>

            <!-- Datas - Agora em uma única linha -->
            <div class="flex justify-between mb-3 text-right">
                <div class="flex-1 text-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">ENTRADA</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        @if($movimentacao->data_entrada)
                            <div>{{ $movimentacao->data_entrada->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $movimentacao->data_entrada->format('H:i') }}</div>
                        @else
                            N/A
                        @endif
                    </div>
                </div>

                <div class="flex-1 text-center border-l border-r border-gray-200 dark:border-slate-700 px-2">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">SAÍDA</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        @if($movimentacao->data_saida)
                            <div>{{ $movimentacao->data_saida->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $movimentacao->data_saida->format('H:i') }}</div>
                        @else
                            N/A
                        @endif
                    </div>
                </div>

                <div class="flex-1 text-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">DEVOLUÇÃO</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        @if($movimentacao->data_devolucao)
                            <div>{{ $movimentacao->data_devolucao->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $movimentacao->data_devolucao->format('H:i') }}</div>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-100 dark:border-slate-800">
                @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
                <a href="{{ route('movimentacoes.show', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-view">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                @endif
                @if(auth()->user() && auth()->user()->canUpdate('movimentacoes'))
                <a href="{{ route('movimentacoes.edit', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                @endif
                @if($movimentacao->anexo && auth()->user() && auth()->user()->canRead('movimentacoes'))
                <button type="button" onclick="openImageModal('{{ $movimentacao->anexo_url }}', {{ $movimentacao->id }})" class="btn-action-view" title="Ver anexo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
                @endif
                @if(auth()->user() && auth()->user()->canDelete('movimentacoes'))
                <form action="{{ route('movimentacoes.destroy', $movimentacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action-delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-4 text-center text-gray-500 dark:text-gray-400">
            Nenhuma movimentação encontrada.
        </div>
    @endforelse
</div>
