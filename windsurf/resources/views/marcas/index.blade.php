<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Marcas') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('marcas.create') }}" class="btn-ghost-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Marca
                </a>
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('marcas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="marca" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por nome</label>
                                <input type="text" name="marca" id="marca" value="{{ request('marca') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Digite o nome da marca">
                            </div>
                            <!-- Filtro de Data de Cadastro removido -->
                            <div>
                                <label for="ativo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>
                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="btn-ghost-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('marcas.index') }}" class="btn-ghost-secondary">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Tabela de Marcas -->
                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th scope="col" class="table-header-cell">
                                        Logo
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Nome da Marca
                                    </th>
                                    <!-- Coluna de Data de Cadastro removida -->
                                    <th scope="col" class="table-header-cell">
                                        Status
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Produtos
                                    </th>
                                    <th scope="col" class="table-header-cell text-right">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($marcas as $marca)
                                    <tr class="table-row">
                                        <td class="table-cell">
                                            @if($marca->logo_path)
                                                <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="Logo da {{ $marca->nome_marca }}" class="h-10 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Logo não disponível';">
                                                <!-- Caminho alternativo para debug -->
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $marca->logo_path }}</div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic">Sem logo</span>
                                            @endif
                                        </td>
                                        <td class="table-cell table-cell-primary">
                                            @if($marca->cor_fundo && $marca->cor_fonte)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $marca->cor_fundo }}; color: {{ $marca->cor_fonte }};">
                                                    {{ $marca->nome_marca }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200">
                                                    {{ $marca->nome_marca }}
                                                </span>
                                            @endif
                                        </td>
                                        <!-- Célula de Data de Cadastro removida -->
                                        <td class="table-cell">
                                            <span class="{{ $marca->ativo ? 'badge-active' : 'badge-inactive' }}">
                                                {{ $marca->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center table-cell-secondary">
                                            {{ $marca->produtos->count() }}
                                        </td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('marcas.show', $marca) }}" class="btn-action-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('marcas.edit', $marca) }}" class="btn-action-edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('marcas.destroy', $marca) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta marca?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="table-cell table-empty">
                                            Nenhuma marca encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $marcas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
