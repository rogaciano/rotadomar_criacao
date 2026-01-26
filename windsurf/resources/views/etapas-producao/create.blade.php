<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Nova Etapa de Produção') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Exibição de Erros de Validação -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Existem erros no formulário:
                                    </h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('etapas-producao.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('nome') border-red-500 @enderror"
                                    placeholder="Ex: Recebimento, Produção, Acabamento">
                                @error('nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">Ordem *</label>
                                <input type="number" name="ordem" id="ordem" value="{{ old('ordem', 0) }}" required min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('ordem') border-red-500 @enderror">
                                @error('ordem')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ old('descricao') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Descrição opcional da etapa">
                            </div>

                            <!-- Cor -->
                            <div>
                                <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">Cor *</label>
                                <select name="cor" id="cor" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('cor') border-red-500 @enderror">
                                    @foreach($cores as $valor => $nome)
                                        <option value="{{ $valor }}" {{ old('cor', 'blue') === $valor ? 'selected' : '' }}>{{ $nome }}</option>
                                    @endforeach
                                </select>
                                @error('cor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ícone -->
                            <div>
                                <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Ícone (emoji)</label>
                                <input type="text" name="icone" id="icone" value="{{ old('icone') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('icone') border-red-500 @enderror"
                                    placeholder="Ex: 📦 ⚙️ ✅ 🎨">
                                @error('icone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Setor para Notificação -->
                            <div class="md:col-span-2 bg-indigo-50/30 dark:bg-slate-800/50 p-4 rounded-xl border border-indigo-100/50 dark:border-indigo-900/30 border-l-4 border-l-indigo-500">
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Setor para Notificação (Opcional)</label>
                                <select name="localizacao_id" id="localizacao_id"
                                    class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('localizacao_id') border-red-500 @enderror">
                                    <option value="">Nenhum setor (sem notificação)</option>
                                    @foreach($localizacoes as $loc)
                                        <option value="{{ $loc->id }}" {{ old('localizacao_id') == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->nome_localizacao }} ({{ $loc->nome_reduzido }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('localizacao_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Os usuários vinculados a este setor receberão uma notificação quando um produto entrar nesta etapa.</p>
                            </div>

                            <!-- Ativo -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Etapa ativa</span>
                                </label>
                            </div>

                            <!-- Obriga Data Entrega Facção -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="obriga_data_entrega_faccao" value="1" {{ old('obriga_data_entrega_faccao') ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Obriga preenchimento da Data de Entrega Prevista</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500 ml-6">Se marcado, será obrigatório preencher a "Entrega Prevista Facção" para avançar para esta etapa.</p>
                            </div>
                        </div>

                        <!-- Transições (Próximas etapas) -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Próximas Etapas (Transições)</h3>
                            <p class="text-sm text-gray-500 mb-4">Defina para quais etapas será possível avançar a partir desta.</p>

                            <div id="transicoes-container" class="space-y-3">
                                <!-- Transição template (será clonado via JS) -->
                            </div>

                            <button type="button" id="add-transicao" class="mt-3 inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Adicionar Transição
                            </button>
                        </div>

                        <!-- Botões -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('etapas-producao.index') }}" class="btn-ghost-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-ghost-primary">
                                Salvar Etapa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="transicao-template">
        <div class="transicao-row flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <div class="flex-1">
                <select name="transicoes[INDEX][etapa_destino_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="">Selecione a próxima etapa</option>
                    @foreach($etapas as $e)
                        <option value="{{ $e->id }}">{{ $e->icone }} {{ $e->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <input type="text" name="transicoes[INDEX][label_botao]" placeholder="Texto do botão"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
            </div>
            <div class="w-32">
                <select name="transicoes[INDEX][cor_botao]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @foreach($cores as $valor => $nome)
                        <option value="{{ $valor }}">{{ $nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="remove-transicao text-red-500 hover:text-red-700 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('transicoes-container');
            const template = document.getElementById('transicao-template');
            const addButton = document.getElementById('add-transicao');
            let index = 0;

            addButton.addEventListener('click', function() {
                const clone = template.content.cloneNode(true);
                const html = clone.querySelector('.transicao-row').outerHTML.replace(/INDEX/g, index);
                container.insertAdjacentHTML('beforeend', html);
                index++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-transicao')) {
                    e.target.closest('.transicao-row').remove();
                }
            });
        });
    </script>
</x-app-layout>
