<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Status') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('status.create') }}" class="btn-ghost-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Status
                </a>
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('status.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ request('descricao') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Digite a descrição do status">
                            </div>

                            <div>
                                <label for="ativo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>

                            <div>
                                <label for="incluir_excluidos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Incluir excluídos</label>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="incluir_excluidos" id="incluir_excluidos" value="1" {{ request('incluir_excluidos') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="incluir_excluidos" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Mostrar registros excluídos</label>
                                </div>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="btn-ghost-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('status.index') }}" class="btn-ghost-secondary">
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
                                    <th scope="col" class="table-header-cell">
                                        ID
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Descrição
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Status
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Calc. Necessidade
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Criado em
                                    </th>
                                    <th scope="col" class="table-header-cell text-right">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse ($statuses as $statusItem)
                                    <tr class="{{ $statusItem->trashed() ? 'table-row-trashed' : 'table-row' }}">
                                        <td class="table-cell table-cell-secondary">
                                            {{ $statusItem->id }}
                                        </td>
                                        <td class="table-cell table-cell-primary">
                                            {{ $statusItem->descricao }}
                                        </td>
                                        <td class="table-cell">
                                            <span class="{{ $statusItem->ativo ? 'badge-active' : 'badge-inactive' }}">
                                                {{ $statusItem->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center">
                                            @if($statusItem->calc_necessidade)
                                                <span class="badge-info font-semibold">
                                                    ✓ Sim
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $statusItem->created_at ? $statusItem->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('status.show', $statusItem->id) }}" class="btn-action-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                @if (!$statusItem->trashed())
                                                    <a href="{{ route('status.edit', $statusItem->id) }}" class="btn-action-edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('status.destroy', $statusItem) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-delete" onclick="return confirm('Tem certeza que deseja excluir este status?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('status.destroy', $statusItem) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-restore" onclick="return confirm('Deseja restaurar este status?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="table-cell table-empty">Nenhum status encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $statuses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
