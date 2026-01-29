
<div class="md:hidden space-y-4">
    @forelse($tecidos as $tecido)
        <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg p-4 border border-gray-200 dark:border-slate-800">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $tecido->descricao }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Ref: {{ $tecido->referencia ?: 'N/A' }}
                    </p>
                </div>
                <span class="{{ $tecido->ativo ? 'badge-active' : 'badge-inactive' }}">
                    {{ $tecido->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs mb-3">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Estoque</p>
                    <div class="font-medium text-gray-900 dark:text-white mt-0.5">
                        @if($tecido->quantidade_estoque)
                            {{ number_format($tecido->quantidade_estoque, 0, ',', '.') }}
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Produtos</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                        {{ $tecido->produtos->count() }}
                    </p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 dark:text-gray-400">Data de Cadastro</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                        {{ $tecido->created_at ? $tecido->created_at->format('d/m/Y') : '-' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-slate-800">
                @if(auth()->user() && auth()->user()->canRead('tecidos'))
                <a href="{{ route('tecidos.show', $tecido) }}" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                @endif
                @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                <a href="{{ route('tecidos.edit', $tecido) }}" class="p-2 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                @endif
                @if(auth()->user() && auth()->user()->canDelete('tecidos'))
                <form action="{{ route('tecidos.destroy', $tecido) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este tecido?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-800">
            Nenhum tecido encontrado.
        </div>
    @endforelse
</div>
