<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Movimentações') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        @php
        // Função para calcular dias úteis entre duas datas (excluindo sábados e domingos)
        if (!function_exists('calcularDiasUteis')) {
            function calcularDiasUteis($dataInicio, $dataFim) {
                if (!$dataInicio) return null;

                if (!$dataFim) {
                    $dataFim = now();
                }

                $diasUteis = 0;
                $dataAtual = clone $dataInicio;

                while ($dataAtual <= $dataFim) {
                    // 6 = sábado, 0 = domingo
                    $diaDaSemana = $dataAtual->dayOfWeek;
                    if ($diaDaSemana != 0 && $diaDaSemana != 6) {
                        $diasUteis++;
                    }
                    $dataAtual->addDay();
                }

                return $diasUteis;
            }
        }
        @endphp

        <div class="w-[98%] mx-auto px-2">
            <!-- Botões de ação -->
            @include('movimentacoes.partials.header-actions')

            <!-- Filtros -->
            @include('movimentacoes.partials.filters')

            <!-- Mensagem de sucesso -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(session('warning') && session('pdf_count'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-medium">{{ session('warning') }}</p>
                    <div class="mt-2 flex items-center">
                        <p>Deseja continuar mesmo assim?</p>
                        <a href="{{ route('movimentacoes.lista.pdf', array_merge(request()->query(), ['confirmar_pdf' => 1])) }}" target="_blank"
                           class="ml-4 bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs">
                            Sim, gerar PDF com {{ session('pdf_count') }} registros
                        </a>
                    </div>
                </div>
            @endif

            <!-- Tabela de Movimentações -->
            <div class="table-container">
                <!-- Versão para desktop/tablet -->
                @include('movimentacoes.partials.table')

                <!-- Versão para dispositivos móveis (cards) -->
                @include('movimentacoes.partials.mobile-list')
            </div>

            <!-- Paginação -->
            <div class="mt-4">
                {{ $movimentacoes->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @include('movimentacoes.partials.image-modal')

    @include('movimentacoes.partials.scripts')
</x-app-layout>
