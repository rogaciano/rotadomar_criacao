<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Tipos') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('tipos.create') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Tipo
                </a>
            </div>
            
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">

                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('tipos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ request('descricao') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Digite a descrição do tipo">
                            </div>
                            
                            <div>
                                <label for="ativo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="incluir_excluidos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Incluir excluídos</label>
                                <div class="flex items-center mt-2">
                                    <input id="incluir_excluidos" name="incluir_excluidos" type="checkbox" value="1" {{ request('incluir_excluidos') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="incluir_excluidos" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Mostrar registros excluídos</label>
                                </div>
                            </div>
                            
                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('tipos.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-200 border border-transparent rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criado em</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-slate-700">
                                @forelse($tipos as $tipo)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 {{ $tipo->trashed() ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $tipo->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $tipo->descricao }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $tipo->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' }}">
                                                {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $tipo->created_at ? $tipo->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('tipos.show', $tipo) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/30">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                
                                                @if(!$tipo->trashed())
                                                    <a href="{{ route('tipos.edit', $tipo) }}" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 p-1 rounded hover:bg-amber-50 dark:hover:bg-amber-900/30">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    
                                                    <form action="{{ route('tipos.destroy', $tipo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este tipo?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/30">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Nenhum tipo encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $tipos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
