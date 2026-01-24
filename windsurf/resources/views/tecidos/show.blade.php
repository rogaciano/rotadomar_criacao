<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes do Tecido') }}
            </h2>
            <div>
                @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
                <a href="{{ route('tecidos.edit', $tecido->id) }}" class="btn-ghost-primary mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
                @endif
                @if($tecido->referencia && auth()->user() && auth()->user()->canUpdate('tecidos'))
                <a href="{{ route('tecidos.atualizar-estoque', $tecido->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Atualizar Estoque
                </a>
                @endif
                <a href="{{ route('tecidos.index') }}" class="btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Informações do Tecido -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações do Tecido</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tecido->descricao }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Referência</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tecido->referencia ?: 'Não informada' }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Cadastro</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tecido->data_cadastro ? $tecido->data_cadastro->format('d/m/Y') : 'Não informada' }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tecido->ativo ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                    {{ $tecido->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Necessidade Total</p>
                                <p class="mt-1 text-lg text-gray-900 dark:text-white font-semibold">{{ number_format($tecido->necessidade_total, 2, ',', '.') }} metros</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Baseado no consumo planejado de todos os produtos</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estoque Total</p>
                                <p class="mt-1 text-lg text-gray-900 dark:text-white font-semibold">{{ isset($tecido->quantidade_estoque) ? number_format($tecido->quantidade_estoque, 2, ',', '.') : 'Não disponível' }} metros</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Última atualização: {{ $tecido->ultima_consulta_estoque ? $tecido->ultima_consulta_estoque->format('d/m/Y H:i') : 'Não disponível' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Produtos Relacionados</h3>
                    <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                        @if($tecido->produtos && $tecido->produtos->count() > 0)
                            <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                                @foreach($tecido->produtos as $produto)
                                            <li class="py-2">
                                                @if(auth()->user() && auth()->user()->canRead('produtos'))
                                                    <a href="{{ route('produtos.show', $produto->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                        {{ $produto->referencia }} - {{ $produto->descricao }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $produto->referencia }} - {{ $produto->descricao }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum produto associado a este tecido.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($tecido->estoquesCores && $tecido->estoquesCores->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Estoque por Cor</h3>
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                                <thead class="bg-gray-100 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">Cor</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">Código</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">Estoque</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">Necessidade</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                    @foreach($tecido->estoquesCores as $estoqueCor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <div class="flex items-center">
                                                @if($estoqueCor->cor_rgb)
                                                    <div class="h-4 w-4 rounded-full mr-3" style="background-color: {{ $estoqueCor->cor_rgb }}"></div>
                                                @endif
                                                <a href="{{ route('tecidos.produtos-por-cor', ['tecidoId' => $tecido->id, 'corId' => $estoqueCor->id]) }}" class="text-blue-600 underline hover:text-blue-800">
                                                    {{ $estoqueCor->cor }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $estoqueCor->codigo_cor ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">{{ number_format($estoqueCor->quantidade, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($estoqueCor->necessidade, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                                            @php
                                                $saldo = $estoqueCor->saldo;
                                                $corSaldo = $saldo >= 0 ? 'text-green-600' : 'text-red-600';
                                            @endphp
                                            <span class="{{ $corSaldo }}">
                                                {{ number_format($saldo, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-slate-700">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Total</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">{{ number_format($tecido->total_estoque_por_cores, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            @php
                                                $totalNecessidade = $tecido->estoquesCores->sum('necessidade');
                                            @endphp
                                            {{ number_format($totalNecessidade, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                            @php
                                                $totalSaldo = $tecido->total_estoque_por_cores - $totalNecessidade;
                                                $corTotalSaldo = $totalSaldo >= 0 ? 'text-green-600' : 'text-red-600';
                                            @endphp
                                            <span class="{{ $corTotalSaldo }} font-medium">
                                                {{ number_format($totalSaldo, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>


            </div>
        </div>
    </div>
</x-app-layout>
