<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Criação</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Produtos em fase de criação antes da definição da etapa de saída.</p>
            </div>
            @if(auth()->user()->canCreate('criacao'))
                <a href="{{ route('criacao.create') }}" class="btn-ghost-primary">Nova Criação</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('criacao.index') }}" class="{{ request()->routeIs('criacao.index') ? 'btn-ghost-primary' : 'btn-ghost-secondary' }}">Lista</a>
                <a href="{{ route('criacao.bel') }}" class="{{ request()->routeIs('criacao.bel') ? 'btn-ghost-primary' : 'btn-ghost-secondary' }}">Visão BEL</a>
                <a href="{{ route('criacao.kanban') }}" class="{{ request()->routeIs('criacao.kanban') ? 'btn-ghost-primary' : 'btn-ghost-secondary' }}">Kanban</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4">{{ session('success') }}</div>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <form method="GET" action="{{ route(request()->routeIs('criacao.bel') ? 'criacao.bel' : 'criacao.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referência</label>
                            <input type="text" name="referencia" value="{{ request('referencia') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estilista</label>
                            <select name="estilista_id" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">Todos</option>
                                @foreach($estilistas as $estilista)
                                    <option value="{{ $estilista->id }}" {{ request('estilista_id') == $estilista->id ? 'selected' : '' }}>{{ $estilista->nome_estilista }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
                            <select name="marca_id" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">Todas</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nome_marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Direcionamento</label>
                            <select name="direcionamento_comercial_id" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">Todos</option>
                                @foreach($direcionamentosComerciais as $direcionamento)
                                    <option value="{{ $direcionamento->id }}" {{ request('direcionamento_comercial_id') == $direcionamento->id ? 'selected' : '' }}>{{ $direcionamento->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entrada de</label>
                            <input type="date" name="data_entrada_de" value="{{ request('data_entrada_de') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entrada até</label>
                            <input type="date" name="data_entrada_ate" value="{{ request('data_entrada_ate') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        </div>
                        <div class="md:col-span-3 lg:col-span-6 flex justify-end gap-2">
                            <a href="{{ route(request()->routeIs('criacao.bel') ? 'criacao.bel' : 'criacao.index') }}" class="btn-ghost-secondary">Limpar</a>
                            <button type="submit" class="btn-ghost-primary">Filtrar</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th class="table-header-cell">Referência</th>
                                    <th class="table-header-cell">Descrição</th>
                                    <th class="table-header-cell">Estilista</th>
                                    <th class="table-header-cell">Marca</th>
                                    <th class="table-header-cell">Direcionamento</th>
                                    <th class="table-header-cell">Entrada</th>
                                    <th class="table-header-cell">Status</th>
                                    <th class="table-header-cell text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($produtos as $item)
                                    <tr class="table-row">
                                        <td class="table-cell table-cell-primary">{{ $item->referencia }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->descricao }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->estilista?->nome_estilista ?? '-' }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->marca?->nome_marca ?? '-' }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->direcionamentoComercial?->descricao ?? '-' }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->data_entrada_processo?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="table-cell table-cell-secondary">{{ $item->status?->descricao ?? '-' }}</td>
                                        <td class="table-cell text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('criacao.edit', $item) }}" class="btn-action-edit" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                                <a href="{{ route('criacao.show', $item) }}" class="btn-action-view" title="Ver criação">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="table-cell table-empty">Nenhum produto em criação encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $produtos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
