
<div class="hidden md:block relative overflow-x-auto">
    <table class="table-base">
        <thead class="table-header">
            <tr>
                <th scope="col" class="table-header-cell">
                    Descrição
                </th>
                <th scope="col" class="table-header-cell">
                    Referência
                </th>
                <th scope="col" class="table-header-cell">
                    Data de Cadastro
                </th>
                <th scope="col" class="table-header-cell">
                    Estoque
                </th>
                <th scope="col" class="table-header-cell">
                    Status
                </th>
                <th scope="col" class="table-header-cell text-center">
                    Produtos
                </th>
                <th scope="col" class="sticky right-0 table-header-cell text-right bg-gray-50 dark:bg-slate-800 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="table-body">
            @forelse($tecidos as $tecido)
                <tr class="table-row">
                    <td class="table-cell table-cell-primary">
                        {{ $tecido->descricao }}
                    </td>
                    <td class="table-cell table-cell-secondary">
                        {{ $tecido->referencia ?: 'Não informada' }}
                    </td>
                    <td class="table-cell table-cell-secondary">
                        {{ $tecido->created_at ? $tecido->created_at->format('d/m/Y') : 'Sem referência' }}
                    </td>
                    <td class="table-cell table-cell-secondary">
                        @if($tecido->quantidade_estoque)
                            <span class="badge-info">
                                {{ number_format($tecido->quantidade_estoque, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-gray-400 ml-1" title="Última atualização">
                                {{ $tecido->ultima_consulta_estoque ? $tecido->ultima_consulta_estoque->format('d/m/Y H:i') : '' }}
                            </span>
                        @else
                            @if($tecido->referencia)
                                <span class="text-xs text-gray-400">Não consultado</span>
                            @else
                                <span class="text-xs text-gray-400">Sem referência</span>
                            @endif
                        @endif
                    </td>
                    <td class="table-cell">
                        <span class="{{ $tecido->ativo ? 'badge-active' : 'badge-inactive' }}">
                            {{ $tecido->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="table-cell text-center table-cell-secondary">
                        {{ $tecido->produtos->count() }}
                    </td>
                    <td class="sticky right-0 table-cell text-right bg-white dark:bg-slate-900 shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.1)] dark:shadow-[-4px_0_8px_-4px_rgba(0,0,0,0.4)] z-10">
                        <div class="flex items-center justify-end space-x-2">
                            @if(auth()->user() && auth()->user()->canRead('tecidos'))
                            <a href="{{ route('tecidos.show', $tecido) }}" class="btn-action-view">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                            <a href="{{ route('tecidos.edit', $tecido) }}" class="btn-action-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user() && auth()->user()->canDelete('tecidos'))
                            <form action="{{ route('tecidos.destroy', $tecido) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este tecido?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        Nenhum tecido encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
