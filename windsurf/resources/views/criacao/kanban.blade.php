<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Criação - Kanban</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Visualização agrupada dos produtos ainda em criação.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('criacao.index') }}" class="btn-ghost-secondary">Lista</a>
                <a href="{{ route('criacao.bel') }}" class="btn-ghost-secondary">Visão BEL</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($produtosPorEtapa as $etapa => $itens)
                    <div class="glass dark:glass-dark rounded-2xl border-none ring-1 ring-black/5 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $etapa }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">{{ $itens->count() }}</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($itens as $item)
                                <a href="{{ route('criacao.show', $item) }}" class="block rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 hover:border-indigo-400 transition">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $item->referencia }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $item->descricao }}</div>
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $item->estilista?->nome_estilista ?? 'Sem estilista' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->status?->descricao ?? 'Sem status' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="glass dark:glass-dark rounded-2xl border-none ring-1 ring-black/5 p-6 text-gray-500 dark:text-gray-400">Nenhum produto em criação para exibir no kanban.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
