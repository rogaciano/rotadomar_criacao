@php
    $linksProduto = array_values(array_filter(old('links_produto', $produto->links_produto ?? []), fn ($link) => filled($link)));
    $anexosCriacao = collect($produto->anexos ?? [])->where('contexto', \App\Models\ProdutoAnexo::CONTEXTO_CRIACAO);
@endphp

<div class="space-y-8">
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Identificação</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo Produto</label>
                <select name="grupo_id" id="grupo_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecione</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}" {{ (string) old('grupo_id', $produto->grupo_id) === (string) $grupo->id ? 'selected' : '' }}>{{ $grupo->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modelo</label>
                <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $produto->descricao) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="marca_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
                <select name="marca_id" id="marca_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecione</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ (string) old('marca_id', $produto->marca_id) === (string) $marca->id ? 'selected' : '' }}>{{ $marca->nome_marca }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="referencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referência</label>
                <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $produto->referencia) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if(!isset($produto->id)) readonly @endif required>
                @if(!isset($produto->id))
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">A referência ALFA é gerada automaticamente com 6 letras maiúsculas e sem repetição.</p>
                @endif
            </div>

            <div>
                <label for="prioridade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                <select name="prioridade" id="prioridade" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecione</option>
                    <option value="Baixa" {{ old('prioridade', $produto->prioridade) === 'Baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="Média" {{ old('prioridade', $produto->prioridade) === 'Média' ? 'selected' : '' }}>Média</option>
                    <option value="Alta" {{ old('prioridade', $produto->prioridade) === 'Alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>

            <div>
                <label for="data_cadastro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Cadastro</label>
                <input type="date" name="data_cadastro" id="data_cadastro" value="{{ old('data_cadastro', optional($produto->data_cadastro)->format('Y-m-d') ?? $produto->data_cadastro) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="lg:col-span-3" data-links-wrapper>
                <div class="flex flex-col lg:flex-row lg:items-end gap-3 mb-3">
                    <div class="flex-1">
                        <label for="novo_link_produto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Links do Produto</label>
                        <input type="url" id="novo_link_produto" placeholder="https://..." class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" data-new-link-input>
                    </div>
                    <div class="lg:pb-0.5">
                        <button type="button" class="btn-ghost-secondary w-full lg:w-auto" data-add-link onclick="window.addCriacaoLinkRow(event)">Adicionar link</button>
                    </div>
                </div>
                <div class="hidden" data-links-hidden-list>
                    @foreach($linksProduto as $link)
                        <input type="hidden" name="links_produto[]" value="{{ $link }}">
                    @endforeach
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2" data-links-chip-list>
                    @foreach($linksProduto as $link)
                        <div class="relative group min-w-0" data-link-chip>
                            <div class="flex items-center gap-1 rounded-full border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 px-2.5 py-1.5 w-full min-w-0 overflow-hidden">
                                <button type="button" class="min-w-0 flex-1 text-left" onclick="window.openCriacaoLinkModal(event, @js($link))">
                                    <span class="block text-xs text-slate-700 dark:text-slate-200 truncate min-w-0" title="{{ $link }}">{{ $link }}</span>
                                </button>
                                <button type="button" class="shrink-0 text-[10px] font-semibold text-slate-500 hover:text-red-500" data-remove-link onclick="window.removeCriacaoLinkRow(event)">×</button>
                            </div>
                            <div class="hidden lg:block pointer-events-none absolute left-0 top-full z-20 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-2">
                                    <img src="{{ $link }}" alt="Preview link do produto" class="h-48 w-48 max-w-none object-contain rounded-md bg-slate-100 dark:bg-slate-800" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="hidden text-xs text-gray-500 dark:text-gray-400 w-48">Preview indisponível para este link.</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produção</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade</label>
                <input type="number" min="0" name="quantidade" id="quantidade" value="{{ old('quantidade', $produto->quantidade ?? 0) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="media_mensal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Média Mensal</label>
                <input type="number" min="0" name="media_mensal" id="media_mensal" value="{{ old('media_mensal', $produto->media_mensal) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="variantes_cores" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Variante (Cores)</label>
                <input type="number" min="0" name="variantes_cores" id="variantes_cores" value="{{ old('variantes_cores', $produto->variantes_cores) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="estilista_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estilista</label>
                <select name="estilista_id" id="estilista_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecione</option>
                    @foreach($estilistas as $estilista)
                        <option value="{{ $estilista->id }}" {{ (string) old('estilista_id', $produto->estilista_id) === (string) $estilista->id ? 'selected' : '' }}>{{ $estilista->nome_estilista }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="data_entrada_processo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Entrada no Processo</label>
                <input type="date" name="data_entrada_processo" id="data_entrada_processo" value="{{ old('data_entrada_processo', optional($produto->data_entrada_processo)->format('Y-m-d') ?? $produto->data_entrada_processo) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="data_prevista_producao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Prevista de Produção</label>
                <input type="date" name="data_prevista_producao" id="data_prevista_producao" value="{{ old('data_prevista_producao', optional($produto->data_prevista_producao)->format('Y-m-d') ?? $produto->data_prevista_producao) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status & Meses</h3>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div>
                <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Criação</label>
                <select name="status_id" id="status_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecione</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ (string) old('status_id', $produto->status_id) === (string) $status->id ? 'selected' : '' }}>{{ $status->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="mes_criacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mês Criação</label>
                <input type="month" name="mes_criacao" id="mes_criacao" value="{{ old('mes_criacao', optional($produto->mes_criacao)->format('Y-m') ?? '') }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="mes_producao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mês Produção</label>
                <input type="month" name="mes_producao" id="mes_producao" value="{{ old('mes_producao', optional($produto->mes_producao)->format('Y-m') ?? '') }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="mes_lancamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mês Lançamento</label>
                <input type="month" name="mes_lancamento" id="mes_lancamento" value="{{ old('mes_lancamento', optional($produto->mes_lancamento)->format('Y-m') ?? '') }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Observações</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="direcionamento_comercial_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Direcionamento Comercial</label>
                <select name="direcionamento_comercial_id" id="direcionamento_comercial_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecione</option>
                    @foreach($direcionamentosComerciais as $direcionamento)
                        <option value="{{ $direcionamento->id }}" {{ (string) old('direcionamento_comercial_id', $produto->direcionamento_comercial_id) === (string) $direcionamento->id ? 'selected' : '' }}>{{ $direcionamento->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="observacoes_criacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações da Criação</label>
                <textarea name="observacoes_criacao" id="observacoes_criacao" rows="4" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_criacao', $produto->observacoes_criacao) }}</textarea>
            </div>

            <div>
                <label for="observacoes_adicionais" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações Adicionais</label>
                <textarea name="observacoes_adicionais" id="observacoes_adicionais" rows="4" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_adicionais', $produto->observacoes_adicionais) }}</textarea>
            </div>

            <div>
                <label for="obs_designer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Obs. do Designer (Somente Leitura)</label>
                <textarea name="obs_designer" id="obs_designer" rows="4" @if(isset($produto->id) && !auth()->user()->can('editObsDesigner', $produto)) readonly @endif class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('obs_designer', $produto->obs_designer) }}</textarea>
                @if(isset($produto->id) && !auth()->user()->can('editObsDesigner', $produto))
                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">Somente o estilista vinculado ou admin pode editar esta observação.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imagens da Criação</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="foto_principal_criacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagem Principal da Criação</label>
                @if($produto->foto_principal_criacao)
                    <div class="relative inline-block group mb-3">
                        <img src="{{ asset('storage/' . $produto->foto_principal_criacao) }}" alt="Imagem principal da criação" class="h-40 w-full object-cover rounded-md border border-gray-300 dark:border-slate-600">
                        <div class="hidden lg:block pointer-events-none absolute left-full top-0 z-20 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                            <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-2">
                                <img src="{{ asset('storage/' . $produto->foto_principal_criacao) }}" alt="Preview imagem principal da criação" class="h-64 w-64 max-w-none object-contain rounded-md bg-slate-100 dark:bg-slate-800">
                            </div>
                        </div>
                    </div>
                @endif
                <input type="file" name="foto_principal_criacao" id="foto_principal_criacao" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @if($produto->foto_principal_criacao)
                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                        <input type="checkbox" name="remover_foto_principal_criacao" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        Remover imagem principal da criação
                    </label>
                @endif
            </div>

            <div>
                <label for="imagens_criacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagens Adicionais da Criação</label>
                <input type="file" name="imagens_criacao[]" id="imagens_criacao" accept="image/*" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @if($anexosCriacao->isNotEmpty())
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($anexosCriacao as $anexo)
                            <label class="rounded-lg border border-gray-200 dark:border-slate-700 p-3 block relative group cursor-pointer">
                                <img src="{{ asset('storage/' . $anexo->arquivo_path) }}" alt="{{ $anexo->descricao }}" class="h-28 w-full object-cover rounded-md mb-2">
                                <div class="hidden lg:block pointer-events-none absolute left-full top-0 z-20 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                    <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-2">
                                        <img src="{{ asset('storage/' . $anexo->arquivo_path) }}" alt="Preview {{ $anexo->descricao }}" class="h-64 w-64 max-w-none object-contain rounded-md bg-slate-100 dark:bg-slate-800">
                                    </div>
                                </div>
                                <span class="block text-sm text-gray-700 dark:text-gray-300 truncate">{{ $anexo->descricao }}</span>
                                <span class="mt-2 inline-flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                                    <input type="checkbox" name="remover_anexos_criacao[]" value="{{ $anexo->id }}" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    Remover
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<template id="link-produto-template">
    <div class="relative group min-w-0" data-link-chip>
        <input type="hidden" name="links_produto[]" value="">
        <div class="flex items-center gap-1 rounded-full border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 px-2.5 py-1.5 w-full min-w-0 overflow-hidden">
            <button type="button" class="min-w-0 flex-1 text-left" data-open-link>
                <span class="block text-xs text-slate-700 dark:text-slate-200 truncate min-w-0" data-link-label></span>
            </button>
            <button type="button" class="shrink-0 text-[10px] font-semibold text-slate-500 hover:text-red-500" data-remove-link onclick="window.removeCriacaoLinkRow(event)">×</button>
        </div>
        <div class="hidden lg:block pointer-events-none absolute left-0 top-full z-20 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
            <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-2">
                <img src="" alt="Preview link do produto" data-link-preview class="h-48 w-48 max-w-none object-contain rounded-md bg-slate-100 dark:bg-slate-800" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="hidden text-xs text-gray-500 dark:text-gray-400 w-48">Preview indisponível para este link.</div>
            </div>
        </div>
    </div>
</template>

<div id="criacao-link-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70" onclick="window.closeCriacaoLinkModal()"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-4xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Preview do Link</h3>
                <button type="button" class="text-slate-500 hover:text-slate-900 dark:hover:text-white" onclick="window.closeCriacaoLinkModal()">✕</button>
            </div>
            <div class="p-4 space-y-4">
                <a href="#" target="_blank" rel="noopener noreferrer" id="criacao-link-modal-anchor" class="block break-all text-sm text-indigo-600 dark:text-indigo-400 hover:underline"></a>
                <div class="rounded-xl bg-slate-100 dark:bg-slate-800 min-h-[420px] flex items-center justify-center overflow-hidden">
                    <img id="criacao-link-modal-image" src="" alt="Preview do link" class="max-h-[70vh] w-auto max-w-full object-contain hidden">
                    <div id="criacao-link-modal-fallback" class="text-sm text-slate-500 dark:text-slate-400 hidden px-6 text-center">Não foi possível gerar preview desta URL. Você pode abrir o link em nova aba.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('criacao.index') }}" class="btn-ghost-secondary">Cancelar</a>
    <button type="submit" class="btn-ghost-primary">Salvar</button>
</div>

<script>
    (function () {
        window.refreshCriacaoLinkButtons = function () {
            const chipList = document.querySelector('[data-links-chip-list]');

            if (!chipList) {
                return;
            }

            const chips = chipList.querySelectorAll('[data-link-chip]');

            chips.forEach((chip, index) => {
                const removeButton = chip.querySelector('[data-remove-link]');

                if (removeButton) {
                    removeButton.disabled = chips.length === 1 && index === 0;
                }
            });
        };

        window.addCriacaoLinkRow = function (event) {
            event.preventDefault();

            const input = document.querySelector('[data-new-link-input]');
            const chipList = document.querySelector('[data-links-chip-list]');
            const template = document.getElementById('link-produto-template');

            if (!input || !chipList || !template) {
                return;
            }

            const value = input.value.trim();

            if (!value) {
                input.focus();
                return;
            }

            const existingValues = Array.from(document.querySelectorAll('[data-links-hidden-list] input[name="links_produto[]"], [data-links-chip-list] input[name="links_produto[]"]'))
                .map((element) => element.value);

            if (existingValues.includes(value)) {
                input.value = '';
                input.focus();
                return;
            }

            const node = template.content.firstElementChild.cloneNode(true);
            const hiddenInput = node.querySelector('input[name="links_produto[]"]');
            const label = node.querySelector('[data-link-label]');
            const preview = node.querySelector('[data-link-preview]');
            const openButton = node.querySelector('[data-open-link]');

            if (hiddenInput) {
                hiddenInput.value = value;
            }

            if (label) {
                label.textContent = value;
                label.title = value;
            }

            if (openButton) {
                openButton.setAttribute('onclick', `window.openCriacaoLinkModal(event, ${JSON.stringify(value)})`);
            }

            if (preview) {
                preview.src = value;
                preview.style.display = 'block';
                if (preview.nextElementSibling) {
                    preview.nextElementSibling.style.display = 'none';
                }
            }

            chipList.appendChild(node);
            input.value = '';
            input.focus();
            window.refreshCriacaoLinkButtons();
        };

        window.openCriacaoLinkModal = function (event, url) {
            if (event) {
                event.preventDefault();
            }

            const modal = document.getElementById('criacao-link-modal');
            const anchor = document.getElementById('criacao-link-modal-anchor');
            const image = document.getElementById('criacao-link-modal-image');
            const fallback = document.getElementById('criacao-link-modal-fallback');

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

        window.closeCriacaoLinkModal = function () {
            const modal = document.getElementById('criacao-link-modal');

            if (modal) {
                modal.classList.add('hidden');
            }
        };

        window.removeCriacaoLinkRow = function (event) {
            event.preventDefault();

            const button = event.target.closest('[data-remove-link]');
            const chipList = document.querySelector('[data-links-chip-list]');

            if (!button || !chipList) {
                return;
            }

            const chips = chipList.querySelectorAll('[data-link-chip]');

            if (chips.length === 1) {
                const hiddenInput = chips[0].querySelector('input[name="links_produto[]"]');
                const label = chips[0].querySelector('[data-link-label]');
                const preview = chips[0].querySelector('[data-link-preview]');

                if (hiddenInput) {
                    hiddenInput.remove();
                }

                if (label) {
                    label.textContent = '';
                    label.title = '';
                }

                if (preview) {
                    preview.removeAttribute('src');
                }

                chips[0].remove();
                window.refreshCriacaoLinkButtons();
                return;
             }

            const row = button.closest('[data-link-chip]');

            if (row) {
                row.remove();
            }

            window.refreshCriacaoLinkButtons();
        };

        const hiddenList = document.querySelector('[data-links-hidden-list]');
        const chipList = document.querySelector('[data-links-chip-list]');

        if (hiddenList && chipList && hiddenList.children.length > 0 && chipList.children.length === 0) {
            hiddenList.querySelectorAll('input[name="links_produto[]"]').forEach((element) => {
                const node = document.getElementById('link-produto-template').content.firstElementChild.cloneNode(true);
                const hiddenInput = node.querySelector('input[name="links_produto[]"]');
                const label = node.querySelector('[data-link-label]');
                const preview = node.querySelector('[data-link-preview]');
                const openButton = node.querySelector('[data-open-link]');

                if (hiddenInput) {
                    hiddenInput.value = element.value;
                }

                if (label) {
                    label.textContent = element.value;
                    label.title = element.value;
                }

                if (openButton) {
                    openButton.setAttribute('onclick', `window.openCriacaoLinkModal(event, ${JSON.stringify(element.value)})`);
                }

                if (preview) {
                    preview.src = element.value;
                }

                chipList.appendChild(node);
            });

            hiddenList.innerHTML = '';
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.refreshCriacaoLinkButtons);
        } else {
            window.refreshCriacaoLinkButtons();
        }
    })();
</script>
