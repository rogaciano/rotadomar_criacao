<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Produto') }}
            </h2>
            <div>
                @if(!$produto->trashed())
                    <a href="{{ route('produtos.edit', $produto->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-600 focus:outline-none focus:border-yellow-600 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Editar
                    </a>
                @endif
                <a href="{{ route('produtos.pdf', $produto->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition mr-2" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                    </svg>
                    PDF
                </a>
                <a href="{{ route('produtos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Informações do Produto -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Básicas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Referência</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->referencia }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Descrição</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->descricao }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Data de Cadastro</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->data_cadastro ? $produto->data_cadastro->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Marca</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->marca->nome_marca ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-span-1 md:col-span-3">
                                <span class="block text-sm font-medium text-gray-500 mb-2">Tecidos</span>
                                @if($produto->tecidos->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referência</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumo</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($produto->tecidos as $tecido)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                            <a href="{{ route('tecidos.show', $tecido->id) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                                                {{ $tecido->descricao }}
                                                            </a>
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $tecido->referencia }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $tecido->pivot->consumo ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Nenhum tecido associado a este produto</span>
                                @endif
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Estilista</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->estilista->nome_estilista ?? 'N/A' }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Grupo</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->grupoProduto->descricao ?? 'N/A' }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Quantidade</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->quantidade }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Status</span>
                                <span class="block mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                                    </span>
                                </span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Localização Atual</span>
                                <span class="block mt-1">
                                    @if($produto->localizacao_atual)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $produto->localizacao_atual->nome_localizacao }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Não localizado</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Preço Atacado</span>
                                <span class="block mt-1 text-sm text-gray-900">R$ {{ number_format($produto->preco_atacado, 2, ',', '.') }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Preço Varejo</span>
                                <span class="block mt-1 text-sm text-gray-900">R$ {{ number_format($produto->preco_varejo, 2, ',', '.') }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Criado em</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500">Última atualização</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $produto->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            @if($produto->deleted_at)
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Excluído em</span>
                                    <span class="block mt-1 text-sm text-red-600">{{ $produto->deleted_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Documentos -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-2">Ficha de Produção</span>
                                @if($produto->anexo_ficha_producao)
                                    <a href="{{ asset('storage/' . $produto->anexo_ficha_producao) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-medium text-sm text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Visualizar Ficha
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Nenhuma ficha de produção disponível</span>
                                @endif
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-2">Catálogo de Vendas</span>
                                @if($produto->anexo_catalogo_vendas)
                                    <a href="{{ asset('storage/' . $produto->anexo_catalogo_vendas) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-medium text-sm text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Visualizar Catálogo
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Nenhum catálogo de vendas disponível</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Movimentações -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Movimentações</h3>
                            <a href="{{ route('movimentacoes.create', ['produto_id' => $produto->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Nova Movimentação
                            </a>
                        </div>
                        
                        @if($movimentacoes->count() > 0)
                            <div class="overflow-x-auto">
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
                                                Data Saída
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dias
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Comprometido
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Observação
                                            </th>
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
                                                        $indexAtual = $loop->index;
                                                        $movimentacaoAnterior = $indexAtual > 0 ? $movimentacoes[$indexAtual - 1] : null;
                                                        
                                                        if ($indexAtual === 0) {
                                                            // Primeira linha, exibir zero
                                                            $diasEntre = 0;
                                                            $prazoExcedido = false;
                                                        } elseif ($movimentacaoAnterior && $movimentacao->data_entrada && $movimentacaoAnterior->data_entrada) {
                                                            // Calcular dias entre a movimentação atual e a anterior
                                                            // Usando abs() para garantir que o valor seja sempre positivo
                                                            $diasEntre = abs($movimentacao->data_entrada->diffInDays($movimentacaoAnterior->data_entrada));
                                                            
                                                            // Verificar se excede o prazo do setor anterior
                                                            if ($movimentacaoAnterior->localizacao && $movimentacaoAnterior->localizacao->prazo) {
                                                                $prazoExcedido = $diasEntre > $movimentacaoAnterior->localizacao->prazo;
                                                                $prazoSetor = $movimentacaoAnterior->localizacao->prazo;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($diasEntre !== null)
                                                        @if($indexAtual === 0)
                                                            <div class="text-center">
                                                                <span class="px-2 py-1 inline-block text-xs bg-gray-100 text-gray-600 rounded-full">0 dias</span>
                                                            </div>
                                                        @else
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
                                                        @endif
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $movimentacao->observacao ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-gray-500 italic">
                                Nenhuma movimentação encontrada para este produto.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
