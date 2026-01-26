<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes do Produto') }}
            </h2>
            <div class="flex flex-wrap gap-2">
                @if(!$produto->trashed() && auth()->user()->canUpdate('produtos'))
                    <a href="{{ route('produtos.edit', $produto->id) }}" class="btn-ghost-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Editar
                    </a>
                @endif
                @if(!$produto->trashed() && auth()->user()->canCreate('produtos') && $produto->podeSerReprogramado())
                    <button onclick="document.getElementById('modal-reprogramar').classList.remove('hidden')" class="btn-ghost-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Reprogramar
                    </button>
                @endif
                @if(auth()->user()->canRead('produtos'))
                    <a href="{{ route('produtos.pdf', $produto->id) }}" class="btn-ghost-rose" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                        PDF
                    </a>
                @endif
                <a href="{{ request('back_url') ? request('back_url') : route('produtos.index') }}" class="btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8" style="max-width: 95%;">
            <!-- Mensagens de Feedback -->
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('alert_error'))
                <script>alert('{{ session('alert_error') }}');</script>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    @include('produtos.partials._informacoes-basicas', ['produto' => $produto])

                    @include('produtos.partials._tecidos', ['produto' => $produto])

                    @include('produtos.partials._localizacoes', ['produto' => $produto, 'etapasProducao' => $etapasProducao])

                    @include('produtos.partials._variacoes-cores', ['produto' => $produto, 'coresEnriquecidas' => $coresEnriquecidas])

                    @include('produtos.partials._combinacoes-cores', ['produto' => $produto])

                    @include('produtos.partials._documentos-anexos', ['produto' => $produto])

                    <!-- Variáveis de permissão para os modais de localização -->
                    @php
                        $canCreateProdutoLocalizacoes = auth()->user()->canCreate('produto_localizacao');
                        $canUpdateProdutoLocalizacoes = auth()->user()->canUpdate('produto_localizacao');
                        $canDeleteProdutoLocalizacoes = auth()->user()->canDelete('produto_localizacao');
                    @endphp

                    <!-- Modal para adicionar localização -->
                    @if($canCreateProdutoLocalizacoes)
                    <div id="modal-adicionar-localizacao" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 z-50 hidden overflow-y-auto">
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
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Adicionar Localização</h3>
                                        <button type="button" onclick="document.getElementById('modal-adicionar-localizacao').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <form action="{{ route('produtos.localizacoes.store', $produto->id) }}" method="POST">
                                    @csrf
                                    <div class="px-6 py-4">
                                        <div class="mb-4">
                                            <label for="localizacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização *</label>
                                            <select name="localizacao_id" id="localizacao_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                                                <option value="">Selecione uma localização</option>
                                                @foreach(\App\Models\Localizacao::where('ativo', true)->orderBy('nome_localizacao')->get() as $loc)
                                                    <option value="{{ $loc->id }}">{{ $loc->nome_localizacao }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="ordem_producao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordem de Produção *</label>
                                            <input type="text" name="ordem_producao" id="ordem_producao" maxlength="30" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Número/código da ordem de produção</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade *</label>
                                            <input type="number" name="quantidade" id="quantidade" min="1" step="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Informe a quantidade do produto nesta localização</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="data_prevista_faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Prevista para Facção</label>
                                            <input type="date" name="data_prevista_faccao" id="data_prevista_faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data prevista de facção para esta localização</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="data_envio_faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Envio à Facção</label>
                                            <input type="date" name="data_envio_faccao" id="data_envio_faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data de envio para a facção</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="concluido" id="concluido" value="1" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" onchange="toggleDataRetornoFaccao('add')">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Concluído</span>
                                            </label>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Marque se esta ordem de produção foi concluída</p>
                                        </div>
                                        <div id="add-data-retorno-container" class="mb-4 hidden">
                                            <label for="data_retorno_faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Retorno da Facção *</label>
                                            <input type="date" name="data_retorno_faccao" id="data_retorno_faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data de retorno da facção (obrigatório quando concluído)</p>
                                        </div>
                                        <div>
                                            <label for="observacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observação</label>
                                            <textarea name="observacao" id="observacao" rows="2" maxlength="255" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Observações adicionais sobre esta ordem de produção</p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 text-right rounded-b-lg flex justify-end gap-2">
                                        <button type="button" onclick="document.getElementById('modal-adicionar-localizacao').classList.add('hidden')" class="btn-ghost-secondary">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn-ghost-primary">
                                            Salvar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Modal para editar localização -->
                    @if($canUpdateProdutoLocalizacoes)
                    <div id="modal-editar-localizacao" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 z-50 hidden overflow-y-auto">
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
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Editar Localização</h3>
                                        <button type="button" onclick="fecharModalEditarLocalizacao()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <form id="form-editar-localizacao" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="px-6 py-4">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização</label>
                                            <p id="edit-localizacao-nome" class="text-sm text-gray-900 font-semibold bg-purple-50 px-3 py-2 rounded"></p>
                                            <input type="hidden" id="edit-localizacao-id" name="localizacao_id">
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit-ordem-producao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordem de Produção *</label>
                                            <input type="text" name="ordem_producao" id="edit-ordem-producao" maxlength="30" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Número/código da ordem de produção</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit-quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade *</label>
                                            <input type="number" name="quantidade" id="edit-quantidade" min="1" step="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Informe a quantidade do produto nesta localização</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit-data-prevista-faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Prevista para Facção</label>
                                            <input type="date" name="data_prevista_faccao" id="edit-data-prevista-faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data prevista de facção para esta localização</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="edit-data-envio-faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Envio à Facção</label>
                                            <input type="date" name="data_envio_faccao" id="edit-data-envio-faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data de envio para a facção</p>
                                        </div>
                                        <div class="mb-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="concluido" id="edit-concluido" value="1" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" onchange="toggleDataRetornoFaccao('edit')">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Concluído</span>
                                            </label>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Marque se esta ordem de produção foi concluída</p>
                                        </div>
                                        <div id="edit-data-retorno-container" class="mb-4 hidden">
                                            <label for="edit-data-retorno-faccao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Retorno da Facção *</label>
                                            <input type="date" name="data_retorno_faccao" id="edit-data-retorno-faccao" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data de retorno da facção (obrigatório quando concluído)</p>
                                        </div>
                                        <div>
                                            <label for="edit-observacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observação</label>
                                            <textarea name="observacao" id="edit-observacao" rows="2" maxlength="255" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Observações adicionais sobre esta ordem de produção</p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 text-right rounded-b-lg flex justify-end gap-2">
                                        <button type="button" onclick="fecharModalEditarLocalizacao()" class="btn-ghost-secondary">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn-ghost-primary">
                                            Atualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleDataRetornoFaccao(mode) {
                            const checkbox = document.getElementById(mode === 'add' ? 'concluido' : 'edit-concluido');
                            const container = document.getElementById(mode === 'add' ? 'add-data-retorno-container' : 'edit-data-retorno-container');
                            const input = document.getElementById(mode === 'add' ? 'data_retorno_faccao' : 'edit-data-retorno-faccao');

                            if (checkbox.checked) {
                                container.classList.remove('hidden');
                                input.setAttribute('required', 'required');
                            } else {
                                container.classList.add('hidden');
                                input.removeAttribute('required');
                                input.value = '';
                            }
                        }

                        function abrirModalEditarLocalizacao(produtoLocalizacaoId, localizacaoId, nomeLocalizacao, quantidade, dataFaccao, ordemProducao, observacao, concluido, dataEnvioFaccao, dataRetornoFaccao, dataEntregaFaccao) {
                            try {
                                console.log('Abrindo modal para editar localização:', {
                                    produtoLocalizacaoId,
                                    localizacaoId,
                                    nomeLocalizacao,
                                    quantidade,
                                    dataFaccao,
                                    ordemProducao,
                                    observacao,
                                    concluido,
                                    dataEnvioFaccao,
                                    dataRetornoFaccao
                                });

                                document.getElementById('edit-localizacao-id').value = localizacaoId;
                                document.getElementById('edit-localizacao-nome').textContent = nomeLocalizacao;
                                document.getElementById('edit-ordem-producao').value = ordemProducao || '';
                                document.getElementById('edit-quantidade').value = quantidade;
                                document.getElementById('edit-data-prevista-faccao').value = dataFaccao || '';
                                document.getElementById('edit-data-envio-faccao').value = dataEnvioFaccao || '';
                                document.getElementById('edit-data-retorno-faccao').value = dataRetornoFaccao || '';
                                document.getElementById('edit-observacao').value = observacao || '';
                                document.getElementById('edit-concluido').checked = concluido == 1;

                                const dataEntregaInput = document.getElementById('edit-data-entrega-faccao');
                                if (dataEntregaInput) dataEntregaInput.value = dataEntregaFaccao || '';

                                // Mostrar/ocultar campo de data de retorno baseado no checkbox
                                toggleDataRetornoFaccao('edit');

                                // Atualizar action do formulário com o ID do registro produto_localizacao
                                const form = document.getElementById('form-editar-localizacao');
                                const currentRoute = "{{ route('produtos.localizacoes.update', [$produto->id, 'PLACEHOLDER']) }}";
                                form.action = currentRoute.replace('PLACEHOLDER', produtoLocalizacaoId);

                                console.log('Action do formulário:', form.action);

                                document.getElementById('modal-editar-localizacao').classList.remove('hidden');
                            } catch (error) {
                                console.error('Erro ao abrir modal de edição:', error);
                                alert('Erro ao abrir formulário de edição. Por favor, recarregue a página e tente novamente.');
                            }
                        }

                        function fecharModalEditarLocalizacao() {
                            document.getElementById('modal-editar-localizacao').classList.add('hidden');
                        }
                    </script>
                    @endif

                    @include('produtos.partials._observacoes', ['produto' => $produto, 'observacoes' => $observacoes ?? null])

                    @include('produtos.partials._movimentacoes', ['produto' => $produto, 'movimentacoes' => $movimentacoes])
                </div>
            </div>
        </div>
    </div>

    @include('produtos.partials._modais', ['produto' => $produto, 'movimentacoes' => $movimentacoes])
</x-app-layout>
