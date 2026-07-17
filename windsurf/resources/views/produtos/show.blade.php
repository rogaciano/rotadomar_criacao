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
                @if($podeLiberarProducao ?? false)
                    <button onclick="document.getElementById('modal-liberar-producao').classList.remove('hidden')" class="btn-ghost-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                        </svg>
                        Liberar para Produção
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

            @if(session('alert_error'))
                <script>alert('{{ session('alert_error') }}');</script>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    @include('produtos.partials._informacoes-basicas', ['produto' => $produto])

                    @include('produtos.partials._tecidos', ['produto' => $produto])

                    @include('produtos.partials._localizacoes', ['produto' => $produto, 'etapasProducao' => $etapasProducao, 'coletasLogisticaPorPlId' => $coletasLogisticaPorPlId ?? collect()])

                    @include('produtos.partials._variacoes-cores', ['produto' => $produto, 'coresEnriquecidas' => $coresEnriquecidas])

                    @include('produtos.partials._combinacoes-cores', ['produto' => $produto])

                    @include('produtos.partials._documentos-anexos', ['produto' => $produto])



                    @include('produtos.partials._observacoes', ['produto' => $produto, 'observacoes' => $observacoes ?? null])

                    @include('produtos.partials._movimentacoes', ['produto' => $produto, 'movimentacoes' => $movimentacoes])
                </div>
            </div>
        </div>
    </div>

    @include('produtos.partials._modais', ['produto' => $produto, 'movimentacoes' => $movimentacoes])

    {{-- Modal: Liberar para Produção (início da logística de ida) --}}
    @if($podeLiberarProducao ?? false)
    <div id="modal-liberar-producao" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Liberar para Produção</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                O produto <strong>{{ $produto->referencia }}</strong> entrará no fluxo logístico de <strong>ida</strong>
                a partir da localização escolhida. Você pode liberar <strong>uma localização por vez</strong>;
                as que já estiverem em logística não aparecem na lista.
            </p>
            <form action="{{ route('produtos.liberar-producao', $produto->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Localização de origem (onde o produto está aguardando) *</label>
                    @if($localizacoesOrigemIda->isEmpty())
                        <p class="text-sm text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-md p-3">
                            Todas as localizações planejadas já estão em logística, ou nenhuma foi cadastrada na ficha.
                        </p>
                    @else
                        <select name="origem_localizacao_id" id="origem-localizacao-ida" required
                                class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm">
                            <option value="">Selecione...</option>
                            @foreach($localizacoesOrigemIda as $loc)
                                <option value="{{ $loc->id }}"
                                        data-quantidade="{{ $quantidadesOrigemIda[$loc->id] ?? $produto->quantidade }}"
                                        @selected($localizacoesOrigemIda->count() === 1)>
                                    {{ $loc->nome_reduzido ?? $loc->nome_localizacao }}
                                    @if(isset($quantidadesOrigemIda[$loc->id]))
                                        ({{ number_format($quantidadesOrigemIda[$loc->id], 0, ',', '.') }} un.)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Quantidade</label>
                    <input type="number" name="quantidade" id="quantidade-origem-ida" min="1"
                           value="{{ $localizacoesOrigemIda->count() === 1 ? ($quantidadesOrigemIda[$localizacoesOrigemIda->first()->id] ?? $produto->quantidade) : $produto->quantidade }}"
                           class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                    <textarea name="observacao" rows="2" maxlength="255" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-liberar-producao').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" @disabled($localizacoesOrigemIda->isEmpty()) class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">Liberar para Produção</button>
                </div>
            </form>
        </div>
    </div>
    @if($localizacoesOrigemIda->isNotEmpty())
    <script>
        document.getElementById('origem-localizacao-ida')?.addEventListener('change', function () {
            const qty = this.selectedOptions[0]?.dataset?.quantidade;
            const input = document.getElementById('quantidade-origem-ida');
            if (qty && input) input.value = qty;
        });
    </script>
    @endif
    @endif
</x-app-layout>
