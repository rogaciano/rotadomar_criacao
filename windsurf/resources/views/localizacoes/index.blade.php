<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Localizações') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('localizacoes.create') }}" class="btn-ghost-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Adicionar Localização
                    </a>
                </div>

                <a href="{{ route('localizacoes.pdf', request()->query()) }}" target="_blank" class="btn-ghost-rose">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Imprimir PDF
                </a>
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('localizacoes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div class="md:col-span-2">
                                <label for="nome_localizacao" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por nome</label>
                                <input type="text" name="nome_localizacao" id="nome_localizacao" value="{{ request('nome_localizacao') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Digite o nome da localização">
                            </div>

                            <div>
                                <label for="prazo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prazo (em dias)</label>
                                <input type="number" name="prazo" id="prazo" value="{{ request('prazo') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Filtrar por prazo">
                            </div>

                            <div>
                                <label for="ativo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="todos" {{ request('ativo') === 'todos' ? 'selected' : '' }}>{{ request('ativo') === null ? '[Ativos]' : 'Todos' }}</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>

                            <div>
                                <label for="incluir_excluidos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Opções</label>
                                <div class="flex flex-col space-y-2 mt-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="incluir_excluidos" id="incluir_excluidos" value="1" {{ request('incluir_excluidos') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="incluir_excluidos" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Incluir excluídos</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="capacidade_maior_zero" id="capacidade_maior_zero" value="1" {{ request('capacidade_maior_zero') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="capacidade_maior_zero" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Capacidade > 0</label>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="btn-ghost-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('localizacoes.index') }}" class="btn-ghost-secondary">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th scope="col" class="table-header-cell">ID</th>
                                    <th scope="col" class="table-header-cell">Nome</th>
                                    <th scope="col" class="table-header-cell">Nome Reduzido</th>
                                    <th scope="col" class="table-header-cell">Prazo (dias)</th>
                                    <th scope="col" class="table-header-cell">Capacidade</th>
                                    <th scope="col" class="table-header-cell">Status</th>
                                    <th scope="col" class="table-header-cell">Criado em</th>
                                    <th scope="col" class="table-header-cell text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($localizacoes as $localizacao)
                                    <tr class="{{ $localizacao->trashed() ? 'table-row-trashed' : 'table-row' }}">
                                        <td class="table-cell table-cell-secondary">{{ $localizacao->id }}</td>
                                        <td class="table-cell table-cell-primary">{{ $localizacao->nome_localizacao }}</td>
                                        <td class="table-cell">
                                            <span class="text-xs {{ $localizacao->nome_reduzido ? 'badge-info' : 'text-gray-400' }}">{{ $localizacao->nome_reduzido ?? '-' }}</span>
                                        </td>
                                        <td class="table-cell table-cell-secondary">{{ $localizacao->prazo !== null ? $localizacao->prazo : 'N/A' }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $localizacao->capacidade !== null ? $localizacao->capacidade : 'N/A' }}</td>
                                        <td class="table-cell">
                                            <span class="{{ $localizacao->ativo ? 'badge-active' : 'badge-inactive' }}">{{ $localizacao->ativo ? 'Ativo' : 'Inativo' }}</span>
                                        </td>
                                        <td class="table-cell table-cell-secondary">{{ $localizacao->created_at ? $localizacao->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('localizacoes.show', $localizacao) }}" class="btn-action-view">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>

                                                @if(!$localizacao->trashed())
                                                    <a href="{{ route('localizacoes.edit', $localizacao) }}" class="btn-action-edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('localizacoes.destroy', $localizacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta localização?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-delete">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('localizacoes.restore', $localizacao->id) }}" method="POST" class="inline" onsubmit="return confirm('Deseja restaurar esta localização?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn-action-restore">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="table-cell table-empty">
                                            Nenhuma localização encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $localizacoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
