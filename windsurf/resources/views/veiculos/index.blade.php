<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                Veículos
            </h2>
            <a href="{{ route('veiculos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Novo Veículo
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4">
                <form method="GET" action="{{ route('veiculos.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 md:items-end">
                    <div>
                        <label for="busca" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Busca</label>
                        <input type="text" name="busca" id="busca" value="{{ request('busca') }}" placeholder="Placa ou descrição"
                               class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                    </div>
                    <div>
                        <label for="ativo" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Status</label>
                        <select name="ativo" id="ativo" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm">
                            <option value="">Todos</option>
                            <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                            <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Filtrar</button>
                        <a href="{{ route('veiculos.index') }}" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-gray-300">Limpar</a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                @if($veiculos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Placa</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Descrição</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @foreach($veiculos as $veiculo)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $veiculo->placa }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $veiculo->descricao ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $veiculo->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                                {{ $veiculo->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="{{ route('veiculos.edit', $veiculo) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">Editar</a>
                                            <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 dark:border-slate-700">
                        {{ $veiculos->links() }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhum veículo cadastrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
