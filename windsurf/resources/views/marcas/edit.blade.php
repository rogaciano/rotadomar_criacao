<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Marca') }}
            </h2>
            <a href="{{ route('marcas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('marcas.update', $marca) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome da Marca -->
                            <div>
                                <x-input-label for="nome_marca" :value="__('Nome da Marca')" />
                                <x-text-input id="nome_marca" class="block mt-1 w-full" type="text" name="nome_marca" :value="old('nome_marca', $marca->nome_marca)" required autofocus />
                                <x-input-error :messages="$errors->get('nome_marca')" class="mt-2" />
                            </div>
                            
                            <!-- Data de Cadastro -->
                            <div>
                                <x-input-label for="data_cadastro" :value="__('Data de Cadastro')" />
                                <x-text-input id="data_cadastro" class="block mt-1 w-full" type="date" name="data_cadastro" :value="old('data_cadastro', $marca->data_cadastro ? $marca->data_cadastro->format('Y-m-d') : date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('data_cadastro')" class="mt-2" />
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <x-input-label for="ativo" :value="__('Status')" />
                                <select id="ativo" name="ativo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1" {{ old('ativo', $marca->ativo) == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo', $marca->ativo) == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <x-input-error :messages="$errors->get('ativo')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
