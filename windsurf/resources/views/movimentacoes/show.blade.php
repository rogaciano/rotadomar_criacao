@php use Illuminate\Support\Facades\Storage; use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes da Movimentação') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('movimentacoes.edit', ['movimentacao' => $movimentacao->id]) }}{{ request('back_url') ? '?back_url=' . urlencode(request('back_url')) : '' }}" class="btn-ghost-primary">
                    Editar
                </a>
                <a href="{{ route('movimentacoes.pdf', ['movimentacao' => $movimentacao->id]) }}" class="btn-ghost-rose" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                    </svg>
                    PDF
                </a>
                <a href="{{ request('back_url') ? request('back_url') : route('movimentacoes.index') }}" class="btn-ghost-secondary">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações da Movimentação</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Data de Entrada:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $movimentacao->data_entrada->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Data de Saída:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Tipo:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white"><strong>{{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}</strong></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Situação:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white"><strong>{{ $movimentacao->situacao ? $movimentacao->situacao->descricao : 'N/A' }}</strong></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Localização:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white"><strong>{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}</strong></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Concluído:</span>
                                    <span class="ml-2 inline-flex items-center">
                                        @if($movimentacao->concluido)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1 text-green-600 font-medium">Sim</span>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1 text-red-600 font-medium">Não</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informações do Produto</h3>
                            <div class="mt-4 space-y-4">
                                @if($movimentacao->produto)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Referência:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $movimentacao->produto->referencia }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Descrição:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $movimentacao->produto->descricao }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Marca:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $movimentacao->produto->marca ? $movimentacao->produto->marca->nome_marca : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                        <span class="ml-2">
                                            @if($movimentacao->produto->status)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                                    {{ $movimentacao->produto->status->descricao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 dark:text-gray-400">N/A</span>
                                            @endif
                                        </span>
                                    </div>
                                    @if($movimentacao->produto->marca && $movimentacao->produto->marca->logo_path)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $movimentacao->produto->marca->logo_path) }}" alt="Logo da Marca" class="h-12 object-contain">
                                    </div>
                                    @endif
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

                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Observações</h3>
                            <button onclick="openObservacaoModal({{ $movimentacao->id }})" class="btn-ghost-primary text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Adicionar Observação
                            </button>
                        </div>
                        @if($movimentacao->observacao)
                            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-md text-gray-900 dark:text-gray-200 whitespace-pre-line" id="observacoes-container">
                                {{ $movimentacao->observacao }}
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-md text-gray-500 dark:text-gray-400" id="observacoes-container">
                                Nenhuma observação registrada.
                            </div>
                        @endif
                    </div>

                    @if($movimentacao->anexo)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Anexo</h3>
                            <div class="mt-4">

                                <a href="{{ $movimentacao->anexo_url }}" target="_blank">
                                    <img src="{{ $movimentacao->anexo_url }}" alt="Anexo da Movimentação" class="max-w-md rounded-lg shadow-md hover:opacity-90 transition-opacity">
                                </a>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Clique na imagem para ampliar</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Anexo</h3>
                            <div class="mt-4">
                                <p class="text-gray-600 dark:text-gray-400">Nenhum anexo disponível para esta movimentação.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Histórico de Alterações --}}
                    @if(isset($activities) && $activities->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Histórico de Alterações
                            </h3>
                            <div class="mt-4 space-y-3">
                                @foreach($activities as $activity)
                                    <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $activity->causer ? $activity->causer->name : 'Sistema' }}
                                                </span>
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    @if($activity->event == 'created')
                                                        criou este registro
                                                    @elseif($activity->event == 'updated')
                                                        atualizou este registro
                                                    @elseif($activity->event == 'deleted')
                                                        excluiu este registro
                                                    @else
                                                        {{ $activity->event }}
                                                    @endif
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        @if($activity->event == 'updated' && $activity->properties->has('old'))
                                            <div class="mt-3 text-sm space-y-1">
                                                @foreach($activity->properties['attributes'] ?? [] as $key => $value)
                                                    @if(isset($activity->properties['old'][$key]) && $activity->properties['old'][$key] != $value)
                                                        <div class="flex items-start gap-2">
                                                            <span class="font-medium text-gray-700 dark:text-gray-300 min-w-[120px]">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                            <span class="text-red-600 dark:text-red-400 line-through">{{ is_array($activity->properties['old'][$key]) ? json_encode($activity->properties['old'][$key]) : $activity->properties['old'][$key] }}</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                            <span class="text-green-600 dark:text-green-400">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
                    // Atualizar o container de observações
                    const container = document.getElementById('observacoes-container');
                    container.textContent = data.observacoes;
                    container.classList.remove('text-gray-500', 'dark:text-gray-400');
                    container.classList.add('text-gray-900', 'dark:text-gray-200', 'whitespace-pre-line');

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
