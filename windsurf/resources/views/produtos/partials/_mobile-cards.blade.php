{{-- Cards Mobile para Listagem de Produtos --}}
<div class="md:hidden px-4 space-y-4">
    @forelse($produtos as $produto)
        <div class="glass dark:glass-dark rounded-xl overflow-hidden border-none ring-1 ring-black/5 {{ $produto->trashed() ? 'opacity-60' : '' }}">
            {{-- Cabeçalho do Card com Foto --}}
            <div class="relative bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 p-4">
                <div class="flex items-start gap-4">
                    @if($produto->foto_principal)
                        <img src="{{ asset('storage/' . $produto->foto_principal) }}" 
                             alt="{{ $produto->referencia }}" 
                             class="h-20 w-20 rounded-lg object-cover border-2 border-white dark:border-slate-700 shadow-lg">
                    @else
                        <div class="h-20 w-20 rounded-lg bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center border-2 border-white dark:border-slate-700 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate">
                            {{ $produto->referencia }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">
                            {{ $produto->descricao }}
                        </p>
                    </div>
                    @if($produto->concluido_atual)
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @else
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informações do Produto --}}
            <div class="p-4 space-y-3">
                @include('produtos.partials._mobile-card-info', ['produto' => $produto])
                @include('produtos.partials._mobile-card-actions', ['produto' => $produto])
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum produto encontrado</p>
        </div>
    @endforelse
</div>
