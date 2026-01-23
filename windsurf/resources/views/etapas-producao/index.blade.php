<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Etapas de Produção') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('etapas-producao.create') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Etapa
                </a>
                
                <a href="{{ route('etapas-producao.visualizar-fluxo') }}" target="_blank" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Visualizar Fluxo
                </a>

                <a href="{{ route('etapas-producao.visualizar-fluxo-quantidades') }}" target="_blank" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-200" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 10-2 0M15 8l-1.333-2.001L12 4.001l-1.667 1.999L9 8m6 0v8a2 2 0 01-2 2H9a2 2 0 01-2-2V8m8 0h-6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10M7 16h10" />
                    </svg>
                    Fluxo com Quantidades
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('etapas-producao.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="nome" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por nome</label>
                                <input type="text" name="nome" id="nome" value="{{ request('nome') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Digite o nome da etapa">
                            </div>

                            <div>
                                <label for="ativo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('etapas-producao.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-200 border border-transparent rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordem</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cor / Ícone</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transições</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Obriga Data Entrega</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-slate-700">
                                @forelse ($etapas as $etapa)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $etapa->ordem }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($etapa->icone)
                                                    <span class="mr-2 text-lg">{{ $etapa->icone }}</span>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $etapa->nome }}</div>
                                                    @if($etapa->descricao)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($etapa->descricao, 40) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $corClasses = [
                                                    'blue' => 'bg-blue-100 text-blue-800',
                                                    'green' => 'bg-green-100 text-green-800',
                                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                                    'red' => 'bg-red-100 text-red-800',
                                                    'purple' => 'bg-purple-100 text-purple-800',
                                                    'gray' => 'bg-gray-100 text-gray-800',
                                                    'indigo' => 'bg-indigo-100 text-indigo-800',
                                                    'pink' => 'bg-pink-100 text-pink-800',
                                                    'orange' => 'bg-orange-100 text-orange-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $corClasses[$etapa->cor] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $etapa->icone ?? '●' }} {{ ucfirst($etapa->cor) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @php
                                                $transicoes = $etapa->transicoesOrigem()->with('etapaDestino')->get();
                                            @endphp
                                            @if($transicoes->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($transicoes->take(3) as $transicao)
                                                        <span class="px-2 py-0.5 text-xs bg-gray-200 text-gray-700 dark:bg-slate-700 dark:text-gray-300 rounded">
                                                            → {{ $transicao->etapaDestino->nome ?? '?' }}
                                                        </span>
                                                    @endforeach
                                                    @if($transicoes->count() > 3)
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $transicoes->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $etapa->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' }}">
                                                {{ $etapa->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            @if($etapa->obriga_data_entrega_faccao)
                                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                                    Sim
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex item-center justify-center">
                                                <a href="{{ route('etapas-producao.show', $etapa->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 bg-transparent p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all" title="Ver detalhes">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                
                                                <a href="{{ route('etapas-producao.edit', $etapa->id) }}" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 bg-transparent p-1 rounded-full hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-all" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                
                                                <form action="{{ route('etapas-producao.destroy', $etapa) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-transparent p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 transition-all" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta etapa?')">
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
                                        <td colspan="7" class="py-6 px-6 text-center text-gray-500 dark:text-gray-400">Nenhuma etapa de produção encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $etapas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Scripts removidos pois o fluxo agora abre em nova aba
    </script>
    @endpush
</x-app-layout>
