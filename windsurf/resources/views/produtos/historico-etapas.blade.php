<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Histórico de Etapas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Botão voltar -->
            <div class="mb-4">
                @php
                    $fallbackVoltarUrl = route('produtos.show', $produtoLocalizacao->produto_id);
                    $backUrlParam = request('back_url');
                    $voltarUrl = (!empty($backUrlParam) && \Illuminate\Support\Str::startsWith($backUrlParam, ['/', url('/')]))
                        ? $backUrlParam
                        : $fallbackVoltarUrl;
                @endphp
                <a href="{{ $voltarUrl }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar ao Produto
                </a>
            </div>

            <!-- Info da localização -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        {{ $produtoLocalizacao->produto->referencia ?? 'Produto' }} - {{ $produtoLocalizacao->localizacao->nome_localizacao ?? 'Localização' }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        OP: <strong>
                            @if($produtoLocalizacao->ordem_producao)
                                <a href="{{ $produtoLocalizacao->ordem_producao_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $produtoLocalizacao->ordem_producao }}
                                </a>
                            @else
                                N/A
                            @endif
                        </strong> |
                        Quantidade: <strong>{{ number_format($produtoLocalizacao->quantidade, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

            <!-- Timeline do histórico -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Histórico de Mudanças de Etapa</h4>

                    @if($historico->count() > 0)
                        <div class="flow-root" x-data="{ editingId: null, editDate: '', editObservacao: '', actionUrl: '' }">
                            <ul role="list" class="-mb-8">
                                @foreach($historico as $item)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-slate-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @php
                                                        $iconColor = match($item->acao) {
                                                            'avancar' => 'bg-green-500',
                                                            'voltar' => 'bg-yellow-500',
                                                            'definir_inicial' => 'bg-blue-500',
                                                            default => 'bg-gray-500'
                                                        };
                                                        $iconSymbol = match($item->acao) {
                                                            'avancar' => '→',
                                                            'voltar' => '←',
                                                            'definir_inicial' => '●',
                                                            default => '?'
                                                        };
                                                    @endphp
                                                    <span class="h-8 w-8 rounded-full {{ $iconColor }} flex items-center justify-center ring-8 ring-white text-white font-bold text-sm">
                                                        {{ $iconSymbol }}
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-800 dark:text-gray-200">
                                                            <span class="font-medium">{{ $item->descricao_acao }}</span>
                                                            @if($item->etapaAnterior)
                                                                de <span class="font-medium text-gray-600 dark:text-gray-400">{{ $item->etapaAnterior->icone }} {{ $item->etapaAnterior->nome }}</span>
                                                            @endif
                                                            @if($item->etapaNova)
                                                                para <span class="font-medium text-green-600 dark:text-green-400">{{ $item->etapaNova->icone }} {{ $item->etapaNova->nome }}</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                            por <span class="font-medium">{{ $item->usuario->name ?? 'Usuário desconhecido' }}</span>
                                                        </p>
                                                        @if($item->updatedBy)
                                                            <p class="text-[10px] text-gray-400 mt-0.5">
                                                                (Editado por {{ $item->updatedBy->name }})
                                                            </p>
                                                        @endif
                                                        @if($item->observacao)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">
                                                                "{{ $item->observacao }}"
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400 flex flex-col items-end">
                                                        <time datetime="{{ $item->created_at->toISOString() }}">
                                                            {{ $item->created_at->format('d/m/Y') }}
                                                            <br>
                                                            {{ $item->created_at->format('H:i') }}
                                                        </time>

                                                        @if(auth()->user()->isAdmin() || auth()->user()->canUpdate('produto_localizacao_historico_etapas'))
                                                            <button type="button"
                                                                    @click="editingId = {{ $item->id }}; editDate = '{{ $item->created_at->format('Y-m-d\TH:i') }}'; editObservacao = {{ Js::from($item->observacao) }}; actionUrl = '{{ route('produtos.localizacoes.historico-etapas.update', $item->id) }}'"
                                                                    class="mt-1 text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition-colors" title="Editar Data/Hora">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Modal de Edição -->
                            <div x-show="editingId" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="editingId" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="editingId = null"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div x-show="editingId" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                        <form :action="actionUrl" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                                            Editar Data e Hora
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                                Altere a data e hora em que esta movimentação ocorreu.
                                                            </p>
                                                            <div>
                                                                <label for="created_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data e Hora</label>
                                                                <input type="datetime-local" name="created_at" id="created_at" x-model="editDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                                            </div>
                                                            <div class="mt-4">
                                                                <label for="observacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observação</label>
                                                                <textarea name="observacao" id="observacao" rows="4" maxlength="255" x-model="editObservacao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white" placeholder="Digite uma observação para esta mudança de etapa..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Salvar Alteração
                                                </button>
                                                <button type="button" @click="editingId = null" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Nenhum histórico de etapas disponível.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
