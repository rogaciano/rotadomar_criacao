<div class="md:col-span-2">
    <label for="foto_principal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Principal</label>
    <div class="flex items-start gap-4">
        @if($produto->foto_principal)
        <div class="shrink-0">
            <img src="{{ asset('storage/' . $produto->foto_principal) }}" alt="Foto atual" class="h-20 w-20 object-cover rounded-md border border-gray-300">
        </div>
        @endif
        <div class="flex-grow">
            <input type="file" name="foto_principal" id="foto_principal" accept="image/*" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Selecione uma imagem para substituir a atual (JPEG, PNG, JPG, GIF máx 5MB)</p>

            @if($produto->foto_principal)
            <div class="mt-2 flex items-center">
                <input type="checkbox" name="remover_foto_principal" id="remover_foto_principal" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                <label for="remover_foto_principal" class="ml-2 text-sm text-red-600">Remover foto atual</label>
            </div>
            @endif
        </div>
    </div>
</div>
