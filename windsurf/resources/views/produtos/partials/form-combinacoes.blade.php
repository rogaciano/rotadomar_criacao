<div class="mt-6">
    <div class="flex justify-between items-center mb-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Combinações de Cores</label>
        <button type="button" id="add-combinacao" onclick="showCombinacaoModal()" class="btn-ghost-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nova Combinação
        </button>
    </div>
    <p class="mt-1 mb-2 text-xs text-gray-500 dark:text-gray-400">Crie combinações de cores para este produto, especificando os tecidos e cores utilizados em cada combinação.</p>
    <div class="border border-gray-300 rounded-md p-4">
        <div id="combinacoes-container">
            <!-- As combinações serão carregadas via JavaScript -->
            <div class="text-center py-4 text-gray-500 italic">
                Clique em "Nova Combinação" para adicionar uma combinação de cores.
            </div>
        </div>
    </div>
</div>
