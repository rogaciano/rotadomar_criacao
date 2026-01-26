<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Novo Grupo de Produto') }}
            </h2>
            <a href="{{ route('grupo-produtos.index') }}" class="btn-ghost-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('grupo-produtos.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome do Grupo de Produto -->
                            <div>
                                <x-input-label for="grupo_produto" :value="__('Nome do Grupo de Produto')" />
                                <x-text-input id="grupo_produto" class="block mt-1 w-full" type="text" name="grupo_produto" :value="old('grupo_produto')" required autofocus />
                                <x-input-error :messages="$errors->get('grupo_produto')" class="mt-2" />
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <x-input-label for="ativo" :value="__('Status')" />
                                <select id="ativo" name="ativo" class="block mt-1 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1" {{ old('ativo') == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <x-input-error :messages="$errors->get('ativo')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="btn-ghost-primary">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
