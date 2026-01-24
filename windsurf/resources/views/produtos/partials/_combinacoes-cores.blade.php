<!-- Combinações de Cores -->
<div class="bg-gray-50 dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Combinações de Cores</h3>

    @if($produto->combinacoes && $produto->combinacoes->count() > 0)
        <div class="space-y-6">
            @foreach($produto->combinacoes as $combinacao)
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-white dark:bg-slate-900">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">{{ $combinacao->descricao }}</h4>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Quantidade pretendida: {{ $combinacao->quantidade_pretendida }}</div>
                            @if($combinacao->observacoes)
                                <div class="text-sm text-gray-500 italic mt-1">{{ $combinacao->observacoes }}</div>
                            @endif
                        </div>
                        @if(auth()->user()->canUpdate('produtos'))
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="mt-3">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Componentes:</h5>
                        @if($combinacao->componentes && $combinacao->componentes->count() > 0)
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($combinacao->componentes as $componente)
                                    <div class="bg-gray-50 dark:bg-slate-800 p-3 rounded-md border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $componente->codigo_cor || '#FFFFFF' }}"></div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $componente->tecido ? $componente->tecido->descricao : 'Tecido não encontrado' }}</div>
                                                    <div class="text-sm text-gray-600">{{ $componente->cor }} {{ $componente->codigo_cor ? "({$componente->codigo_cor})" : '' }}</div>
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-700 dark:text-gray-300">Consumo: <span class="font-medium">{{ $componente->consumo }} m</span></div>
                                        </div>

                                        @if($componente->tecido)
                                            @php
                                                $estoqueCor = $componente->tecido->estoquesCores()
                                                    ->where('cor', $componente->cor)
                                                    ->first();

                                                $estoque = $estoqueCor ? ($estoqueCor->quantidade ?? 0) : 0;
                                                $necessidade = $estoqueCor ? ($estoqueCor->necessidade ?? 0) : 0;
                                                $necessidadeProduto = $combinacao->quantidade_pretendida * $componente->consumo;
                                                $saldo = $estoque - $necessidade;
                                                $producaoPossivel = ($saldo > 0 && $componente->consumo > 0) ? floor($saldo / $componente->consumo) : 0;
                                            @endphp

                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                                    <div class="flex justify-between sm:justify-start gap-2">
                                                        <span class="text-gray-600 text-xs">Estoque:</span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($estoque, 2, ',', '.') }} m</span>
                                                    </div>
                                                    <div class="flex justify-between sm:justify-start gap-2 border-b border-gray-100 sm:border-0 pb-1 sm:pb-0">
                                                        <span class="text-gray-600 text-xs">Nec. Total:</span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($necessidade, 2, ',', '.') }} m</span>
                                                    </div>
                                                    <div class="flex justify-between sm:justify-start gap-2">
                                                        <span class="text-gray-600 text-xs">Nec. Produto:</span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($necessidadeProduto, 2, ',', '.') }} m</span>
                                                    </div>
                                                    <div class="flex justify-between sm:justify-start gap-2 border-b border-gray-100 sm:border-0 pb-1 sm:pb-0">
                                                        <span class="text-gray-600 text-xs">Saldo:</span>
                                                        <span class="font-medium {{ $saldo < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($saldo, 2, ',', '.') }} m</span>
                                                    </div>
                                                    <div class="flex justify-between sm:justify-start gap-2 col-span-1 sm:col-span-2 pt-1">
                                                        <span class="text-gray-600 text-xs uppercase font-bold">Produção possível:</span>
                                                        <span class="font-bold {{ $producaoPossivel <= 0 ? 'text-red-600' : 'text-green-600' }}">{{ $producaoPossivel }} unidades</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-gray-500 italic">Nenhum componente adicionado.</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-gray-500 italic">
            Nenhuma combinação definida para este produto.
            @if(auth()->user()->canUpdate('produtos'))
                <div class="mt-2">
                    <a href="{{ route('produtos.edit', $produto->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Editar produto para adicionar combinações</a>
                </div>
            @endif
        </div>
    @endif
</div>
