<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Detalhes da Etapa de Produção') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('etapas-producao.index') }}" class="btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar
                </a>
                <a href="{{ route('etapas-producao.edit', $etapa) }}" class="btn-ghost-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Informações Básicas -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center">
                            @if($etapa->icone)
                                <span class="text-4xl mr-4">{{ $etapa->icone }}</span>
                            @endif
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $etapa->nome }}</h3>
                                @if($etapa->descricao)
                                    <p class="text-gray-600 dark:text-gray-400">{{ $etapa->descricao }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                                $corClasses = [
                                    'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'green' => 'bg-green-100 text-green-800 border-green-200',
                                    'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'red' => 'bg-red-100 text-red-800 border-red-200',
                                    'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
                                    'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                                ];
                                $btnCorClasses = [
                                    'blue' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm',
                                    'green' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm',
                                    'yellow' => 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm',
                                    'red' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
                                    'purple' => 'bg-purple-600 hover:bg-purple-700 text-white shadow-sm',
                                    'gray' => 'bg-gray-600 hover:bg-gray-700 text-white shadow-sm',
                                    'indigo' => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm',
                                    'pink' => 'bg-pink-500 hover:bg-pink-600 text-white shadow-sm',
                                    'orange' => 'bg-orange-500 hover:bg-orange-600 text-white shadow-sm',
                                ];
                                // Fallback para cores não mapeadas
                                $defaultCorClass = 'bg-gray-100 text-gray-800 border-gray-200';
                                $defaultBtnClass = 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $corClasses[$etapa->cor] ?? $defaultCorClass }}">
                                {{ ucfirst($etapa->cor) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm {{ $etapa->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $etapa->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Ordem</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $etapa->ordem }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Transições de Saída</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $etapa->transicoesOrigem->count() }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Transições de Entrada</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $etapa->transicoesDestino->count() }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Criado em</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $etapa->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="bg-indigo-50/50 dark:bg-slate-800/50 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/30 md:col-span-4 border-l-4 border-l-indigo-500">
                            <p class="text-xs text-indigo-500 dark:text-indigo-400 uppercase font-bold">Setor para Notificação</p>
                            @if($etapa->setor)
                                <div class="flex items-center gap-2 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-lg font-bold text-indigo-900 dark:text-indigo-100">{{ $etapa->setor->nome_localizacao }} ({{ $etapa->setor->nome_reduzido }})</p>
                                </div>
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 italic">Usuários deste setor serão notificados ao entrar nesta etapa.</p>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 mt-1 italic">Nenhum setor configurado para notificações.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transições de Saída -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                        Próximas Etapas Possíveis (Saída)
                    </h4>

                    @if($etapa->transicoesOrigem->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($etapa->transicoesOrigem as $transicao)
                                @php
                                    $corClasses = [
                                        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'green' => 'bg-green-100 text-green-800 border-green-200',
                                        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'red' => 'bg-red-100 text-red-800 border-red-200',
                                        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
                                        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    ];
                                    $btnCorClasses = [
                                        'blue' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm',
                                        'green' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm',
                                        'yellow' => 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm',
                                        'red' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
                                        'purple' => 'bg-purple-600 hover:bg-purple-700 text-white shadow-sm',
                                        'gray' => 'bg-gray-600 hover:bg-gray-700 text-white shadow-sm',
                                        'indigo' => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm',
                                        'pink' => 'bg-pink-500 hover:bg-pink-600 text-white shadow-sm',
                                        'orange' => 'bg-orange-500 hover:bg-orange-600 text-white shadow-sm',
                                    ];
                                    $defaultBtnClass = 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm';
                                @endphp
                                <span class="px-4 py-2 rounded-lg text-sm font-medium {{ $btnCorClasses[$transicao->cor_botao] ?? $defaultBtnClass }}">
                                    {{ $transicao->etapaDestino->icone ?? '' }}
                                    {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">Esta etapa não possui transições de saída configuradas.</p>
                    @endif
                </div>
            </div>

            <!-- Transições de Entrada -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Etapas Anteriores (De onde pode vir)
                    </h4>

                    @if($etapa->transicoesDestino->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($etapa->transicoesDestino as $transicao)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                    {{ $transicao->etapaOrigem->icone ?? '' }}
                                    {{ $transicao->etapaOrigem->nome }}
                                    →
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">Nenhuma etapa leva a esta (pode ser uma etapa inicial).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
