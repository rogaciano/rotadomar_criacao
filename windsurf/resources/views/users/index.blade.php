<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Gerenciamento de Usuários') }}
            </h2>
            <a href="{{ route('users.create') }}" class="btn-ghost-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Novo Usuário
            </a>
        </div>
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

                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-100 dark:bg-slate-800/50 p-4 rounded-lg border border-gray-200 dark:border-slate-700">
                        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                                <input type="text" name="name" id="name" value="{{ request('name') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Buscar por nome">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="text" name="email" id="email" value="{{ request('email') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Buscar por email">
                            </div>

                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização</label>
                                <select name="localizacao_id" id="localizacao_id" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ request('localizacao_id') == $localizacao->id ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="is_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                                <select name="is_admin" id="is_admin" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('is_admin') === '1' ? 'selected' : '' }}>Administrador</option>
                                    <option value="0" {{ request('is_admin') === '0' ? 'selected' : '' }}>Usuário</option>
                                </select>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="btn-ghost-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('users.index') }}" class="btn-ghost-secondary">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-header">
                                <tr>
                                    <th scope="col" class="table-header-cell">
                                        Nome
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Email
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Localização
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Tipo
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Criado em
                                    </th>
                                    <th scope="col" class="table-header-cell text-right">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse($users as $user)
                                    <tr class="table-row">
                                        <td class="table-cell table-cell-primary">
                                            {{ $user->name }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $user->email }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $user->localizacao ? $user->localizacao->nome_localizacao : 'N/A' }}
                                        </td>
                                        <td class="table-cell">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200' }}">
                                                {{ $user->isAdmin() ? 'Administrador' : 'Usuário' }}
                                            </span>
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn-action-edit" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('user-permissions.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Permissões">
                                                    Permissões
                                                </a>
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="table-cell table-empty">
                                            Nenhum usuário encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
