{{-- Tabela Desktop de Produtos --}}
<div class="hidden md:block table-container">
    <table class="table-base">
        <thead class="table-header">
            <tr>
                <th scope="col" class="table-header-cell">Referência</th>
                <th scope="col" class="table-header-cell">Descrição</th>
                <th scope="col" class="table-header-cell">Prev. Produção</th>
                <th scope="col" class="table-header-cell">Facção (1ª Data)</th>
                <th scope="col" class="table-header-cell">Marca</th>
                <th scope="col" class="table-header-cell">Grupo</th>
                <th scope="col" class="table-header-cell">Direcionamento</th>
                <th scope="col" class="table-header-cell">Status</th>
                <th scope="col" class="table-header-cell text-center w-8">OK</th>
                <th scope="col" class="table-header-cell">Localização</th>
                <th scope="col" class="table-header-cell">Situação</th>
                <th scope="col" class="sticky right-0 table-header-cell text-right bg-gray-50 dark:bg-slate-800 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="table-body">
            @forelse($produtos as $produto)
                <tr class="{{ $produto->trashed() ? 'table-row-trashed' : 'table-row' }}">
                    {{-- Referência com Foto --}}
                    <td class="table-cell table-cell-primary line-clamp-1">
                        <div class="flex items-center">
                            @if($produto->foto_principal)
                                <img src="{{ asset('storage/' . $produto->foto_principal) }}" alt="" class="h-10 w-10 rounded-md object-cover mr-3 border border-gray-200 dark:border-gray-700 shadow-sm">
                            @endif
                            <span>{{ $produto->referencia }}</span>
                        </div>
                    </td>

                    {{-- Descrição --}}
                    <td class="table-cell table-cell-secondary">
                        {{ $produto->descricao }}
                    </td>

                    {{-- Prev. Produção --}}
                    <td class="table-cell table-cell-secondary">
                        {{ $produto->data_prevista_producao_mes_ano }}
                    </td>

                    {{-- Facção --}}
                    <td class="table-cell table-cell-secondary">
                        @if($produto->primeira_data_prevista_faccao)
                            {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem data</span>
                        @endif
                    </td>

                    {{-- Marca --}}
                    <td class="table-cell table-cell-secondary">
                        @if($produto->marca && $produto->marca->logo_path)
                            <img src="{{ asset('storage/' . $produto->marca->logo_path) }}" alt="{{ $produto->marca->nome_marca }}" class="h-6 w-auto object-contain" title="{{ $produto->marca->nome_marca }}">
                        @else
                            {{ $produto->marca->nome_marca ?? 'N/A' }}
                        @endif
                    </td>

                    {{-- Grupo --}}
                    <td class="table-cell table-cell-secondary">
                        {{ $produto->grupoProduto->descricao ?? 'N/A' }}
                    </td>

                    {{-- Direcionamento --}}
                    <td class="table-cell table-cell-secondary">
                        @if($produto->direcionamentoComercial)
                            <span class="font-semibold">
                                {{ $produto->direcionamentoComercial->descricao }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem direcionamento</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="table-cell">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                        </span>
                    </td>

                    {{-- OK (Concluído) --}}
                    <td class="table-cell text-center w-8">
                        @if($produto->concluido_atual)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mx-auto text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mx-auto text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </td>

                    {{-- Localização --}}
                    <td class="table-cell table-cell-secondary">
                        @if($produto->localizacao_atual)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                {{ $produto->localizacao_atual->nome_localizacao }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Não localizado</span>
                        @endif
                    </td>

                    {{-- Situação --}}
                    <td class="table-cell table-cell-secondary">
                        @if($produto->situacao_atual)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
                                {{ $produto->situacao_atual->descricao }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Sem situação</span>
                        @endif
                    </td>

                    {{-- Ações --}}
                    <td class="sticky right-0 table-cell text-right bg-white dark:bg-slate-900 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                        <div class="flex items-center justify-end space-x-2">
                            @if(auth()->user()->canRead('produtos'))
                                <a href="{{ route('produtos.show', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-view">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @endif

                            @if(!$produto->trashed() && auth()->user()->canUpdate('produtos'))
                                <a href="{{ route('produtos.edit', $produto->id) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="btn-action-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif

                            @if(!$produto->trashed() && auth()->user()->canDelete('produtos'))
                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            @if($produto->trashed() && auth()->user()->canDelete('produtos'))
                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja restaurar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-restore">
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
                    <td colspan="12" class="table-cell table-empty">
                        Nenhum produto encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
