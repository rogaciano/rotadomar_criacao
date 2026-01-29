<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Variações de Cores</label>
    <div class="border border-gray-300 rounded-md p-4">
        <div id="cores-container">
            @forelse($produto->cores as $index => $produtoCor)
            <div class="cor-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <input type="text" name="cores[{{ $index }}][cor]" value="{{ $produtoCor->cor }}" placeholder="Nome da cor" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                    </div>
                    <div class="w-1/4">
                        <input type="text" name="cores[{{ $index }}][codigo_cor]" value="{{ $produtoCor->codigo_cor }}" placeholder="Código" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                    </div>
                    <div class="w-1/4">
                        <input type="number" name="cores[{{ $index }}][quantidade]" value="{{ $produtoCor->quantidade }}" placeholder="Quantidade" min="1" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>
                    <button type="button" class="remove-cor text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <!-- As cores serão adicionadas dinamicamente via JavaScript -->
            @endforelse
        </div>

    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">As cores disponíveis serão carregadas automaticamente com base nos tecidos selecionados</p>
</div>

<!-- Contador de cores selecionadas -->
<div class="mt-4 text-sm text-gray-700">
    <span id="cores-count">0</span> cores selecionadas
</div>
