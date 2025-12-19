<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Etapa de Produção') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('etapas-producao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar
                </a>
                <a href="{{ route('etapas-producao.edit', $etapa) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Informações Básicas -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center">
                            @if($etapa->icone)
                                <span class="text-4xl mr-4">{{ $etapa->icone }}</span>
                            @endif
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $etapa->nome }}</h3>
                                @if($etapa->descricao)
                                    <p class="text-gray-600">{{ $etapa->descricao }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
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
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $corClasses[$etapa->cor] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($etapa->cor) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm {{ $etapa->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $etapa->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Ordem</p>
                            <p class="text-xl font-bold text-gray-900">{{ $etapa->ordem }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Transições de Saída</p>
                            <p class="text-xl font-bold text-gray-900">{{ $etapa->transicoesOrigem->count() }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Transições de Entrada</p>
                            <p class="text-xl font-bold text-gray-900">{{ $etapa->transicoesDestino->count() }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Criado em</p>
                            <p class="text-sm font-medium text-gray-900">{{ $etapa->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transições de Saída -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
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
                                    $btnCorClasses = [
                                        'blue' => 'bg-blue-500 hover:bg-blue-600 text-white',
                                        'green' => 'bg-green-500 hover:bg-green-600 text-white',
                                        'yellow' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
                                        'red' => 'bg-red-500 hover:bg-red-600 text-white',
                                        'purple' => 'bg-purple-500 hover:bg-purple-600 text-white',
                                        'gray' => 'bg-gray-500 hover:bg-gray-600 text-white',
                                        'indigo' => 'bg-indigo-500 hover:bg-indigo-600 text-white',
                                        'pink' => 'bg-pink-500 hover:bg-pink-600 text-white',
                                        'orange' => 'bg-orange-500 hover:bg-orange-600 text-white',
                                    ];
                                @endphp
                                <span class="px-4 py-2 rounded-lg text-sm font-medium {{ $btnCorClasses[$transicao->cor_botao] ?? 'bg-gray-500 text-white' }}">
                                    {{ $transicao->etapaDestino->icone ?? '' }}
                                    {{ $transicao->label_botao ?: $transicao->etapaDestino->nome }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Esta etapa não possui transições de saída configuradas.</p>
                    @endif
                </div>
            </div>

            <!-- Transições de Entrada -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                        <p class="text-gray-500 italic">Nenhuma etapa leva a esta (pode ser uma etapa inicial).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
