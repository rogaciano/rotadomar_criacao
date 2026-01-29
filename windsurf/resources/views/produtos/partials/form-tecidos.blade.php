<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tecidos</label>
    <div class="border border-gray-300 rounded-md p-4">
        <div id="tecidos-container">
            @forelse($produto->tecidos as $index => $produtoTecido)
            <div class="tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <select name="tecidos[{{ $index }}][tecido_id]" class="tecido-select select2 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700">
                            <option value="">Selecione um tecido</option>
                            @foreach($tecidos as $tecido)
                            <option value="{{ $tecido->id }}" {{ $produtoTecido->id == $tecido->id ? 'selected' : '' }} class="text-gray-700">
                                {{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/4">
                        <input type="number" name="tecidos[{{ $index }}][consumo]" value="{{ $produtoTecido->pivot->consumo }}" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>
                    <button type="button" class="remove-tecido text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="flex-grow">
                        <select name="tecidos[0][tecido_id]" class="tecido-select select2 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700">
                            <option value="">Selecione um tecido</option>
                            @foreach($tecidos as $tecido)
                            <option value="{{ $tecido->id }}" class="text-gray-700">
                                {{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/4">
                        <input type="number" name="tecidos[0][consumo]" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>
                    <button type="button" class="remove-tecido text-red-500 hover:text-red-700" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforelse
        </div>
        <button type="button" id="add-tecido" class="mt-3 btn-ghost-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Adicionar Tecido
        </button>
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Adicione um ou mais tecidos utilizados neste produto</p>
</div>
