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



                    @include('produtos.partials._observacoes', ['produto' => $produto, 'observacoes' => $observacoes ?? null])

                    @include('produtos.partials._movimentacoes', ['produto' => $produto, 'movimentacoes' => $movimentacoes])
                </div>
            </div>
        </div>
    </div>

    @include('produtos.partials._modais', ['produto' => $produto, 'movimentacoes' => $movimentacoes])
</x-app-layout>
