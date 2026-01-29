<!-- Modal para adicionar/editar combinação -->
<div id="modal-combinacao" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay de fundo -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="fecharModalCombinacao()">
            <div class="absolute inset-0 bg-gray-500 dark:bg-slate-900 opacity-75"></div>
        </div>

        <!-- Centralização vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal propriamente dito -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:border dark:border-slate-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-800">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-combinacao-title">Nova Combinação</h3>
                    <button type="button" onclick="fecharModalCombinacao()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 bg-white dark:bg-slate-800">
                <div class="space-y-4">
                    <input type="hidden" id="combinacao-id" value="">
                    <div>
                        <label for="combinacao-descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                        <input type="text" id="combinacao-descricao" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="combinacao-quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade Pretendida</label>
                        <input type="number" id="combinacao-quantidade" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="1" value="1" required>
                    </div>
                    <div>
                        <label for="combinacao-observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações (opcional)</label>
                        <textarea id="combinacao-observacoes" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900 text-right rounded-b-lg flex justify-end gap-2">
                <button type="button" onclick="fecharModalCombinacao()" class="btn-ghost-secondary">
                    Cancelar
                </button>
                <button type="button" id="salvar-combinacao" class="btn-ghost-primary">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar/editar componente -->
<div id="modal-componente" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Overlay de fundo -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="fecharModalComponente()">
            <div class="absolute inset-0 bg-gray-500 dark:bg-slate-900 opacity-75"></div>
        </div>

        <!-- Centralização vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal propriamente dito -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:border dark:border-slate-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-800">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-componente-title">Adicionar Componente</h3>
                    <button type="button" onclick="fecharModalComponente()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 bg-white dark:bg-slate-800">
                <div class="space-y-4">
                    <input type="hidden" id="componente-id" value="">
                    <input type="hidden" id="componente-combinacao-id" value="">
                    <div>
                        <label for="componente-tecido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tecido</label>
                        <select id="componente-tecido" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">Selecione um tecido</option>
                            <!-- Mostrar apenas os tecidos já selecionados para o produto -->
                            @foreach($produto->tecidos as $tecido)
                            <option value="{{ $tecido->id }}">{{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="componente-cor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cor</label>
                        <select id="componente-cor" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required disabled>
                            <option value="">Selecione um tecido primeiro</option>
                        </select>
                    </div>

                    <!-- Informações de estoque -->
                    <div id="info-estoque" class="hidden bg-gray-50 dark:bg-slate-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informações de Estoque</h4>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="pr-1 py-0.5 whitespace-nowrap text-right"><span class="text-gray-600 dark:text-gray-400">Estoque:</span></td>
                                <td class="pl-1 py-0.5 whitespace-nowrap"><span id="info-estoque-valor" class="font-medium text-gray-900 dark:text-white">0 m</span></td>
                                <td class="pr-1 py-0.5 whitespace-nowrap text-right"><span class="text-gray-600 dark:text-gray-400">Necessidade:</span></td>
                                <td class="pl-1 py-0.5 whitespace-nowrap"><span id="info-necessidade-valor" class="font-medium text-gray-900 dark:text-white">0 m</span></td>
                            </tr>
                            <tr>
                                <td class="pr-1 py-0.5 whitespace-nowrap text-right"><span class="text-gray-600 dark:text-gray-400">Saldo:</span></td>
                                <td class="pl-1 py-0.5 whitespace-nowrap"><span id="info-saldo-valor" class="font-medium text-gray-900 dark:text-white">0 m</span></td>
                                <td class="pr-1 py-0.5 whitespace-nowrap text-right"><span class="text-gray-600 dark:text-gray-400">Produção possível:</span></td>
                                <td class="pl-1 py-0.5 whitespace-nowrap"><span id="info-producao-valor" class="font-medium text-gray-900 dark:text-white">0 unidades</span></td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <label for="componente-consumo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Consumo (metros)</label>
                        <input type="number" id="componente-consumo" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0.001" step="0.001" value="1" required>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900 text-right rounded-b-lg flex justify-end gap-2">
                <button type="button" onclick="fecharModalComponente()" class="btn-ghost-secondary">
                    Cancelar
                </button>
                <button type="button" id="salvar-componente" class="btn-ghost-primary">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar anexo -->
<div id="modal-anexo" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-anexo').classList.add('hidden')"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:border dark:border-slate-700">
            <form action="{{ route('produtos.anexos.store', $produto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Adicionar Anexo</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                                    <input type="text" name="descricao" id="descricao" class="block w-full border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                </div>
                                <div>
                                    <label for="arquivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo</label>
                                    <input type="file" name="arquivo" id="arquivo" class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-600 dark:file:text-white dark:hover:file:bg-indigo-500" required>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formatos aceitos: PNG, JPG, JPEG (máx. 10MB) e PDF (máx. 1MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 dark:bg-slate-900 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Salvar</button>
                    <button type="button" onclick="document.getElementById('modal-anexo').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
