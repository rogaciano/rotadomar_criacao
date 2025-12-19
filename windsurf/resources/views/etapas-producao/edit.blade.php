<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Etapa de Produção') }}: {{ $etapa->nome }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('etapas-producao.update', $etapa) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome', $etapa->nome) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('nome') border-red-500 @enderror"
                                    placeholder="Ex: Recebimento, Produção, Acabamento">
                                @error('nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">Ordem *</label>
                                <input type="number" name="ordem" id="ordem" value="{{ old('ordem', $etapa->ordem) }}" required min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('ordem') border-red-500 @enderror">
                                @error('ordem')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $etapa->descricao) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Descrição opcional da etapa">
                            </div>

                            <!-- Cor -->
                            <div>
                                <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">Cor *</label>
                                <select name="cor" id="cor" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($cores as $valor => $nome)
                                        <option value="{{ $valor }}" {{ old('cor', $etapa->cor) === $valor ? 'selected' : '' }}>{{ $nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ícone -->
                            <div>
                                <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Ícone (emoji)</label>
                                <input type="text" name="icone" id="icone" value="{{ old('icone', $etapa->icone) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Ex: 📦 ⚙️ ✅ 🎨">
                            </div>

                            <!-- Ativo -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $etapa->ativo) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Etapa ativa</span>
                                </label>
                            </div>
                        </div>

                        <!-- Transições (Próximas etapas) -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Próximas Etapas (Transições)</h3>
                            <p class="text-sm text-gray-500 mb-4">Defina para quais etapas será possível avançar a partir desta.</p>

                            <div id="transicoes-container" class="space-y-3">
                                @foreach($etapa->transicoesOrigem as $i => $transicao)
                                    <div class="transicao-row flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <select name="transicoes[{{ $i }}][etapa_destino_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                <option value="">Selecione a próxima etapa</option>
                                                @foreach($etapas as $e)
                                                    <option value="{{ $e->id }}" {{ $transicao->etapa_destino_id == $e->id ? 'selected' : '' }}>{{ $e->icone }} {{ $e->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-40">
                                            <input type="text" name="transicoes[{{ $i }}][label_botao]" value="{{ $transicao->label_botao }}" placeholder="Texto do botão" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                        </div>
                                        <div class="w-32">
                                            <select name="transicoes[{{ $i }}][cor_botao]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                @foreach($cores as $valor => $nome)
                                                    <option value="{{ $valor }}" {{ $transicao->cor_botao === $valor ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="remove-transicao text-red-500 hover:text-red-700 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
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
                            <a href="{{ route('etapas-producao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Atualizar Etapa
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
            let index = {{ $etapa->transicoesOrigem->count() }};

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
