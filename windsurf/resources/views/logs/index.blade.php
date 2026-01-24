<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Logs do Sistema') }}
        </h2>
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
                                        Nome do Arquivo
                                    </th>
                                    <th class="table-header-cell">
                                        Tamanho
                                    </th>
                                    <th class="table-header-cell">
                                        Última Modificação
                                    </th>
                                    <th class="table-header-cell text-right">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($logsList as $log)
                                    <tr class="table-row">
                                        <td class="table-cell table-cell-primary">
                                            {{ $log['name'] }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $log['size'] }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $log['modified'] }}
                                        </td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('logs.show', basename($log['path'])) }}" class="btn-action-view">
                                                    Visualizar
                                                </a>
                                                <a href="{{ route('logs.download', basename($log['path'])) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded hover:bg-green-50 dark:hover:bg-green-900/30 transition-all">
                                                    Download
                                                </a>
                                                <form action="{{ route('logs.destroy', basename($log['path'])) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo de log?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="table-cell table-empty">
                                            Nenhum arquivo de log encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
