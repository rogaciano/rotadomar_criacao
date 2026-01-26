<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Botões de Ação --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                    @if(auth()->user()->canCreate('produtos'))
                    <a href="{{ route('produtos.create') }}" class="btn-ghost-primary w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Adicionar Produto
                    </a>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    @if(auth()->user()->canRead('produtos'))
                        <button id="btn-gerar-pdf-landscape" class="btn-ghost-rose w-full sm:w-auto justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">PDF Paisagem</span>
                            <span class="sm:hidden">PDF Paisagem</span>
                        </button>
                        <button id="btn-gerar-pdf-portrait" class="btn-ghost-warning w-full sm:w-auto justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">PDF Retrato</span>
                            <span class="sm:hidden">PDF Retrato</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Seção de Filtros --}}
            @include('produtos.partials._filters', [
                'filters' => $filters,
                'marcas' => $marcas,
                'tecidos' => $tecidos,
                'estilistas' => $estilistas,
                'grupos' => $grupos,
                'statuses' => $statuses,
                'direcionamentosComerciais' => $direcionamentosComerciais,
                'localizacoes' => $localizacoes,
                'situacoes' => $situacoes,
                'localizacoesPlanejamento' => $localizacoesPlanejamento,
                'filtersVisible' => $filtersVisible ?? true
            ])

            {{-- Mensagem de Sucesso --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4 mx-4 sm:mx-0 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Dica de Scroll Mobile --}}
            <div class="md:hidden px-4 mb-2">
                <p class="text-xs text-gray-500 dark:text-gray-400 italic text-center">
                    👉 Deslize horizontalmente para ver mais colunas
                </p>
            </div>

            {{-- Cards Mobile --}}
            @include('produtos.partials._mobile-cards', ['produtos' => $produtos])

            {{-- Tabela Desktop --}}
            @include('produtos.partials._desktop-table', ['produtos' => $produtos])

            {{-- Paginação --}}
            <div class="mt-4">
                {{ $produtos->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @include('produtos.partials._scripts', [
        'filters' => $filters ?? [],
        'filtersVisible' => $filtersVisible ?? true
    ])
</x-app-layout>
