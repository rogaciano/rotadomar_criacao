<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permissões do Usuário') }}: {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                    {{ __('Voltar') }}
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    {{ __('Usuários') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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

                    <form method="POST" action="{{ route('user-permissions.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissão</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer col-toggle" data-col="can_create" title="Marcar/desmarcar todas as permissões de Criar">Criar</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer col-toggle" data-col="can_read" title="Marcar/desmarcar todas as permissões de Ler">Ler</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer col-toggle" data-col="can_update" title="Marcar/desmarcar todas as permissões de Atualizar">Atualizar</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer col-toggle" data-col="can_delete" title="Marcar/desmarcar todas as permissões de Excluir">Excluir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($permissions as $permission)
                                        @php($up = $userPermissions->get($permission->id))
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 perm-cell cursor-pointer select-none" title="Clique para marcar/desmarcar todas as ações desta linha">
                                                <div class="font-medium perm-title cursor-pointer select-none text-indigo-700 hover:underline" title="Marcar/desmarcar todas as ações desta permissão">{{ $permission->display_name ?? $permission->name }}</div>
                                                @if(!empty($permission->description))
                                                    <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_create]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $up && $up->can_create ? 'checked' : '' }} />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_read]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $up && $up->can_read ? 'checked' : '' }} />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_update]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $up && $up->can_update ? 'checked' : '' }} />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" name="permissions[{{ $permission->id }}][can_delete]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $up && $up->can_delete ? 'checked' : '' }} />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button>
                                {{ __('Salvar Permissões') }}
                            </x-button>
                        </div>
                    </form>
                    <p class="text-xs text-gray-500 mt-2">
                        Dica: clique no nome da permissão para marcar/desmarcar todas as ações da linha.
                    </p>
                    <p class="text-xs text-gray-500 mt-4">
                        Dica: desmarque todas as ações de uma permissão para remover a configuração específica deste usuário (o sistema voltará a considerar apenas as permissões do(s) grupo(s)).
                    </p>
                    <script>
                        (function() {
                            // Event delegation: normalize target to handle text-node clicks
                            document.addEventListener('click', function (e) {
                                var target = e.target;
                                if (target && target.nodeType === 3) { // TEXT_NODE
                                    target = target.parentNode;
                                }
                                if (!(target instanceof Element)) return;
                                // Do not toggle when directly clicking a checkbox
                                if (target.closest('input[type="checkbox"]')) return;
                                // Row toggle by clicking the permission title
                                var title = target.closest('.perm-title, .perm-cell');
                                if (title) {
                                    var row = title.closest('tr');
                                    if (!row) return;
                                    var boxes = Array.from(row.querySelectorAll('input[type="checkbox"]'));
                                    if (boxes.length === 0) return;
                                    var allChecked = boxes.every(function (b) { return b.checked; });
                                    boxes.forEach(function (b) { b.checked = !allChecked; });
                                    return;
                                }
                                // Column toggle by clicking the column header
                                var colHeader = target.closest('.col-toggle');
                                if (colHeader) {
                                    var col = colHeader.getAttribute('data-col');
                                    if (!col) return;
                                    var selector = 'tbody input[type="checkbox"][name$="[' + col + ']"]';
                                    var colBoxes = Array.from(document.querySelectorAll(selector));
                                    if (colBoxes.length === 0) return;
                                    var colAllChecked = colBoxes.every(function (b) { return b.checked; });
                                    colBoxes.forEach(function (b) { b.checked = !colAllChecked; });
                                    return;
                                }
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
