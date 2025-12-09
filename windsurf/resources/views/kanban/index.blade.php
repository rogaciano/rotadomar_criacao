<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kanban - Produtos por Localização') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('kanban.index') }}" class="flex flex-wrap items-end gap-4">
                    <!-- Filtro de Mês -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="mes" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                        <select name="mes" id="mes" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($meses as $numero => $nome)
                                <option value="{{ $numero }}" {{ $mes == $numero ? 'selected' : '' }}>
                                    {{ $nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Ano -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                        <select name="ano" id="ano" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($anos as $anoOpcao)
                                <option value="{{ $anoOpcao }}" {{ $ano == $anoOpcao ? 'selected' : '' }}>
                                    {{ $anoOpcao }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botão Atualizar -->
                    <div>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            ATUALIZAR
                        </button>
                    </div>
                </form>

                <!-- Indicador de Período -->
                <div class="mt-3 flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Exibindo produtos com previsão de facção em <strong>{{ $meses[$mes] }}/{{ $ano }}</strong></span>
                </div>
            </div>
            <!-- Kanban Board com Botões de Navegação -->
            <div class="relative" style="min-height: 500px;">
                <!-- Botão Esquerda -->
                <button id="scroll-left" class="absolute left-2 top-[230px] z-30 bg-white hover:bg-gray-100 shadow-md rounded-full p-2 transition-all duration-200 opacity-90 hover:opacity-100 border border-gray-300">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Botão Direita -->
                <button id="scroll-right" class="absolute right-2 top-[230px] z-30 bg-white hover:bg-gray-100 shadow-md rounded-full p-2 transition-all duration-200 opacity-90 hover:opacity-100 border border-gray-300">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Barra de Rolagem Superior -->
                <div id="kanban-scroll-top" class="kanban-scroll-top mb-2">
                    <div id="kanban-scroll-top-content"></div>
                </div>

                <!-- Container do Kanban -->
                <div id="kanban-container" class="flex gap-4 pb-4 kanban-scroll">
                    @forelse($produtosPorLocalizacao as $dados)
                    <!-- Coluna da Localização -->
                    <div class="flex-shrink-0 w-80 bg-gray-100 rounded-lg overflow-hidden">
                        <!-- Header da Coluna (Fixo) -->
                        <div class="sticky top-0 z-20 bg-gray-100 p-4 pb-3 border-b border-gray-300">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-bold text-lg text-gray-800">
                                    {{ $dados['localizacao']->nome_reduzido ?? $dados['localizacao']->nome_localizacao }}
                                </h3>
                                <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ $dados['total'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">{{ $dados['localizacao']->nome_localizacao }}</p>
                        </div>

                        <!-- Cards dos Produtos -->
                        <div class="space-y-3 max-h-[calc(100vh-250px)] overflow-y-auto p-4 pt-3 pr-2">
                            @forelse($dados['produtos'] as $produto)
                                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 border-l-4 border-{{ $produto->marca->cor_fundo ?? 'gray' }}-500">
                                    <!-- Referência -->
                                    <div class="flex items-start justify-between mb-2">
                                        <a href="{{ route('produtos.show', $produto->id) }}"
                                           class="text-blue-600 hover:text-blue-800 font-bold text-lg"
                                           target="_blank">
                                            {{ $produto->referencia }}
                                        </a>
                                    </div>

                                    <!-- Descrição -->
                                    <p class="text-sm text-gray-700 mb-1 line-clamp-2">
                                        {{ $produto->descricao }}
                                    </p>

                                    <!-- Direcionamento Comercial -->
                                    @if($produto->direcionamentoComercial)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500 text-white">
                                                {{ $produto->direcionamentoComercial->descricao }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Marca -->
                                    @if($produto->marca)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: {{ $produto->marca->cor_fundo ?? '#f3f4f6' }};
                                                         color: {{ $produto->marca->cor_fonte ?? '#1f2937' }};">
                                                {{ $produto->marca->nome_marca }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Quantidade e Data Prevista -->
                                    <div class="mt-2 pt-2 border-t border-gray-200 space-y-1">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="flex items-center text-gray-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                                Quantidade:
                                            </span>
                                            <span class="font-semibold text-gray-900">{{ $produto->quantidade_alocada ?? 0 }}</span>
                                        </div>
                                        @if($produto->data_prevista)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="flex items-center text-gray-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Previsão:
                                            </span>
                                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($produto->data_prevista)->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 text-sm">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    Nenhum produto
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-12">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Nenhuma localização encontrada</h3>
                            <p class="text-sm text-gray-500">Não há localizações ativas com movimentações.</p>
                        </div>
                    </div>
                @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('kanban-container');
            const scrollLeftBtn = document.getElementById('scroll-left');
            const scrollRightBtn = document.getElementById('scroll-right');

            // Quantidade de pixels para rolar (largura de uma coluna + gap)
            const scrollAmount = 336; // 320px (coluna) + 16px (gap)

            // Função para rolar para a esquerda
            scrollLeftBtn.addEventListener('click', function() {
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Função para rolar para a direita
            scrollRightBtn.addEventListener('click', function() {
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Função para mostrar/ocultar botões baseado na posição do scroll
            function updateButtonVisibility() {
                const isAtStart = container.scrollLeft <= 0;
                const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 1;

                scrollLeftBtn.style.display = isAtStart ? 'none' : 'block';
                scrollRightBtn.style.display = isAtEnd ? 'none' : 'block';
            }

            // Atualizar visibilidade dos botões ao carregar e ao rolar
            updateButtonVisibility();
            container.addEventListener('scroll', updateButtonVisibility);

            // Atualizar ao redimensionar a janela
            window.addEventListener('resize', updateButtonVisibility);

            // Sincronizar barra de rolagem superior com a inferior
            const scrollTop = document.getElementById('kanban-scroll-top');
            const scrollTopContent = document.getElementById('kanban-scroll-top-content');

            // Definir largura do conteúdo da barra superior igual ao conteúdo do kanban
            function updateTopScrollWidth() {
                scrollTopContent.style.width = container.scrollWidth + 'px';
            }

            updateTopScrollWidth();
            window.addEventListener('resize', updateTopScrollWidth);

            // Observar mudanças no tamanho do container
            const resizeObserver = new ResizeObserver(() => {
                updateTopScrollWidth();
            });
            resizeObserver.observe(container);

            // Sincronizar scroll entre as duas barras (evitar loop)
            let isScrolling = false;

            scrollTop.addEventListener('scroll', function() {
                if (!isScrolling) {
                    isScrolling = true;
                    container.scrollLeft = this.scrollLeft;
                    requestAnimationFrame(() => { isScrolling = false; });
                }
            });

            container.addEventListener('scroll', function() {
                if (!isScrolling) {
                    isScrolling = true;
                    scrollTop.scrollLeft = this.scrollLeft;
                    requestAnimationFrame(() => { isScrolling = false; });
                }
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Scrollbar personalizada para as colunas (vertical) */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Scrollbar personalizada para o Kanban (horizontal) */
        .kanban-scroll {
            overflow-x: auto !important;
            overflow-y: visible !important;
            scroll-behavior: smooth;
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: #94a3b8 #f1f5f9; /* Firefox */
        }

        .kanban-scroll::-webkit-scrollbar {
            height: 14px;
            -webkit-appearance: none;
        }

        .kanban-scroll::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 8px;
        }

        .kanban-scroll::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 8px;
            border: 3px solid #e5e7eb;
        }

        .kanban-scroll::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Botões de navegação sempre visíveis */
        #scroll-left, #scroll-right {
            pointer-events: auto;
        }

        /* Line clamp para descrição */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Sombra no header fixo das colunas */
        .sticky {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Barra de rolagem superior */
        .kanban-scroll-top {
            overflow-x: auto;
            overflow-y: hidden;
            height: 32px;
            padding-top: 12px;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 #f1f5f9;
        }

        .kanban-scroll-top::-webkit-scrollbar {
            height: 14px;
            -webkit-appearance: none;
        }

        .kanban-scroll-top::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 8px;
        }

        .kanban-scroll-top::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 8px;
            border: 3px solid #e5e7eb;
        }

        .kanban-scroll-top::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        #kanban-scroll-top-content {
            height: 1px;
        }
    </style>
    @endpush
</x-app-layout>
