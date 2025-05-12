<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Movimentação') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('movimentacoes.edit', ['movimentacao' => $movimentacao->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Editar
                </a>
                <a href="{{ route('movimentacoes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informações da Movimentação</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-gray-500">Data de Entrada:</span>
                                    <span class="ml-2 text-gray-900">{{ $movimentacao->data_entrada->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Data de Saída:</span>
                                    <span class="ml-2 text-gray-900">{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Tipo:</span>
                                    <span class="ml-2 text-gray-900">{{ $movimentacao->tipo ? $movimentacao->tipo->nome : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Situação:</span>
                                    <span class="ml-2 text-gray-900">{{ $movimentacao->situacao ? $movimentacao->situacao->nome : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Localização:</span>
                                    <span class="ml-2 text-gray-900">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informações do Produto</h3>
                            <div class="mt-4 space-y-4">
                                @if($movimentacao->produto)
                                    <div>
                                        <span class="text-gray-500">Referência:</span>
                                        <span class="ml-2 text-gray-900">{{ $movimentacao->produto->referencia }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Descrição:</span>
                                        <span class="ml-2 text-gray-900">{{ $movimentacao->produto->descricao }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Marca:</span>
                                        <span class="ml-2 text-gray-900">{{ $movimentacao->produto->marca ? $movimentacao->produto->marca->nome : 'N/A' }}</span>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('produtos.show', $movimentacao->produto) }}" class="text-blue-600 hover:underline">
                                            Ver detalhes completos do produto
                                        </a>
                                    </div>
                                @else
                                    <div class="text-gray-900">Produto não encontrado ou removido.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($movimentacao->observacao)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900">Observação</h3>
                            <div class="mt-4 p-4 bg-gray-50 rounded-md">
                                {{ $movimentacao->observacao }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
