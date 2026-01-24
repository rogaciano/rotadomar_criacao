<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Permissões') }}
            </h2>
            <a href="{{ route('permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nova Permissão
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th class="table-header-cell">
                                        ID
                                    </th>
                                    <th class="table-header-cell">
                                        Nome
                                    </th>
                                    <th class="table-header-cell">
                                        Nome de Exibição
                                    </th>
                                    <th class="table-header-cell">
                                        Descrição
                                    </th>
                                    <th class="table-header-cell text-right">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($permissions as $permission)
                                    <tr class="table-row">
                                        <td class="table-cell table-cell-secondary">
                                            {{ $permission->id }}
                                        </td>
                                        <td class="table-cell table-cell-primary">
                                            {{ $permission->name }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $permission->display_name }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $permission->description }}
                                        </td>
                                        <td class="table-cell text-right flex items-center justify-end space-x-2">
                                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn-action-edit">
                                                    Editar
                                                </a>
                                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta permissão?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete">
                                                        Excluir
                                                    </button>
                                                </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="table-cell table-empty">
                                            Nenhuma permissão encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
