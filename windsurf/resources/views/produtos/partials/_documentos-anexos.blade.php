<!-- Documentos e Anexos -->
<div class="bg-gray-50 dark:bg-slate-800/50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Documentos e Anexos</h3>
        @if(auth()->user()->canUpdate('produtos'))
            <button type="button" onclick="document.getElementById('modal-adicionar-anexo').classList.remove('hidden')" class="btn-ghost-purple">
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
                    <div class="bg-white dark:bg-slate-800 p-3 rounded-md border border-gray-200 dark:border-slate-700 flex items-center justify-between">
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
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $anexo->descricao }}</span>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('produtos.anexos.show', $anexo->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm mr-3">
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
            <div class="text-center py-4 text-gray-500 dark:text-gray-400 italic">
                Nenhum anexo adicionado. Clique em "Adicionar Anexo" para incluir documentos.
            </div>
        @endif
    </div>
</div>

<!-- Modal para adicionar anexo -->
@if(auth()->user()->canUpdate('produtos'))
<div id="modal-adicionar-anexo" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay de fundo -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        <!-- Centralização vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal propriamente dito -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Adicionar Anexo</h3>
                <button type="button" onclick="document.getElementById('modal-adicionar-anexo').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
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
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="arquivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo</label>
                    <input type="file" name="arquivo" id="arquivo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formatos aceitos: PNG, JPG, JPEG (máx. 10MB) e PDF (máx. 1MB)</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 text-right rounded-b-lg">
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
