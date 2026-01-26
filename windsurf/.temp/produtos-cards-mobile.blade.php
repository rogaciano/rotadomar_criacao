<!-- Visualização em Cards (Mobile) -->
<div class="md:hidden px-4 space-y-4">
    @forelse($produtos as $produto)
        <div class="glass dark:glass-dark rounded-xl overflow-hidden border-none ring-1 ring-black/5 {{ $produto->trashed() ? 'opacity-60' : '' }}">
            <!-- Cabeçalho do Card com Foto -->
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
                    <!-- Status Badge -->
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

            <!-- Informações do Produto -->
            <div class="p-4 space-y-3">
                <!-- Marca e Grupo -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Marca</p>
                        @if($produto->marca && $produto->marca->logo_path)
                            <img src="{{ asset('storage/' . $produto->marca->logo_path) }}" 
                                 alt="{{ $produto->marca->nome_marca }}" 
                                 class="h-5 w-auto object-contain">
                        @else
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $produto->marca->nome_marca ?? 'N/A' }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Grupo</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $produto->grupoProduto->descricao ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Datas -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Prev. Produção</p>
                        <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                            {{ $produto->data_prevista_producao_mes_ano ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Facção</p>
                        @if($produto->primeira_data_prevista_faccao)
                            <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
                            </p>
                        @else
                            <p class="text-xs italic text-gray-400 dark:text-gray-500">Sem data</p>
                        @endif
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2">
                    <!-- Status -->
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                        {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                    </span>
                    
                    <!-- Localização -->
                    @if($produto->localizacao_atual)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                            📍 {{ $produto->localizacao_atual->nome_localizacao }}
                        </span>
                    @endif

                    <!-- Situação -->
                    @if($produto->situacao_atual)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
                            {{ $produto->situacao_atual->descricao }}
                        </span>
                    @endif

                    <!-- Direcionamento -->
                    @if($produto->direcionamentoComercial)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-200">
                            {{ $produto->direcionamentoComercial->descricao }}
                        </span>
                    @endif
                </div>

                <!-- Botões de Ação -->
                <div class="flex gap-2 pt-2 border-t border-slate-200 dark:border-slate-700">
                    @if(auth()->user()->canRead('produtos'))
                        <a href="{{ route('produtos.show', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" 
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="text-sm font-medium">Ver</span>
                        </a>
                    @endif

                    @if(!$produto->trashed() && auth()->user()->canUpdate('produtos'))
                        <a href="{{ route('produtos.edit', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" 
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="text-sm font-medium">Editar</span>
                        </a>
                    @endif

                    @if(!$produto->trashed() && auth()->user()->canDelete('produtos'))
                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="text-sm font-medium">Excluir</span>
                            </button>
                        </form>
                    @endif

                    @if($produto->trashed() && auth()->user()->canDelete('produtos'))
                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Tem certeza que deseja restaurar este produto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span class="text-sm font-medium">Restaurar</span>
                            </button>
                        </form>
                    @endif
                </div>
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
