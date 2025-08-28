<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Produto') }}
            </h2>
            <div>
                @if(!$produto->trashed() && auth()->user()->canUpdate('produtos'))
                    <a href="{{ route('produtos.edit', $produto->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-600 focus:outline-none focus:border-yellow-600 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Editar
                    </a>
                @endif
                @if(auth()->user()->canRead('produtos'))
                    <a href="{{ route('produtos.pdf', $produto->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition mr-2" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                        PDF
                    </a>
                @endif
                <a href="{{ request('back_url') ? request('back_url') : route('produtos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8" style="max-width: 95%;">
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

                    <!-- Tecidos -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tecidos</h3>

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

                    <!-- Variações de Cores -->
                     <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                         <h3 class="text-lg font-semibold text-gray-800 mb-4">Variações de Cores</h3>

                         @if($produto->cores->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque (m)</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <span>Necessidade Geral(m)</span>
                                                    <div class="tooltip-container ml-1" style="position: relative;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 hover:text-gray-600 cursor-help tooltip-trigger" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                        </svg>
                                                        <div class="tooltip-content fixed z-[9999] w-64 p-2 bg-black text-xs rounded-lg hidden" style="color: white !important; box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                                                            Necessidade total de todos os produtos que usam esta cor de tecido.
                                                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-black rotate-45"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumo deste Produto (m)</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo (m)</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produção Possível</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($coresEnriquecidas as $cor)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center">
                                                        @if($cor['codigo_cor'])
                                                            <div class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: {{ $cor['codigo_cor'] }}"></div>
                                                        @endif
                                                        {{ $cor['cor'] }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $cor['codigo_cor'] ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($cor['quantidade'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['estoque'], 2, ',', '.') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['necessidade'], 2, ',', '.') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['consumo_deste_produto'], 2, ',', '.') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm {{ $cor['saldo'] >= 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                                                    {{ number_format($cor['saldo'], 2, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm {{ $cor['producao_possivel'] > 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                                                    {{ number_format($cor['producao_possivel'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                     </tbody>
                                     <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-sm font-medium text-gray-700">Total Geral:</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900">
                                                <div class="flex items-center">
                                                    <span class="mr-2">{{ number_format(collect($coresEnriquecidas)->sum('quantidade'), 0, ',', '.') }}</span>
                                                    @php
                                                        $totalCores = collect($coresEnriquecidas)->sum('quantidade');
                                                        $quantidadeProduto = $produto->quantidade ?? 0;
                                                        $isEqual = $totalCores == $quantidadeProduto;
                                                    @endphp
                                                    @if($isEqual)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor" title="Total das cores está igual à quantidade do produto ({{ number_format($quantidadeProduto, 0, ',', '.') }})">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor" title="Total das cores não está igual à quantidade do produto ({{ number_format($quantidadeProduto, 0, ',', '.') }})">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format(collect($coresEnriquecidas)->sum('estoque'), 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format(collect($coresEnriquecidas)->sum('necessidade'), 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format(collect($coresEnriquecidas)->sum('consumo_deste_produto'), 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold {{ collect($coresEnriquecidas)->sum('saldo') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format(collect($coresEnriquecidas)->sum('saldo'), 2, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900"></td>
                                        </tr>
                                        @if(!$isEqual)
                                             <tr>
                                                 <td colspan="8" class="px-4 py-2 text-xs text-gray-600 bg-yellow-50">
                                                     <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span>Quantidade do produto: {{ number_format($quantidadeProduto, 0, ',', '.') }} | Diferença: {{ number_format(abs($totalCores - $quantidadeProduto), 0, ',', '.') }}</span>
                                                     </div>
                                                 </td>
                                             </tr>
                                         @endif
                                     </tfoot>
                                 </table>
                             </div>
                         @else
                             <span class="text-gray-400 italic">Nenhuma variação de cor definida para este produto</span>
                         @endif
                     </div>

                    <!-- Documentos e Anexos -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Documentos e Anexos</h3>
                            @if(auth()->user()->canUpdate('produtos'))
                                <button type="button" onclick="document.getElementById('modal-adicionar-anexo').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Adicionar Anexo
                                </button>
                            @endif
                        </div>



                        <!-- Novos Anexos -->
                        <div>
                            @if($produto->anexos && $produto->anexos->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                                    @foreach($produto->anexos as $anexo)
                                        <div class="bg-white p-3 rounded-md border border-gray-200 flex items-center justify-between">
                                            <div class="flex items-center">
                                                @php
                                                    $icone = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
                                                    $corIcone = 'text-blue-500';

                                                    if (in_array($anexo->tipo_arquivo, ['jpg', 'jpeg', 'png'])) {
                                                        $icone = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
                                                        $corIcone = 'text-green-500';
                                                    }
                                                @endphp

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $corIcone }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icone }}" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $anexo->descricao }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <a href="{{ asset('storage/' . $anexo->arquivo_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm mr-3">
                                                    Visualizar
                                                </a>
                                                @if(auth()->user()->canUpdate('produtos'))
                                                    <form action="{{ route('produtos.anexos.destroy', $anexo->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este anexo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500 italic">
                                    Nenhum anexo adicionado. Clique em "Adicionar Anexo" para incluir documentos.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal para adicionar anexo -->
                    @if(auth()->user()->canUpdate('produtos'))
                    <div id="modal-adicionar-anexo" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 hidden overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <!-- Overlay de fundo -->
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>

                            <!-- Centralização vertical -->
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal propriamente dito -->
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-gray-900">Adicionar Anexo</h3>
                                    <button type="button" onclick="document.getElementById('modal-adicionar-anexo').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <form action="{{ route('produtos.anexos.store', $produto->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="px-6 py-4">
                                    <div class="mb-4">
                                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                        <input type="text" name="descricao" id="descricao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label for="arquivo" class="block text-sm font-medium text-gray-700 mb-1">Arquivo</label>
                                        <input type="file" name="arquivo" id="arquivo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                        <p class="mt-1 text-sm text-gray-500">Formatos aceitos: PNG, JPG, JPEG (máx. 10MB) e PDF (máx. 1MB)</p>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                                    <button type="button" onclick="document.getElementById('modal-adicionar-anexo').classList.add('hidden')" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Salvar
                                    </button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Carrossel de Imagens das Movimentações -->
                    @php
                        $movimentacoesComAnexo = $movimentacoes->filter(function($mov) {
                            return !empty($mov->anexo);
                        });
                    @endphp

                    @if($movimentacoesComAnexo->count() > 0)
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagens das Movimentações</h3>

                        <div class="relative">
                            <!-- Carrossel de imagens -->
                            <div class="carousel-container overflow-hidden">
                                <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                                    @foreach($movimentacoesComAnexo as $index => $movimentacao)
                                        <div class="carousel-item min-w-full flex flex-col items-center justify-center" data-index="{{ $index }}">
                                            <div class="relative w-full max-w-2xl">
                                                <img src="{{ $movimentacao->anexo_url }}" alt="Anexo da movimentação" class="w-full h-auto max-h-96 object-contain rounded-lg shadow-md">
                                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-2 rounded-b-lg">
                                                    <p class="text-sm">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }} -
                                                    {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }} -
                                                    {{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Controles do carrossel -->
                            @if($movimentacoesComAnexo->count() > 1)
                                <button class="carousel-control prev absolute top-1/2 left-2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button class="carousel-control next absolute top-1/2 right-2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Indicadores do carrossel -->
                            @if($movimentacoesComAnexo->count() > 1)
                                <div class="carousel-indicators flex justify-center mt-4">
                                    @foreach($movimentacoesComAnexo as $index => $movimentacao)
                                        <button class="carousel-indicator w-3 h-3 rounded-full mx-1 {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-300' }}" data-index="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

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

                                                        // Calcular dias úteis entre DATA ENTRADA e DATA SAIDA (ou data atual se não houver saída)
                                                        if ($movimentacao->data_entrada) {
                                                            if ($movimentacao->data_saida) {
                                                                // Se tem data de saída, calcular dias úteis entre entrada e saída
                                                                $diasEntre = calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                                                            } else {
                                                                // Se não tem data de saída, calcular dias úteis entre entrada e data atual
                                                                $diasEntre = calcularDiasUteis($movimentacao->data_entrada, now());
                                                            }

                                                            // Verificar se excede o prazo do setor
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
                                                    <a href="{{ route('movimentacoes.show', $movimentacao->id) }}" class="text-blue-600 hover:text-blue-900" title="Visualizar movimentação">
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
    <!-- Script para o carrossel -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carouselInner = document.querySelector('.carousel-inner');
            const carouselItems = document.querySelectorAll('.carousel-item');
            const prevButton = document.querySelector('.carousel-control.prev');
            const nextButton = document.querySelector('.carousel-control.next');
            const indicators = document.querySelectorAll('.carousel-indicator');

            if (!carouselInner || carouselItems.length === 0) return;

            let currentIndex = 0;
            const itemCount = carouselItems.length;

            // Função para atualizar o carrossel
            function updateCarousel() {
                const translateValue = -currentIndex * 100 + '%';
                carouselInner.style.transform = 'translateX(' + translateValue + ')';

                // Atualizar indicadores
                indicators.forEach((indicator, index) => {
                    if (index === currentIndex) {
                        indicator.classList.remove('bg-gray-300');
                        indicator.classList.add('bg-blue-600');
                    } else {
                        indicator.classList.remove('bg-blue-600');
                        indicator.classList.add('bg-gray-300');
                    }
                });
            }

            // Eventos para os botões de navegação
            if (prevButton) {
                prevButton.addEventListener('click', function() {
                    currentIndex = (currentIndex - 1 + itemCount) % itemCount;
                    updateCarousel();
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function() {
                    currentIndex = (currentIndex + 1) % itemCount;
                    updateCarousel();
                });
            }

            // Eventos para os indicadores
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    currentIndex = index;
                    updateCarousel();
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltip functionality
            const tooltipTriggers = document.querySelectorAll('.tooltip-trigger');

            tooltipTriggers.forEach(trigger => {
                trigger.addEventListener('mouseenter', function(e) {
                    const tooltipContent = this.closest('.tooltip-container').querySelector('.tooltip-content');

                    // Get position of the trigger
                    const triggerRect = this.getBoundingClientRect();

                    // Position the tooltip above the trigger with some offset
                    tooltipContent.style.position = 'fixed';
                    tooltipContent.style.zIndex = '9999';
                    tooltipContent.style.top = (triggerRect.top - 15) + 'px';
                    tooltipContent.style.left = (triggerRect.left + (triggerRect.width / 2)) + 'px';
                    tooltipContent.style.transform = 'translate(-50%, -100%)';

                    // Ensure tooltip is visible and above all other elements
                    tooltipContent.classList.remove('hidden');

                    // Prevent tooltip from being cut off at the top of the screen
                    const tooltipRect = tooltipContent.getBoundingClientRect();
                    if (tooltipRect.top < 10) {
                        // If too close to the top, position below the trigger instead
                        tooltipContent.style.top = (triggerRect.bottom + 15) + 'px';
                        tooltipContent.style.transform = 'translate(-50%, 0)';

                        // Move the arrow to the top
                        const arrow = tooltipContent.querySelector('div[class*="absolute"]');
                        if (arrow) {
                            arrow.style.top = '-5px';
                            arrow.style.bottom = 'auto';
                        }
                    }
                });

                trigger.addEventListener('mouseleave', function() {
                    const tooltipContent = this.closest('.tooltip-container').querySelector('.tooltip-content');
                    tooltipContent.classList.add('hidden');
                });
            });
        });
    </script>
</x-app-layout>
