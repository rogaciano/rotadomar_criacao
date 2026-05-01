<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Detalhes da Criação</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $produto->referencia }} - {{ $produto->descricao }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->canAction('update', 'criacao'))
                    <a href="{{ route('criacao.edit', $produto) }}" class="btn-ghost-warning">Editar</a>
                @endif
                <a href="{{ route('criacao.index') }}" class="btn-ghost-secondary">Voltar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4">{{ session('success') }}</div>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 space-y-8 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Grupo Produto</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->grupoProduto?->descricao ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Modelo</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->descricao ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Marca</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->marca?->nome_marca ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Referência</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->referencia ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Prioridade</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->prioridade ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Data de Cadastro</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->data_cadastro?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Links do Produto</h3>
                        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2">
                            @forelse(($produto->links_produto ?? []) as $link)
                                <div class="relative group min-w-0">
                                    <div class="flex items-center gap-1 rounded-full border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 px-2.5 py-1.5 w-full min-w-0 overflow-hidden">
                                        <button type="button" class="min-w-0 flex-1 text-left" onclick="window.openCriacaoShowLinkModal(event, @js($link))">
                                            <span class="block text-xs text-slate-700 dark:text-slate-200 truncate min-w-0" title="{{ $link }}">{{ $link }}</span>
                                        </button>
                                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="shrink-0 text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">↗</a>
                                    </div>
                                    <div class="hidden lg:block pointer-events-none absolute left-0 top-full z-20 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                        <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-2">
                                            <img src="{{ $link }}" alt="Preview link do produto" class="h-48 w-48 max-w-none object-contain rounded-md bg-slate-100 dark:bg-slate-800" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="hidden text-xs text-gray-500 dark:text-gray-400 w-48">Preview indisponível para este link.</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum link informado.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Quantidade</p>
                            <p class="mt-1 text-base font-medium">{{ number_format((int) ($produto->quantidade ?? 0), 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Média Mensal</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->media_mensal !== null ? number_format((int) $produto->media_mensal, 0, ',', '.') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Variante (Cores)</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->variantes_cores !== null ? number_format((int) $produto->variantes_cores, 0, ',', '.') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Estilista</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->estilista?->nome_estilista ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Direcionamento Comercial</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->direcionamentoComercial?->descricao ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Status Criação</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->status?->descricao ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Entrada no Processo</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->data_entrada_processo?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Prevista de Produção</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->data_prevista_producao?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Mês Criação</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->mes_criacao?->format('m/Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Mês Produção</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->mes_producao?->format('m/Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Mês Lançamento</p>
                            <p class="mt-1 text-base font-medium">{{ $produto->mes_lancamento?->format('m/Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold mb-3">Observações da Criação</h3>
                            <div class="rounded-xl bg-slate-100 dark:bg-slate-900/70 p-4 min-h-28 whitespace-pre-line">{{ $produto->observacoes_criacao ?: '-' }}</div>
                        </div>
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold mb-3">Observações Adicionais</h3>
                            <div class="rounded-xl bg-slate-100 dark:bg-slate-900/70 p-4 min-h-28 whitespace-pre-line">{{ $produto->observacoes_adicionais ?: '-' }}</div>
                        </div>
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold mb-3">Obs. do Designer</h3>
                            <div class="rounded-xl bg-slate-100 dark:bg-slate-900/70 p-4 min-h-28 whitespace-pre-line">{{ $produto->obs_designer ?: '-' }}</div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Imagens da Criação</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Imagem Principal</p>
                                @if($produto->foto_principal_criacao)
                                    <a href="{{ asset('storage/' . $produto->foto_principal_criacao) }}" target="_blank" rel="noopener noreferrer" class="block">
                                        <img src="{{ asset('storage/' . $produto->foto_principal_criacao) }}" alt="Imagem principal da criação" class="h-56 w-full object-cover rounded-xl border border-gray-300 dark:border-slate-700">
                                    </a>
                                @else
                                    <div class="rounded-xl bg-slate-100 dark:bg-slate-900/70 p-4 min-h-56 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">Nenhuma imagem principal.</div>
                                @endif
                            </div>
                            <div class="lg:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Imagens Adicionais</p>
                                @if($anexosCriacao->isNotEmpty())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                        @foreach($anexosCriacao as $anexo)
                                            <a href="{{ asset('storage/' . $anexo->arquivo_path) }}" target="_blank" rel="noopener noreferrer" class="block rounded-xl border border-gray-200 dark:border-slate-700 p-3 hover:border-indigo-400 transition">
                                                <img src="{{ asset('storage/' . $anexo->arquivo_path) }}" alt="{{ $anexo->descricao }}" class="h-36 w-full object-cover rounded-lg mb-2">
                                                <span class="block text-sm text-gray-700 dark:text-gray-300 truncate">{{ $anexo->descricao }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-xl bg-slate-100 dark:bg-slate-900/70 p-4 text-sm text-gray-500 dark:text-gray-400">Nenhuma imagem adicional cadastrada.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="criacao-show-link-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70" onclick="window.closeCriacaoShowLinkModal()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Preview do Link</h3>
                    <button type="button" class="text-slate-500 hover:text-slate-900 dark:hover:text-white" onclick="window.closeCriacaoShowLinkModal()">✕</button>
                </div>
                <div class="p-4 space-y-4">
                    <a href="#" target="_blank" rel="noopener noreferrer" id="criacao-show-link-modal-anchor" class="block break-all text-sm text-indigo-600 dark:text-indigo-400 hover:underline"></a>
                    <div class="rounded-xl bg-slate-100 dark:bg-slate-800 min-h-[420px] flex items-center justify-center overflow-hidden">
                        <img id="criacao-show-link-modal-image" src="" alt="Preview do link" class="max-h-[70vh] w-auto max-w-full object-contain hidden">
                        <div id="criacao-show-link-modal-fallback" class="text-sm text-slate-500 dark:text-slate-400 hidden px-6 text-center">Não foi possível gerar preview desta URL. Você pode abrir o link em nova aba.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            window.openCriacaoShowLinkModal = function (event, url) {
                if (event) {
                    event.preventDefault();
                }

                const modal = document.getElementById('criacao-show-link-modal');
                const anchor = document.getElementById('criacao-show-link-modal-anchor');
                const image = document.getElementById('criacao-show-link-modal-image');
                const fallback = document.getElementById('criacao-show-link-modal-fallback');

                if (!modal || !anchor || !image || !fallback) {
                    return;
                }

                anchor.href = url;
                anchor.textContent = url;
                image.src = url;
                image.classList.remove('hidden');
                fallback.classList.add('hidden');
                modal.classList.remove('hidden');

                image.onerror = function () {
                    image.classList.add('hidden');
                    fallback.classList.remove('hidden');
                };

                image.onload = function () {
                    image.classList.remove('hidden');
                    fallback.classList.add('hidden');
                };
            };

            window.closeCriacaoShowLinkModal = function () {
                const modal = document.getElementById('criacao-show-link-modal');

                if (modal) {
                    modal.classList.add('hidden');
                }
            };
        })();
    </script>
</x-app-layout>
