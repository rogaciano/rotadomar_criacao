<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Histórico de Etapas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Botão voltar -->
            <div class="mb-4">
                <a href="{{ route('produtos.show', $produtoLocalizacao->produto_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar ao Produto
                </a>
            </div>

            <!-- Info da localização -->
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ $produtoLocalizacao->produto->referencia ?? 'Produto' }} - {{ $produtoLocalizacao->localizacao->nome_localizacao ?? 'Localização' }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        OP: <strong>
                            @if($produtoLocalizacao->ordem_producao)
                                <a href="{{ $produtoLocalizacao->ordem_producao_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $produtoLocalizacao->ordem_producao }}
                                </a>
                            @else
                                N/A
                            @endif
                        </strong> |
                        Quantidade: <strong>{{ number_format($produtoLocalizacao->quantidade, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

            <!-- Timeline do histórico -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Histórico de Mudanças de Etapa</h4>

                    @if($historico->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($historico as $item)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @php
                                                        $iconColor = match($item->acao) {
                                                            'avancar' => 'bg-green-500',
                                                            'voltar' => 'bg-yellow-500',
                                                            'definir_inicial' => 'bg-blue-500',
                                                            default => 'bg-gray-500'
                                                        };
                                                        $iconSymbol = match($item->acao) {
                                                            'avancar' => '→',
                                                            'voltar' => '←',
                                                            'definir_inicial' => '●',
                                                            default => '?'
                                                        };
                                                    @endphp
                                                    <span class="h-8 w-8 rounded-full {{ $iconColor }} flex items-center justify-center ring-8 ring-white text-white font-bold text-sm">
                                                        {{ $iconSymbol }}
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-800">
                                                            <span class="font-medium">{{ $item->descricao_acao }}</span>
                                                            @if($item->etapaAnterior)
                                                                de <span class="font-medium text-gray-600">{{ $item->etapaAnterior->icone }} {{ $item->etapaAnterior->nome }}</span>
                                                            @endif
                                                            @if($item->etapaNova)
                                                                para <span class="font-medium text-green-600">{{ $item->etapaNova->icone }} {{ $item->etapaNova->nome }}</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-0.5">
                                                            por <span class="font-medium">{{ $item->usuario->name ?? 'Usuário desconhecido' }}</span>
                                                        </p>
                                                        @if($item->observacao)
                                                            <p class="text-xs text-gray-500 mt-1 italic">
                                                                "{{ $item->observacao }}"
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400">
                                                        <time datetime="{{ $item->created_at->toISOString() }}">
                                                            {{ $item->created_at->format('d/m/Y') }}
                                                            <br>
                                                            {{ $item->created_at->format('H:i') }}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Nenhum histórico de etapas disponível.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
