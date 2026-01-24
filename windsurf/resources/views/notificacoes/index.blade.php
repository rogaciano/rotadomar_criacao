<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ __('Notificações') }}
                </h2>
                @if($podeVerTodas ?? false)
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Visualização Global - Todas as Localizações
                        </span>
                    </p>
                @endif
            </div>
            <button id="marcar-todas-visualizadas" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Marcar Todas como Lidas
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dashboard de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Card Total -->
                <a href="{{ route('notificacoes.index') }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow {{ !request('tipo') && !request('status') ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                            </div>
                            <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-full">
                                <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card Não Lidas -->
                @php
                    $statusParams = request()->all();
                    if (request('status') === 'nao_lida') {
                        unset($statusParams['status']);
                    } else {
                        $statusParams['status'] = 'nao_lida';
                    }
                @endphp
                <a href="{{ route('notificacoes.index', $statusParams) }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer {{ request('status') === 'nao_lida' ? 'ring-2 ring-red-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Não Lidas</p>
                                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['nao_lidas'] }}</p>
                            </div>
                            <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-full">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card Novas Movimentações -->
                @php
                    $novaParams = request()->all();
                    if (request('tipo') === 'nova_movimentacao') {
                        unset($novaParams['tipo']);
                    } else {
                        $novaParams['tipo'] = 'nova_movimentacao';
                    }
                @endphp
                <a href="{{ route('notificacoes.index', $novaParams) }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer {{ request('tipo') === 'nova_movimentacao' ? 'ring-2 ring-green-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Novas</p>
                                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['novas'] }}</p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-full">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card Concluídas -->
                @php
                    $concluidaParams = request()->all();
                    if (request('tipo') === 'movimentacao_concluida') {
                        unset($concluidaParams['tipo']);
                    } else {
                        $concluidaParams['tipo'] = 'movimentacao_concluida';
                    }
                @endphp
                <a href="{{ route('notificacoes.index', $concluidaParams) }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer {{ request('tipo') === 'movimentacao_concluida' ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Concluídas</p>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['concluidas'] }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-full">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card Mudança de Etapa -->
                @php
                    $etapaParams = request()->all();
                    if (request('tipo') === 'mudanca_etapa') {
                        unset($etapaParams['tipo']);
                    } else {
                        $etapaParams['tipo'] = 'mudanca_etapa';
                    }
                @endphp
                <a href="{{ route('notificacoes.index', $etapaParams) }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer {{ request('tipo') === 'mudanca_etapa' ? 'ring-2 ring-purple-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mudança Etapa</p>
                                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['mudancas_etapa'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-full">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card Atribuição de Localização -->
                @php
                    $atribParams = request()->all();
                    if (request('tipo') === 'atribuicao_localizacao') {
                        unset($atribParams['tipo']);
                    } else {
                        $atribParams['tipo'] = 'atribuicao_localizacao';
                    }
                @endphp
                <a href="{{ route('notificacoes.index', $atribParams) }}" 
                   class="glass dark:glass-dark overflow-hidden sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer {{ request('tipo') === 'atribuicao_localizacao' ? 'ring-2 ring-indigo-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Atribuições</p>
                                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['atribuicoes'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-full">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Lista de Notificações -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Indicador de Filtro Ativo -->
                    @if(request('tipo') || request('status'))
                        <div class="mb-4 flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-3">
                            <div class="flex items-center space-x-2 flex-wrap">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">
                                    Filtros ativos:
                                </span>
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if(request('status') === 'nao_lida')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Não Lidas
                                        </span>
                                    @endif
                                    @if(request('tipo') === 'nova_movimentacao')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Novas Movimentações
                                        </span>
                                    @endif
                                    @if(request('tipo') === 'movimentacao_concluida')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Movimentações Concluídas
                                        </span>
                                    @endif
                                    @if(request('tipo') === 'mudanca_etapa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Mudanças de Etapa
                                        </span>
                                    @endif
                                    @if(request('tipo') === 'atribuicao_localizacao')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Atribuições de Localização
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('notificacoes.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">
                                Limpar filtros
                            </a>
                        </div>
                    @endif

                    @if($notificacoes->count() > 0)
                        <div class="space-y-4">
                            @foreach($notificacoes as $notificacao)
                                <div class="border rounded-lg p-4 {{ $notificacao->isVisualizada() ? 'bg-gray-50 dark:bg-gray-800/50' : 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-lg font-semibold {{ $notificacao->isVisualizada() ? 'text-gray-700' : 'text-blue-800' }}">
                                                    {{ $notificacao->titulo }}
                                                </h3>
                                                @if($notificacao->tipo === 'nova_movimentacao')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Nova
                                                    </span>
                                                @elseif($notificacao->tipo === 'mudanca_etapa')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Etapa
                                                    </span>
                                                @elseif($notificacao->tipo === 'atribuicao_localizacao')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        Atribuição
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Concluída
                                                    </span>
                                                @endif
                                                @if(!$notificacao->isVisualizada())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Não lida
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $notificacao->mensagem }}</p>
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span>{{ $notificacao->created_at->diffForHumans() }}</span>
                                                <span>{{ $notificacao->localizacao->nome_localizacao }}</span>
                                                @if($notificacao->movimentacao && $notificacao->movimentacao->criadoPor)
                                                    <span>Criada por {{ $notificacao->movimentacao->criadoPor->name }}</span>
                                                @endif
                                                @if($notificacao->isVisualizada())
                                                    <span>Visualizada por {{ $notificacao->visualizadaPor->name }} em {{ $notificacao->visualizada_em->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('notificacoes.visualizar', $notificacao->id) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ in_array($notificacao->tipo, ['mudanca_etapa', 'atribuicao_localizacao']) ? 'Ver Produto' : 'Ver Movimentação' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            {{ $notificacoes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400 text-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12h5v12z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma notificação</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Você não possui notificações no momento.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('marcar-todas-visualizadas').addEventListener('click', function() {
            if (confirm('Deseja marcar todas as notificações como visualizadas?')) {
                fetch('{{ route("api.notificacoes.marcar-todas-visualizadas") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao marcar notificações como visualizadas');
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
