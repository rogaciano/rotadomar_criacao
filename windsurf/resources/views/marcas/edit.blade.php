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
                    <form action="{{ route('marcas.update', $marca) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome da Marca -->
                            <div>
                                <x-input-label for="nome_marca" :value="__('Nome da Marca')" />
                                <x-text-input id="nome_marca" class="block mt-1 w-full" type="text" name="nome_marca" :value="old('nome_marca', $marca->nome_marca)" required autofocus />
                                <x-input-error :messages="$errors->get('nome_marca')" class="mt-2" />
                            </div>

                            <!-- Campo de Data de Cadastro removido -->

                            <!-- Logo da Marca -->
                            <div>
                                <x-input-label for="logo" :value="__('Logo da Marca')" />
                                @if($marca->logo_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="Logo da {{ $marca->nome_marca }}" class="h-24 w-auto object-contain border rounded p-1">
                                    </div>
                                @endif
                                <input id="logo" type="file" name="logo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" accept="image/*" />
                                <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</p>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>

                            <!-- Status -->

                            <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $marca->ativo) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                <label for="ativo" class="ml-2 block text-sm text-gray-900">Ativo</label>
                            </div>
                        </div>


                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Atualizar
                            </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
