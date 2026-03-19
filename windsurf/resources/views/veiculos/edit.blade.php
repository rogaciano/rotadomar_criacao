<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Editar Veículo — {{ $veiculo->placa }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('veiculos.update', $veiculo) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="placa" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Placa *</label>
                            <input type="text" name="placa" id="placa" value="{{ old('placa', $veiculo->placa) }}" required maxlength="10"
                                   class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm uppercase" />
                            @error('placa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="descricao" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                            <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $veiculo->descricao) }}" maxlength="255"
                                   class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                            @error('descricao')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $veiculo->ativo) ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:bg-slate-700 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500" />
                            <label for="ativo" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ativo</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('veiculos.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</a>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
