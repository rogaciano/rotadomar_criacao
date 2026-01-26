<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Editar Direcionamento Comercial') }}
            </h2>
            <a href="{{ route('direcionamentos-comerciais.index') }}" class="btn-ghost-secondary">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Formulário de Edição -->
                <form action="{{ route('direcionamentos-comerciais.update', $direcionamento) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Descrição') }} *</label>
                        <input id="descricao" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="descricao" value="{{ old('descricao', $direcionamento->descricao) }}" required autofocus maxlength="100" />
                        @error('descricao')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="ativo" value="1" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('ativo', $direcionamento->ativo) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Ativo</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn-ghost-primary">
                            Atualizar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
