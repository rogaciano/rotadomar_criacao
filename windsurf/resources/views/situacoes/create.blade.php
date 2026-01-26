<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Nova Situação') }}
            </h2>
            <a href="{{ route('situacoes.index') }}" class="btn-ghost-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- Formulário de Criação -->
                <form action="{{ route('situacoes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Descrição') }}</label>
                        <input id="descricao" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="descricao" value="{{ old('descricao') }}" required autofocus />
                        @error('descricao')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="prazo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Prazo (dias)') }}</label>
                        <input id="prazo" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="prazo" value="{{ old('prazo') }}" min="0" />
                        <p class="text-xs text-gray-500 mt-1">Prazo em dias úteis. Se definido, tem prioridade sobre o prazo da localização.</p>
                        @error('prazo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Observações') }}</label>
                        <textarea id="observacoes" name="observacoes" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="ativo" value="1" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                            <span class="ml-2 text-gray-700">Ativo</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn-ghost-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
