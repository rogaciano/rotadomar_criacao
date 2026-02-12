@php use Illuminate\Support\Facades\Storage; use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes da Movimentação') }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->canUpdate('movimentacoes'))
                <a href="{{ route('movimentacoes.edit', ['movimentacao' => $movimentacao->id]) }}{{ request('back_url') ? '?back_url=' . urlencode(request('back_url')) : '' }}" class="btn-ghost-primary">
                    Editar
                </a>
                @endif
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
                            @if(auth()->user()->canCreate('movimentacoes_observacoes'))
                                <button onclick="openObservacaoModal({{ $movimentacao->id }})" class="btn-ghost-primary text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Adicionar Observação
                                </button>
                            @endif
                        </div>

                        <div class="space-y-3" id="observacoes-list">
                            @forelse($movimentacao->observacoes as $obs)
                                <div id="observacao-item-{{ $obs->id }}" class="p-4 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700">
                                    <div class="text-sm text-gray-900 dark:text-gray-200 whitespace-pre-line" data-observacao-text>{{ $obs->observacao }}</div>
                                    <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center" data-observacao-created>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <span data-observacao-created-at>Criado em {{ $obs->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center {{ $obs->updated_at && $obs->updated_at->ne($obs->created_at) ? '' : 'hidden' }}" data-observacao-updated>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <span data-observacao-updated-at>Atualizado em {{ $obs->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    @if(auth()->user()->canUpdate('movimentacoes_observacoes') || auth()->user()->canDelete('movimentacoes_observacoes'))
                                        <div class="mt-2 flex items-center gap-3 text-xs" data-observacao-actions>
                                            @if(auth()->user()->canUpdate('movimentacoes_observacoes'))
                                                <button type="button" onclick="openEditObservacaoModal({{ $obs->id }})" class="text-blue-600 hover:text-blue-800">Editar</button>
                                            @endif
                                            @if(auth()->user()->canDelete('movimentacoes_observacoes'))
                                                <button type="button" onclick="confirmDeleteObservacao({{ $obs->id }})" class="text-red-600 hover:text-red-800">Excluir</button>
                                            @endif
                                        </div>
                                    @endif
                                    <template id="observacao-text-{{ $obs->id }}">{{ $obs->observacao }}</template>
                                </div>
                            @empty
                                <div id="observacoes-empty" class="p-4 bg-gray-50 dark:bg-slate-800 rounded-md text-gray-500 dark:text-gray-400">
                                    Nenhuma observação registrada.
                                </div>
                            @endforelse
                        </div>
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
                            <button type="button" onclick="document.getElementById('historicoContent').classList.toggle('hidden'); document.getElementById('historicoChevron').classList.toggle('rotate-90');" class="flex items-center gap-2 text-lg font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer w-full text-left">
                                <svg id="historicoChevron" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Histórico de Alterações
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $activities->count() }})</span>
                            </button>
                            <div id="historicoContent" class="mt-4 space-y-3 hidden">
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
                                                    @elseif($activity->event == 'observacao_criada')
                                                        adicionou uma observação
                                                    @elseif($activity->event == 'observacao_atualizada')
                                                        atualizou uma observação
                                                    @elseif($activity->event == 'observacao_excluida')
                                                        excluiu uma observação
                                                    @else
                                                        {{ $activity->event }}
                                                    @endif
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        @php
                                            $isObsEvent = in_array($activity->event, ['observacao_criada', 'observacao_atualizada', 'observacao_excluida']);
                                        @endphp
                                        @if($isObsEvent)
                                            <div class="mt-3 text-sm space-y-2">
                                                @if($activity->event == 'observacao_criada')
                                                    <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                                        {{ data_get($activity->properties, 'observacao') }}
                                                    </div>
                                                @elseif($activity->event == 'observacao_atualizada')
                                                    <div class="space-y-1">
                                                        <div class="text-red-600 dark:text-red-400 line-through whitespace-pre-line">
                                                            {{ data_get($activity->properties, 'observacao_antiga') }}
                                                        </div>
                                                        <div class="text-green-600 dark:text-green-400 whitespace-pre-line">
                                                            {{ data_get($activity->properties, 'observacao_nova') }}
                                                        </div>
                                                    </div>
                                                @elseif($activity->event == 'observacao_excluida')
                                                    <div class="text-gray-700 dark:text-gray-300 line-through whitespace-pre-line">
                                                        {{ data_get($activity->properties, 'observacao') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($activity->event == 'updated' && $activity->properties->has('old'))
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

    <!-- Modal de Edição de Observação -->
    <div id="editObservacaoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[56rem] max-w-[95vw] shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Editar Observação</h3>
                <div class="mt-4">
                    <textarea id="editObservacaoTexto" rows="4" class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 border rounded-lg focus:outline-none focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600" placeholder="Digite a observação..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button onclick="closeEditObservacaoModal()" class="btn-ghost-secondary">
                        Cancelar
                    </button>
                    <button onclick="saveEditObservacao()" class="btn-ghost-primary">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Observação -->
    <div id="observacaoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-[56rem] max-w-[95vw] shadow-lg rounded-md bg-white dark:bg-slate-800">
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
        let currentEditObservacaoId = null;
        const canUpdateObservacao = @json(auth()->user()->canUpdate('movimentacoes_observacoes'));
        const canDeleteObservacao = @json(auth()->user()->canDelete('movimentacoes_observacoes'));

        function showSuccessToast(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-2xl z-[9999] text-center';
            successDiv.innerHTML = '<div class="flex items-center gap-2 justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><p class="font-semibold text-base">' + message + '</p></div>';
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.style.transition = 'opacity 0.5s';
                successDiv.style.opacity = '0';
                setTimeout(() => successDiv.remove(), 500);
            }, 5000);
        }

        function buildObservacaoItem(obs) {
            const wrapper = document.createElement('div');
            wrapper.id = `observacao-item-${obs.id}`;
            wrapper.className = 'p-4 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700';

            const actionsHtml = (canUpdateObservacao || canDeleteObservacao)
                ? `<div class="mt-2 flex items-center gap-3 text-xs" data-observacao-actions>
                        ${canUpdateObservacao ? `<button type="button" onclick="openEditObservacaoModal(${obs.id})" class="text-blue-600 hover:text-blue-800">Editar</button>` : ''}
                        ${canDeleteObservacao ? `<button type="button" onclick="confirmDeleteObservacao(${obs.id})" class="text-red-600 hover:text-red-800">Excluir</button>` : ''}
                   </div>`
                : '';

            const showUpdated = obs.updated_at && obs.updated_at !== obs.created_at;
            wrapper.innerHTML = `
                <div class="text-sm text-gray-900 dark:text-gray-200 whitespace-pre-line" data-observacao-text></div>
                <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center" data-observacao-created>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span data-observacao-created-at>Criado em ${obs.created_at}</span>
                    </div>
                    <div class="flex items-center ${showUpdated ? '' : 'hidden'}" data-observacao-updated>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span data-observacao-updated-at>${showUpdated ? `Atualizado em ${obs.updated_at}` : ''}</span>
                    </div>
                </div>
                ${actionsHtml}
                <template id="observacao-text-${obs.id}"></template>
            `;

            const textEl = wrapper.querySelector('[data-observacao-text]');
            textEl.textContent = obs.texto;
            const templateEl = wrapper.querySelector(`#observacao-text-${obs.id}`);
            if (templateEl) {
                templateEl.textContent = obs.texto;
            }

            return wrapper;
        }

        function updateEmptyState() {
            const list = document.getElementById('observacoes-list');
            if (!list) return;
            const items = list.querySelectorAll('[id^="observacao-item-"]');
            const empty = document.getElementById('observacoes-empty');

            if (items.length === 0 && !empty) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'observacoes-empty';
                emptyDiv.className = 'p-4 bg-gray-50 dark:bg-slate-800 rounded-md text-gray-500 dark:text-gray-400';
                emptyDiv.textContent = 'Nenhuma observação registrada.';
                list.appendChild(emptyDiv);
            } else if (items.length > 0 && empty) {
                empty.remove();
            }
        }

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

        function openEditObservacaoModal(observacaoId) {
            currentEditObservacaoId = observacaoId;
            const item = document.getElementById(`observacao-item-${observacaoId}`);
            const textEl = item ? item.querySelector('[data-observacao-text]') : null;
            const template = document.getElementById(`observacao-text-${observacaoId}`);
            const texto = (textEl && textEl.textContent) ? textEl.textContent : (template ? template.textContent : '');
            document.getElementById('editObservacaoTexto').value = texto.trim();
            document.getElementById('editObservacaoModal').classList.remove('hidden');
            document.getElementById('editObservacaoTexto').focus();
        }

        function closeEditObservacaoModal() {
            document.getElementById('editObservacaoModal').classList.add('hidden');
            document.getElementById('editObservacaoTexto').value = '';
            currentEditObservacaoId = null;
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
            .then(response => {
                if (response.status === 403) {
                    throw new Error('permission');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const list = document.getElementById('observacoes-list');
                    if (list && data.observacao) {
                        const item = buildObservacaoItem(data.observacao);
                        list.appendChild(item);
                        updateEmptyState();
                    }
                    closeObservacaoModal();
                    showSuccessToast(data.message || 'Observação adicionada com sucesso!');
                } else {
                    alert(data.message || 'Erro ao salvar observação. Tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                if (error.message === 'permission') {
                    alert('Você não tem permissão para adicionar observações. Solicite acesso ao administrador.');
                } else {
                    alert('Erro ao salvar observação. Tente novamente.');
                }
            });
        }

        function saveEditObservacao() {
            const observacao = document.getElementById('editObservacaoTexto').value.trim();

            if (!observacao) {
                alert('Por favor, digite uma observação.');
                return;
            }

            fetch(`/movimentacoes/observacoes/${currentEditObservacaoId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    observacao: observacao
                })
            })
            .then(response => {
                if (response.status === 403) {
                    throw new Error('permission');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const item = document.getElementById(`observacao-item-${currentEditObservacaoId}`);
                    if (item) {
                        const textEl = item.querySelector('[data-observacao-text]');
                        if (textEl) textEl.textContent = observacao;
                        const templateEl = document.getElementById(`observacao-text-${currentEditObservacaoId}`);
                        if (templateEl) templateEl.textContent = observacao;
                        if (data.observacao && data.observacao.updated_at) {
                            const updatedWrapper = item.querySelector('[data-observacao-updated]');
                            const updatedText = item.querySelector('[data-observacao-updated-at]');
                            if (updatedText) {
                                updatedText.textContent = `Atualizado em ${data.observacao.updated_at}`;
                            }
                            if (updatedWrapper) {
                                updatedWrapper.classList.remove('hidden');
                            }
                        }
                    }
                    closeEditObservacaoModal();
                    showSuccessToast(data.message || 'Observação atualizada com sucesso!');
                } else {
                    alert(data.message || 'Erro ao atualizar observação. Tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                if (error.message === 'permission') {
                    alert('Você não tem permissão para editar observações. Solicite acesso ao administrador.');
                } else {
                    alert('Erro ao atualizar observação. Tente novamente.');
                }
            });
        }

        function confirmDeleteObservacao(observacaoId) {
            if (!confirm('Tem certeza que deseja excluir esta observação?')) {
                return;
            }

            fetch(`/movimentacoes/observacoes/${observacaoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.status === 403) {
                    throw new Error('permission');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const item = document.getElementById(`observacao-item-${observacaoId}`);
                    if (item) item.remove();
                    updateEmptyState();
                    showSuccessToast(data.message || 'Observação excluída com sucesso!');
                } else {
                    alert('Erro ao excluir observação. Tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                if (error.message === 'permission') {
                    alert('Você não tem permissão para excluir observações. Solicite acesso ao administrador.');
                } else {
                    alert('Erro ao excluir observação. Tente novamente.');
                }
            });
        }

        // Fechar modal ao clicar fora dele
        document.getElementById('observacaoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeObservacaoModal();
            }
        });

        document.getElementById('editObservacaoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditObservacaoModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
