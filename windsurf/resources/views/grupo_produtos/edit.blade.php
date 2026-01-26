<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Editar Grupo de Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('grupo_produtos.update', $grupo_produto) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao', $grupo_produto->descricao)" required autofocus />
                            <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                        </div>


                        <div>
                            <x-input-label for="ativo" :value="__('Status')" />
                            <select id="ativo" name="ativo" class="mt-1 block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="1" {{ $grupo_produto->ativo ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ !$grupo_produto->ativo ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <x-input-error :messages="$errors->get('ativo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('grupo_produtos.index') }}" class="btn-ghost-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-ghost-primary">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
