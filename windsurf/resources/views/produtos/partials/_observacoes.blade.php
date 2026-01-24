<!-- Observações -->
<div class="bg-gray-50 dark:bg-slate-800/50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Observações</h3>
        @if(auth()->user()->canUpdate('produtos'))
            <button type="button" onclick="abrirModalObservacao()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-700 focus:outline-none focus:border-purple-700 focus:ring focus:ring-purple-300 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nova Observação
            </button>
        @endif
    </div>

    @php
        // Garantir que $observacoes existe
        if (!isset($observacoes)) {
            $observacoes = $produto->observacoes ?? collect();
        }
    @endphp

    @if($observacoes && $observacoes->count() > 0)
        <div class="space-y-3">
            @foreach($observacoes as $obs)
                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="text-sm text-gray-700 dark:text-gray-200 prose prose-sm max-w-none">{!! $obs->observacao !!}</div>
                            <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium">{{ $obs->usuario ? $obs->usuario->name : 'Sistema' }}</span>
                                <span class="mx-2">•</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $obs->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        @if(auth()->user()->canUpdate('produtos'))
                            <button onclick="removerObservacao({{ $obs->id }})" class="ml-4 text-red-600 hover:text-red-800" title="Remover observação">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-400 dark:text-gray-500 italic">Nenhuma observação registrada para este produto</p>
    @endif
</div>
