<!-- Movimentações -->
<div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
    @php
    // Função para calcular dias úteis entre duas datas (excluindo sábados e domingos)
    function calcularDiasUteis($dataInicio, $dataFim) {
        $diasUteis = 0;
        $dataAtual = clone $dataInicio;

        while ($dataAtual <= $dataFim) {
            // 6 = sábado, 0 = domingo
            $diaDaSemana = $dataAtual->dayOfWeek;
            if ($diaDaSemana != 0 && $diaDaSemana != 6) {
                $diasUteis++;
            }
            $dataAtual->addDay();
        }

        return $diasUteis;
    }
    @endphp

    <!-- Seção de Reprogramações -->
    @if($produto->isReprogramacao() || $produto->reprogramacoes()->count() > 0)
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            @if($produto->isReprogramacao())
                <!-- Este produto É uma reprogramação -->
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Produto Original</h3>
                </div>
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-orange-700">
                                Este produto é a <strong>reprogramação #{{ str_pad($produto->numero_reprogramacao, 2, '0', STR_PAD_LEFT) }}</strong> de:
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('produtos.show', $produto->produtoOriginal->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-white border border-orange-300 rounded-md font-semibold text-sm text-orange-700 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $produto->produtoOriginal->referencia }} - {{ $produto->produtoOriginal->descricao }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Este produto TEM reprogramações -->
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Reprogramações deste Produto</h3>
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $produto->reprogramacoes()->count() }}
                    </span>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="space-y-3">
                        @foreach($produto->reprogramacoes as $reprogramacao)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-3 rounded-lg border border-blue-200 hover:shadow-md transition gap-4">
                                <div class="flex items-center space-x-3 w-full">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 font-bold">
                                            #{{ str_pad($reprogramacao->numero_reprogramacao, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $reprogramacao->referencia }}</p>
                                        <p class="text-xs text-gray-500">
                                            Criado em {{ $reprogramacao->data_cadastro->format('d/m/Y') }}
                                            @if($reprogramacao->status)
                                                • <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $reprogramacao->status->descricao == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $reprogramacao->status->descricao }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-auto">
                                    <a href="{{ route('produtos.show', $reprogramacao->id) }}"
                                       class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded-md transition shadow-sm uppercase tracking-tighter">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @php
        // Verificar se usuário pode gerenciar pelo menos uma localização do produto
        $podeGerenciarProdutoMov = auth()->user()->isAdmin();
        if (!$podeGerenciarProdutoMov && $produto->localizacoes->count() > 0) {
            foreach ($produto->localizacoes as $loc) {
                if (auth()->user()->podeGerenciarEtapa($loc->id)) {
                    $podeGerenciarProdutoMov = true;
                    break;
                }
            }
        }
    @endphp
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Movimentações</h3>
        @if($podeGerenciarProdutoMov)
            <a href="{{ route('movimentacoes.create', ['produto_id' => $produto->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nova Movimentação
            </a>
        @endif
    </div>

    @if($movimentacoes->count() > 0)
        <!-- Vista Desktop -->
        <style>
            #tabela-desktop-movimentacoes { display: none; }
            @media (min-width: 768px) {
                #tabela-desktop-movimentacoes { display: block !important; }
            }
        </style>
        <div class="overflow-x-auto" id="tabela-desktop-movimentacoes">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Localização
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Situação
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data Entrada
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data Conclusão
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dias
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Comprometido
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Obs
                        </th>
                        <th scope="col" class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($movimentacoes as $movimentacao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($movimentacao->situacao)
                                    <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $movimentacao->situacao->descricao ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $diasEntre = null;
                                    $prazoExcedido = false;
                                    $prazoSetor = null;
                                    if ($movimentacao->data_entrada) {
                                        if ($movimentacao->data_saida) {
                                            $diasEntre = calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                                        } else {
                                            $diasEntre = calcularDiasUteis($movimentacao->data_entrada, now());
                                        }
                                        if ($movimentacao->localizacao && $movimentacao->localizacao->prazo) {
                                            $prazoExcedido = $diasEntre > $movimentacao->localizacao->prazo;
                                            $prazoSetor = $movimentacao->localizacao->prazo;
                                        }
                                    }
                                @endphp

                                @if($diasEntre !== null)
                                    <div class="text-center">
                                        <span class="px-2 py-1 inline-block text-xs {{ $prazoExcedido ? 'bg-red-100 text-red-800 font-bold' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                            {{ $diasEntre }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                                        </span>
                                        @if($prazoExcedido && isset($prazoSetor))
                                            <div class="text-xs mt-1 text-red-600 font-medium">
                                                (Prazo: {{ $prazoSetor }} {{ $prazoSetor == 1 ? 'dia' : 'dias' }})
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span class="text-gray-400">-</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->comprometido ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $movimentacao->comprometido ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($movimentacao->observacao)
                                    <div class="tooltip-container flex items-center justify-center" style="position: static;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 hover:text-blue-700 cursor-help tooltip-trigger" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="tooltip-content fixed z-[9999] w-64 p-2 bg-black text-xs rounded-lg hidden" style="color: white !important; box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                                            {{ $movimentacao->observacao }}
                                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-black rotate-45"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('movimentacoes.show', ['movimentacao' => $movimentacao->id, 'back_url' => route('produtos.show', $produto->id)]) }}" class="text-blue-600 hover:text-blue-900" title="Visualizar movimentação">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Vista Mobile Cards -->
        <style>
            #cards-mobile-movimentacoes { display: block; }
            @media (min-width: 768px) {
                #cards-mobile-movimentacoes { display: none !important; }
            }
        </style>
        <div class="space-y-4" id="cards-mobile-movimentacoes">
            @foreach($movimentacoes as $movimentacao)
                @php
                    $diasEntre = null;
                    $prazoExcedido = false;
                    $prazoSetor = null;
                    if ($movimentacao->data_entrada) {
                        if ($movimentacao->data_saida) {
                            $diasEntre = calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                        } else {
                            $diasEntre = calcularDiasUteis($movimentacao->data_entrada, now());
                        }
                        if ($movimentacao->localizacao && $movimentacao->localizacao->prazo) {
                            $prazoExcedido = $diasEntre > $movimentacao->localizacao->prazo;
                            $prazoSetor = $movimentacao->localizacao->prazo;
                        }
                    }
                @endphp
                <div class="bg-white border rounded-xl shadow-sm overflow-hidden border-indigo-100">
                    <!-- Header -->
                    <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider block">Setor / Localização</span>
                            <span class="font-bold text-gray-900">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter block mb-0.5">Entrada</span>
                                <span class="text-xs font-bold text-gray-900">{{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter block mb-0.5">Conclusão</span>
                                <span class="text-xs font-bold text-gray-900">{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y') : 'Em andamento' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter block mb-0.5">Tempo Gasto</span>
                                @if($diasEntre !== null)
                                    <span class="px-2 py-0.5 inline-block text-[10px] font-bold {{ $prazoExcedido ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                        {{ $diasEntre }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                                    </span>
                                    @if($prazoExcedido && isset($prazoSetor))
                                        <span class="text-[9px] block text-red-500 font-medium">Prazo: {{ $prazoSetor }}d</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter block mb-0.5">Comprometido</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $movimentacao->comprometido ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $movimentacao->comprometido ? 'Sim' : 'Não' }}
                                </span>
                            </div>
                        </div>

                        @if($movimentacao->situacao)
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Situação Atual</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $movimentacao->situacao->descricao ?? 'N/A' }}
                                </span>
                            </div>
                        @endif

                        @if($movimentacao->observacao)
                            <div class="border-t pt-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Observação</span>
                                <p class="text-xs text-gray-600 italic leading-relaxed">{{ $movimentacao->observacao }}</p>
                            </div>
                        @endif
                    </div>
                    <!-- Footer Action -->
                    <div class="bg-gray-50 px-4 py-2 border-t flex justify-end">
                        <a href="{{ route('movimentacoes.show', ['movimentacao' => $movimentacao->id, 'back_url' => route('produtos.show', $produto->id)]) }}" class="text-indigo-600 font-bold text-xs uppercase tracking-tighter flex items-center gap-1">
                            Detalhes Completos
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-gray-500 italic">
            Nenhuma movimentação encontrada para este produto.
        </div>
    @endif
</div>
