<div class="hidden md:block overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="table-base table-compact">
        <thead class="table-header">
            <tr>
                <th scope="col" class="table-header-cell">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'produto', 'direction' => request('sort') == 'produto' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                        Produto
                        @if(request('sort') == 'produto')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-center">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'produto.status', 'direction' => request('sort') == 'produto.status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center hover:text-gray-700">
                        Status
                        @if(request('sort') == 'status')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-center">
                    Concluído
                </th>
                <th scope="col" class="table-header-cell">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'localizacao', 'direction' => request('sort') == 'localizacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                        Localização
                        @if(request('sort') == 'localizacao')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'tipo', 'direction' => request('sort') == 'tipo' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                        Tipo
                        @if(request('sort') == 'tipo')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell min-w-[100px]">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'situacao', 'direction' => request('sort') == 'situacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                        Situação
                        @if(request('sort') == 'situacao')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-right min-w-[90px]">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_entrada', 'direction' => request('sort') == 'data_entrada' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                        Data Entrada
                        @if(request('sort') == 'data_entrada')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-right min-w-[90px]">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_saida', 'direction' => request('sort') == 'data_saida' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                        Data Conclusão
                        @if(request('sort') == 'data_saida')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-right min-w-[90px]">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_devolucao', 'direction' => request('sort') == 'data_devolucao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                        Data Devolução
                        @if(request('sort') == 'data_devolucao')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell">
                    <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'observacao', 'direction' => request('sort') == 'observacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                        Observação
                        @if(request('sort') == 'observacao')
                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                @if(request('direction') == 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        @endif
                    </a>
                </th>
                <th scope="col" class="table-header-cell text-center">
                    Dias
                </th>
                <th scope="col" class="sticky right-0 table-header-cell text-right bg-gray-50 dark:bg-slate-800 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10 w-24">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="table-body">
            @forelse($movimentacoes as $movimentacao)
                <tr class="table-row">
                    <td class="table-cell table-cell-primary">
                        @if($movimentacao->produto)
                            <div>
                                <span>{{ $movimentacao->produto->referencia }}</span>
                                @if($movimentacao->produto->data_prevista_producao)
                                    <span class="text-blue-600 dark:text-blue-400 text-[10px] font-semibold ml-2">
                                        Dt.Prod: {{ $movimentacao->produto->data_prevista_producao->format('m/Y') }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-gray-500 dark:text-gray-400 text-xs truncate max-w-[150px]" title="{{ $movimentacao->produto->descricao }}">
                                {{ Str::limit($movimentacao->produto->descricao, 25, '...') }}
                            </div>
                            @if($movimentacao->produto->marca)
                            <div class="text-gray-400 dark:text-gray-500 text-[10px] truncate max-w-[150px]" title="{{ $movimentacao->produto->marca->nome_marca }}">
                                {{ $movimentacao->produto->marca->nome_marca }}
                            </div>
                            @endif
                        @else
                            <span class="text-red-500">Produto não encontrado</span>
                        @endif
                    </td>
                    <td class="table-cell text-center">
                        @if($movimentacao->produto && $movimentacao->produto->status)
                            <div>
                                <span class="badge-info">
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

                        <div class="mt-1 text-[10px] text-gray-500 dark:text-gray-400">
                            Comprometido: <span class="font-semibold {{ $movimentacao->comprometido ? 'text-blue-600 dark:text-blue-400' : '' }}">{{ $movimentacao->comprometido ? 'Sim' : 'Não' }}</span>
                        </div>
                    </td>
                    <td class="table-cell text-center">
                        @if($movimentacao->concluido)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary">
                        @if($movimentacao->localizacao)
                            <div class="truncate max-w-[100px]" title="{{ $movimentacao->localizacao->nome_localizacao }}">
                                {{ Str::limit($movimentacao->localizacao->nome_localizacao, 15, '...') }}
                            </div>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">N/A</span>
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary">
                        <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200' }}">
                            {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                        </span>
                    </td>
                    <td class="table-cell table-cell-secondary">
                        @if($movimentacao->situacao)
                            <div class="truncate max-w-[100px]" title="{{ $movimentacao->situacao->descricao }}">
                                <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                    {{ Str::limit($movimentacao->situacao->descricao, 15, '...') }}
                                </span>
                            </div>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                N/A
                            </span>
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary text-right">
                        @if($movimentacao->data_entrada)
                            <div class="leading-tight">
                                <div>{{ $movimentacao->data_entrada->format('d/m/Y') }}</div>
                                <div class="text-gray-400 dark:text-gray-500">{{ $movimentacao->data_entrada->format('H:i') }}</div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary text-right">
                        @if($movimentacao->data_saida)
                            <div class="leading-tight">
                                <div>{{ $movimentacao->data_saida->format('d/m/Y') }}</div>
                                <div class="text-gray-400 dark:text-gray-500">{{ $movimentacao->data_saida->format('H:i') }}</div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary text-right">
                        @if($movimentacao->data_devolucao)
                            <div class="leading-tight">
                                <div>{{ $movimentacao->data_devolucao->format('d/m/Y') }}</div>
                                <div class="text-gray-400 dark:text-gray-500">{{ $movimentacao->data_devolucao->format('H:i') }}</div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary max-w-[120px] overflow-hidden">
                        @if($movimentacao->observacao)
                            <button type="button" onclick="openObsPopup(this, {{ $movimentacao->id }})" class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 hover:text-blue-700 cursor-pointer transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template id="obs-text-{{ $movimentacao->id }}">{{ $movimentacao->observacao }}</template>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="table-cell table-cell-secondary">
                        @php
                            $diasEntre = null;
                            $prazoExcedido = false;
                            $prazoSetor = null;

                            if ($movimentacao->data_entrada) {
                                if ($movimentacao->data_saida) {
                                    // Se tem data de saída, calcular dias úteis entre entrada e saída
                                    $diasEntre = \App\Helpers\MovimentacaoHelper::calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                                } else {
                                    // Se não tem data de saída, calcular dias úteis entre entrada e data atual
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
                            <div class="text-center">
                                <span class="px-2 py-1 inline-block text-xs {{ $prazoExcedido ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 font-bold' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' }} rounded-full">
                                    {{ number_format($diasEntre, 0, ',', '.') }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                                </span>
                                @if(isset($prazoSetor))
                                    <div class="text-xs mt-1 {{ $prazoExcedido ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }} font-medium">
                                        (Prazo: {{ number_format($prazoSetor, 0, ',', '.') }} {{ $prazoSetor == 1 ? 'dia' : 'dias' }})
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center">
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            </div>
                        @endif
                    </td>
                    <td class="sticky right-0 table-cell text-right bg-white dark:bg-slate-900 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                        <div class="flex items-center justify-end space-x-1">
                            @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
                            <a href="{{ route('movimentacoes.show', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-view">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user() && auth()->user()->canUpdate('movimentacoes'))
                            <a href="{{ route('movimentacoes.edit', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endif
                            @if($movimentacao->anexo && auth()->user() && auth()->user()->canRead('movimentacoes'))
                            <button type="button" onclick="openImageModal('{{ $movimentacao->anexo_url }}', {{ $movimentacao->id }})" class="btn-action-view" title="Ver anexo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            @endif
                            @if(auth()->user() && auth()->user()->canDelete('movimentacoes'))
                            <form action="{{ route('movimentacoes.destroy', $movimentacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="table-cell table-empty">
                        Nenhuma movimentação encontrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
