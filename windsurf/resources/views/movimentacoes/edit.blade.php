<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Editar Movimentação') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <form action="{{ route('movimentacoes.update', $movimentacao) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="current_page" value="{{ request()->query('page') }}">
                        <input type="hidden" name="back_url" value="{{ request('back_url') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Produto -->
                            <div>
                                <x-label for="produto_id" value="{{ __('Produto') }}" />
                                <select id="produto_id" name="produto_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}" {{ old('produto_id', $movimentacao->produto_id) == $produto->id ? 'selected' : '' }}>
                                            {{ $produto->referencia }} - {{ $produto->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('produto_id')" class="mt-2" />
                            </div>

                            <!-- Localização -->
                            <div>
                                <x-label for="localizacao_id" value="{{ __('Localização') }}" />
                                <select id="localizacao_id" name="localizacao_id" class="select2 block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma localização</option>
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ old('localizacao_id', $movimentacao->localizacao_id) == $localizacao->id ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}{{ !$localizacao->ativo ? ' (Inativa)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('localizacao_id')" class="mt-2" />
                            </div>

                            <!-- Tipo -->
                            <div>
                                <x-label for="tipo_id" value="{{ __('Tipo de Movimentação') }}" />
                                <select id="tipo_id" name="tipo_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipo_id', $movimentacao->tipo_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_id')" class="mt-2" />
                            </div>

                            <!-- Situação -->
                            <div>
                                <x-label for="situacao_id" value="{{ __('Situação') }}" />
                                <select id="situacao_id" name="situacao_id" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma situação</option>
                                    @foreach($situacoes as $situacao)
                                        <option value="{{ $situacao->id }}" {{ old('situacao_id', $movimentacao->situacao_id) == $situacao->id ? 'selected' : '' }}>
                                            {{ $situacao->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('situacao_id')" class="mt-2" />
                            </div>

                            <!-- Data Entrada -->
                            <div>
                                <x-label for="data_entrada" value="{{ __('Data de Entrada') }}" />
                                <x-input id="data_entrada" class="block mt-1 w-full" type="datetime-local" name="data_entrada" :value="old('data_entrada', $movimentacao->data_entrada->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('data_entrada')" class="mt-2" />
                            </div>

                            <!-- Data Conclusão -->
                            <div>
                                <x-label for="data_saida" value="{{ __('Data de Conclusão') }}" />
                                <x-input id="data_saida" class="block mt-1 w-full" type="datetime-local" name="data_saida" :value="old('data_saida', $movimentacao->data_saida ? $movimentacao->data_saida->format('Y-m-d\TH:i') : null)" />
                                <x-input-error :messages="$errors->get('data_saida')" class="mt-2" />
                            </div>
                            <!-- Data Devolução -->
                            <div>
                                <x-label for="data_devolucao" value="{{ __('Data de Devolução') }}" />
                                <x-input id="data_devolucao" class="block mt-1 w-full" type="datetime-local" name="data_devolucao" :value="old('data_devolucao', $movimentacao->data_devolucao ? $movimentacao->data_devolucao->format('Y-m-d\TH:i') : null)" />
                                <x-input-error :messages="$errors->get('data_devolucao')" class="mt-2" />
                            </div>

                            <!-- Observações -->
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-center mb-2">
                                    <x-label for="observacao" value="{{ __('Observações') }}" />
                                    <button type="button" onclick="openObservacaoModal({{ $movimentacao->id }})" class="btn-ghost-primary text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Adicionar Observação
                                    </button>
                                </div>
                                <textarea id="observacao" name="observacao" rows="5" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>{{ old('observacao', $movimentacao->observacao) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">As observações são exibidas em ordem cronológica. Use o botão acima para adicionar novas observações.</p>
                                <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                            </div>

                            <!-- Anexo -->
                            <div class="md:col-span-2">
                                <x-label for="anexo" value="{{ __('Anexo (opcional)') }}" />
                                <input type="file" id="anexo" name="anexo" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formatos aceitos: JPG, JPEG, PNG. Tamanho máximo: 10MB.</p>
                                @if($movimentacao->anexo)
                                <div class="mt-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm">Anexo atual: <span class="font-medium">{{ basename($movimentacao->anexo) }}</span></p>
                                        <button type="button" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="confirmarRemocao()">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Remover anexo
                                            </span>
                                        </button>
                                    </div>
                                    <img src="{{ $movimentacao->anexo_url }}" class="mt-2 max-w-xs rounded border" alt="Anexo atual">
                                </div>
                                @endif
                                <x-input-error :messages="$errors->get('anexo')" class="mt-2" />
                            </div>

                            <!-- Concluido -->
                            <div class="md:col-span-2">
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" id="concluido" name="concluido" value="1" {{ old('concluido', $movimentacao->concluido) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="concluido" class="ml-2 block text-sm font-medium text-gray-700">Movimentação concluída</label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Marque esta opção se a movimentação foi finalizada.</p>
                                <x-input-error :messages="$errors->get('concluido')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ request('back_url') ? request('back_url') : route('movimentacoes.index') }}" class="btn-ghost-secondary mr-3">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-ghost-primary">
                                {{ __('Atualizar') }}
                            </button>
                        </div>
                    </form>

                    <!-- Formulário para remover anexo (fora do formulário principal) -->
                    @if($movimentacao->anexo)
                    <form id="form-remover-anexo" action="{{ route('movimentacoes.remover-anexo', $movimentacao->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Selecione uma localização",
                allowClear: true,
                width: '100%'
            });

            // Ajustar estilo do Select2 para combinar com o Tailwind
            $('.select2-container--default .select2-selection--single').css({
                'height': '42px',
                'padding': '6px 4px',
                'border-color': '#d1d5db'
            });

            // Validação: não permitir marcar como concluído sem data de devolução
            function validarConcluido() {
                const dataDevolucao = $('#data_devolucao').val();
                const checkboxConcluido = $('#concluido');

                if (checkboxConcluido.is(':checked') && (!dataDevolucao || dataDevolucao.trim() === '')) {
                    alert('Para marcar como concluído, é necessário preencher a Data de Devolução.');
                    checkboxConcluido.prop('checked', false);
                    return false;
                }
                return true;
            }

            // Auto-marcar concluído quando data de devolução for preenchida
            function autoMarcarConcluido() {
                const dataDevolucao = $('#data_devolucao').val();
                const checkboxConcluido = $('#concluido');

                if (dataDevolucao && dataDevolucao.trim() !== '') {
                    // Se data de devolução foi preenchida, marcar como concluído
                    checkboxConcluido.prop('checked', true);
                }
            }

            // Evento no campo data de devolução
            $('#data_devolucao').on('change', autoMarcarConcluido);
            $('#data_devolucao').on('input', autoMarcarConcluido);

            // Evento no checkbox concluído
            $('#concluido').on('change', function() {
                if ($(this).is(':checked')) {
                    validarConcluido();
                }
            });

            // Validação no submit do formulário
            $('form').on('submit', function(e) {
                if (!validarConcluido()) {
                    e.preventDefault();
                    return false;
                }
            });

            // Verificar no carregamento da página se já tem data de devolução preenchida
            setTimeout(function() {
                autoMarcarConcluido();
            }, 100);
        });

        function confirmarRemocao() {
            if (confirm('Tem certeza que deseja remover este anexo?')) {
                document.getElementById('form-remover-anexo').submit();
            }
        }
    </script>
    @endpush

    <!-- Modal de Observação -->
    <div id="observacaoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Adicionar Observação</h3>
                <div class="mt-4">
                    <textarea id="observacaoTexto" rows="4" class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 border rounded-lg focus:outline-none focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600" placeholder="Digite a observação..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button onclick="closeObservacaoModal()" class="btn-ghost-secondary">
                        Cancelar
                    </button>
                    <button onclick="saveObservacao()" class="btn-ghost-primary">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentMovimentacaoId = null;

        function openObservacaoModal(movimentacaoId) {
            currentMovimentacaoId = movimentacaoId;
            document.getElementById('observacaoModal').classList.remove('hidden');
            document.getElementById('observacaoTexto').value = '';
            document.getElementById('observacaoTexto').focus();
        }

        function closeObservacaoModal() {
            document.getElementById('observacaoModal').classList.add('hidden');
            document.getElementById('observacaoTexto').value = '';
            currentMovimentacaoId = null;
        }

        function saveObservacao() {
            const observacao = document.getElementById('observacaoTexto').value.trim();

            if (!observacao) {
                alert('Por favor, digite uma observação.');
                return;
            }

            fetch(`/movimentacoes/${currentMovimentacaoId}/observacao`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    observacao: observacao
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar o campo de observações
                    const textarea = document.getElementById('observacao');
                    textarea.value = data.observacoes;

                    closeObservacaoModal();

                    // Mostrar mensagem de sucesso centralizada
                    const successDiv = document.createElement('div');
                    successDiv.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-2xl z-[9999] text-center';
                    successDiv.innerHTML = '<div class="flex items-center gap-2 justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><p class="font-semibold text-base">' + data.message + '</p></div>';
                    document.body.appendChild(successDiv);

                    setTimeout(() => {
                        successDiv.style.transition = 'opacity 0.5s';
                        successDiv.style.opacity = '0';
                        setTimeout(() => successDiv.remove(), 500);
                    }, 5000);
                } else {
                    alert('Erro ao salvar observação. Tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar observação. Tente novamente.');
            });
        }

        // Fechar modal ao clicar fora dele
        document.getElementById('observacaoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeObservacaoModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
