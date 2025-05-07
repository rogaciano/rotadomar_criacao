<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nova Situação') }}
            </h2>
            <a href="{{ route('situacoes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <!-- Formulário de Criação -->
                <form action="{{ route('situacoes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <x-label for="descricao" value="{{ __('Descrição') }}" />
                        <x-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao')" required autofocus />
                        @error('descricao')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="ativo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                            <span class="ml-2 text-gray-700">Ativo</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Salvar') }}
                        </x-button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
