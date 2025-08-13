<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Produto') }}
            </h2>
            <a href="{{ route('produtos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Erros de validação -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                            <p class="font-bold">Ocorreram erros. Por favor, verifique:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Referência -->
                            <div>
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $produto->referencia) }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $produto->descricao) }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Data de Cadastro -->
                            <div>
                                <label for="data_cadastro" class="block text-sm font-medium text-gray-700 mb-1">Data de Cadastro</label>
                                <input type="date" name="data_cadastro" id="data_cadastro" value="{{ old('data_cadastro', $produto->data_cadastro ? $produto->data_cadastro->format('Y-m-d') : date('Y-m-d')) }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Marca -->
                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <select name="marca_id" id="marca_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione uma marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id', $produto->marca_id) == $marca->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $marca->nome_marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantidade -->
                            <div>
                                <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', $produto->quantidade) }}" min="0" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            </div>

                            <!-- Estilista -->
                            <div>
                                <label for="estilista_id" class="block text-sm font-medium text-gray-700 mb-1">Estilista</label>
                                <select name="estilista_id" id="estilista_id" class="estilista-select block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione um estilista</option>
                                    @foreach($estilistas as $estilista)
                                        <option value="{{ $estilista->id }}" {{ old('estilista_id', $produto->estilista_id) == $estilista->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $estilista->nome_estilista }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Grupo -->
                            <div>
                                <label for="grupo_id" class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                                <select name="grupo_id" id="grupo_id" class="grupo-select block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione um grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ old('grupo_id', $produto->grupo_id) == $grupo->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $grupo->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Preço Atacado -->
                            <div>
                                <label for="preco_atacado" class="block text-sm font-medium text-gray-700 mb-1">Preço Atacado (R$)</label>
                                <input type="number" name="preco_atacado" id="preco_atacado" value="{{ old('preco_atacado', $produto->preco_atacado) }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <!-- Preço Varejo -->
                            <div>
                                <label for="preco_varejo" class="block text-sm font-medium text-gray-700 mb-1">Preço Varejo (R$)</label>
                                <input type="number" name="preco_varejo" id="preco_varejo" value="{{ old('preco_varejo', $produto->preco_varejo) }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_id" id="status_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700 bg-white" required>
                                    <option value="">Selecione um status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id', $produto->status_id) == $status->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $status->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Anexos Flexíveis -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-md font-medium text-gray-700">Anexos</h3>
                                    <button type="button" onclick="document.getElementById('modal-anexo').classList.remove('hidden')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Adicionar Anexo
                                    </button>
                                </div>

                                @if($produto->anexos && $produto->anexos->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @foreach($produto->anexos as $anexo)
                                            <div class="bg-white p-3 rounded-md border border-gray-200 flex items-center justify-between">
                                                <div class="flex items-center">
                                                    @php
                                                        $icone = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
                                                        $corIcone = 'text-blue-500';

                                                        if (in_array($anexo->tipo_arquivo, ['jpg', 'jpeg', 'png'])) {
                                                            $icone = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
                                                            $corIcone = 'text-green-500';
                                                        }
                                                    @endphp

                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $corIcone }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icone }}" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700">{{ $anexo->descricao }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <a href="{{ asset('storage/' . $anexo->arquivo_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm mr-3">
                                                        Visualizar
                                                    </a>
                                                    <form action="{{ route('produtos.anexos.destroy', $anexo->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este anexo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-gray-500 italic mb-4">
                                        Nenhum anexo adicionado. Salve o produto e adicione anexos na tela de visualização.
                                    </div>
                                @endif

                                <p class="text-sm text-gray-500">Você pode adicionar múltiplos anexos com descrições clicando no botão "Adicionar Anexo".</p>
                            </div>
                        </div>

                        <!-- Tecidos -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tecidos</label>
                            <div class="border border-gray-300 rounded-md p-4">
                                <div id="tecidos-container">
                                    @forelse($produto->tecidos as $index => $produtoTecido)
                                        <div class="tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-grow">
                                                    <select name="tecidos[{{ $index }}][tecido_id]" class="tecido-select select2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700">
                                                        <option value="">Selecione um tecido</option>
                                                        @foreach($tecidos as $tecido)
                                                            <option value="{{ $tecido->id }}" {{ $produtoTecido->id == $tecido->id ? 'selected' : '' }} class="text-gray-700">
                                                                {{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-1/4">
                                                    <input type="number" name="tecidos[{{ $index }}][consumo]" value="{{ $produtoTecido->pivot->consumo }}" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                </div>
                                                <button type="button" class="remove-tecido text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-grow">
                                                    <select name="tecidos[0][tecido_id]" class="tecido-select select2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700">
                                                        <option value="">Selecione um tecido</option>
                                                        @foreach($tecidos as $tecido)
                                                            <option value="{{ $tecido->id }}" class="text-gray-700">
                                                                {{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-1/4">
                                                    <input type="number" name="tecidos[0][consumo]" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                </div>
                                                <button type="button" class="remove-tecido text-red-500 hover:text-red-700" style="display: none;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" id="add-tecido" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Adicionar Tecido
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Adicione um ou mais tecidos utilizados neste produto</p>
                        </div>

                        <!-- Seção de Variações de Cores -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Variações de Cores</label>
                            <div class="border border-gray-300 rounded-md p-4">
                                <div id="cores-container">
                                    @forelse($produto->cores as $index => $produtoCor)
                                        <div class="cor-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-grow">
                                                    <input type="text" name="cores[{{ $index }}][cor]" value="{{ $produtoCor->cor }}" placeholder="Nome da cor" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                                                </div>
                                                <div class="w-1/4">
                                                    <input type="text" name="cores[{{ $index }}][codigo_cor]" value="{{ $produtoCor->codigo_cor }}" placeholder="Código" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                                                </div>
                                                <div class="w-1/4">
                                                    <input type="number" name="cores[{{ $index }}][quantidade]" value="{{ $produtoCor->quantidade }}" placeholder="Quantidade" min="1" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                </div>
                                                <button type="button" class="remove-cor text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- As cores serão adicionadas dinamicamente via JavaScript -->
                                    @endforelse
                                </div>
                                <button type="button" id="add-cor" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Adicionar Cor
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">As cores disponíveis serão carregadas automaticamente com base nos tecidos selecionados</p>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar anexo -->
    <div id="modal-anexo" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-anexo').classList.add('hidden')"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('produtos.anexos.store', $produto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Adicionar Anexo</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                        <input type="text" name="descricao" id="descricao" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    </div>
                                    <div>
                                        <label for="arquivo" class="block text-sm font-medium text-gray-700 mb-1">Arquivo</label>
                                        <input type="file" name="arquivo" id="arquivo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                        <p class="mt-1 text-sm text-gray-500">Formatos aceitos: PNG, JPG, JPEG (máx. 10MB) e PDF (máx. 1MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Salvar</button>
                        <button type="button" onclick="document.getElementById('modal-anexo').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Aguardar que o DOM e jQuery estejam totalmente carregados
        $(function() {
            const container = document.getElementById('tecidos-container');
            const addButton = document.getElementById('add-tecido');
            let tecidoCount = {{ count($produto->tecidos) > 0 ? count($produto->tecidos) : 0 }};

            // Show/hide remove buttons based on number of tecido items
            function updateRemoveButtons() {
                const items = container.querySelectorAll('.tecido-item');
                items.forEach(item => {
                    const removeButton = item.querySelector('.remove-tecido');
                    if (items.length > 1) {
                        removeButton.style.display = 'block';
                    } else {
                        removeButton.style.display = 'none';
                    }
                });
            }

            // Get all currently selected tecido IDs
            function getSelectedTecidoIds() {
                const selects = container.querySelectorAll('select[name^="tecidos"]');
                return Array.from(selects).map(select => select.value).filter(value => value !== '');
            }

            // Update all selects to properly show available options
            function updateSelectOptions() {
                const selectedIds = getSelectedTecidoIds();
                const selects = container.querySelectorAll('select[name^="tecidos"]');

                // Get all available options from the first select (which has all options)
                const firstSelect = container.querySelector('select');
                const allOptions = Array.from(firstSelect.options);

                selects.forEach(select => {
                    const currentValue = select.value;

                    // Clear all options except the first one (placeholder)
                    while (select.options.length > 1) {
                        select.remove(1);
                    }

                    // Add back all options, ensuring the current selection remains available
                    allOptions.forEach(option => {
                        // Include option if:
                        // 1. It's the empty placeholder, or
                        // 2. It's the currently selected value for this dropdown, or
                        // 3. It's not selected in any other dropdown
                        if (option.value === '' ||
                            option.value === currentValue ||
                            !selectedIds.includes(option.value) ||
                            (selectedIds.filter(id => id === option.value).length < 2 && option.value === currentValue)) {

                            const newOption = document.createElement('option');
                            newOption.value = option.value;
                            newOption.text = option.text;
                            newOption.className = 'text-gray-700';
                            if (option.value === currentValue) {
                                newOption.selected = true;
                            }
                            select.add(newOption);
                        }
                    });
                });
            }

            // Add new tecido item
            $(addButton).on('click', function() {
                tecidoCount++;
                const newItem = document.createElement('div');
                newItem.className = 'tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200';

                // Get all available options excluding already selected ones
                const selectedIds = getSelectedTecidoIds();
                const firstSelect = container.querySelector('select');
                const filteredOptions = Array.from(firstSelect.options)
                    .filter(opt => opt.value === '' || !selectedIds.includes(opt.value))
                    .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
                    .join('');

                newItem.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="flex-grow">
                            <select name="tecidos[${tecidoCount}][tecido_id]" class="tecido-select block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700">
                                ${filteredOptions}
                            </select>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="tecidos[${tecidoCount}][consumo]" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        <button type="button" class="remove-tecido text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                `;

                container.appendChild(newItem);
                updateRemoveButtons();

                // Não precisamos adicionar event listener aqui, pois estamos usando delegação de eventos
                const newSelect = newItem.querySelector('select');

                // Não precisamos adicionar event listener aqui, pois estamos usando delegação de eventos
            });

            // Add event listeners to existing remove buttons using event delegation
            $(container).on('click', '.remove-tecido', function() {
                $(this).closest('.tecido-item').remove();
                updateRemoveButtons();
                updateSelectOptions();
            });

            // Add change event listeners to existing selects using event delegation
            $(container).on('change', 'select[name^="tecidos"]', function() {
                updateSelectOptions();
                // Trigger Select2 to update
                $(this).trigger('change.select2');
            });

            // Verificar campos de consumo vazios antes de enviar o formulário
            document.querySelector('form').addEventListener('submit', function(e) {
                // Encontrar todos os campos de consumo
                const consumoInputs = document.querySelectorAll('input[name^="tecidos"][name$="[consumo]"]');

                // Definir como zero se estiver vazio
                consumoInputs.forEach(input => {
                    if (input.value === '' || input.value === null || input.value.trim() === '') {
                        input.value = '0';
                    }
                });

                // Verificar novamente para garantir que nenhum campo ficou vazio
                let todosPreenchidos = true;
                consumoInputs.forEach(input => {
                    if (input.value === '' || input.value === null || input.value.trim() === '') {
                        todosPreenchidos = false;
                        input.value = '0'; // Tentar definir novamente
                    }
                });

                if (!todosPreenchidos) {
                    console.log('Alguns campos de consumo foram automaticamente definidos como zero');
                }
            });

            // Inicializar Select2 nos campos de grupo e estilista
            $(document).ready(function() {
                $('.grupo-select').select2({
                    placeholder: "Selecione um grupo",
                    allowClear: true,
                    width: '100%'
                });

                $('.estilista-select').select2({
                    placeholder: "Selecione um estilista",
                    allowClear: true,
                    width: '100%'
                });
            });

            // Initialize Select2 on all tecido selects
            // Função para inicializar Select2 de forma consistente
            function initializeSelect2(selector) {
                $(selector).select2({
                    placeholder: "Selecione um tecido",
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "Nenhum tecido encontrado";
                        }
                    },
                    // Configuração para busca mais flexível
                    minimumInputLength: 0,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    // Forçar fechamento do dropdown após seleção
                    closeOnSelect: true
                }).on('select2:select', function (e) {
                    // Debug: log quando um item é selecionado
                    console.log('Tecido selecionado:', e.params.data);
                    
                    // Forçar o valor no select
                    $(this).val(e.params.data.id).trigger('change');
                });

                // Ajustar estilo do Select2 para combinar com o Tailwind
                $('.select2-container--default .select2-selection--single').css({
                    'height': '42px',
                    'padding': '6px 4px',
                    'border-color': '#d1d5db'
                });

                // Garantir que o container do Select2 respeite o layout flex
                $('.select2-container').css({
                    'width': '100%',
                    'max-width': '100%',
                    'flex': '1'
                });
            }

            $(document).ready(function() {
                initializeSelect2('.tecido-select');

                // Re-initialize Select2 after adding a new tecido
                $('#add-tecido').on('click', function() {
                    setTimeout(function() {
                        $('select[name^="tecidos"]').last().select2({
                            placeholder: 'Selecione um tecido',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Nenhum resultado encontrado";
                                },
                                searching: function() {
                                    return "Buscando...";
                                }
                            }
                        });
                    }, 100);
                });
            });

            // Initialize
            updateRemoveButtons();
            updateSelectOptions();

            // === SEÇÃO DE VARIAÇÕES DE CORES ===
            const coresContainer = document.getElementById('cores-container');
            const addCorButton = document.getElementById('add-cor');
            let corCount = {{ $produto->cores->count() }};
            let availableColors = [];

            // Função para obter cores disponíveis dos tecidos selecionados
            function getAvailableColors() {
                const selectedTecidoIds = getSelectedTecidoIds();
                
                if (selectedTecidoIds.length === 0) {
                    availableColors = [];
                    updateCoresContainer();
                    return;
                }

                // Fazer requisição AJAX para obter as cores dos tecidos selecionados
                $.ajax({
                    url: '{{ route("produtos.get-available-colors") }}',
                    method: 'POST',
                    data: {
                        tecido_ids: selectedTecidoIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        availableColors = response.colors || [];
                        updateCoresContainer();
                    },
                    error: function() {
                        console.error('Erro ao carregar cores disponíveis');
                        availableColors = [];
                        updateCoresContainer();
                    }
                });
            }

            // Atualizar container de cores com base nas cores disponíveis
            function updateCoresContainer() {
                // Se não há cores disponíveis e não há cores existentes, mostrar mensagem
                if (availableColors.length === 0 && coresContainer.querySelectorAll('.cor-item').length === 0) {
                    coresContainer.innerHTML = '<p class="text-gray-500 text-sm">Selecione tecidos para ver as cores disponíveis</p>';
                    return;
                }

                // Coletar cores e quantidades existentes
                const existingColors = {};
                const existingItems = coresContainer.querySelectorAll('.cor-item');
                existingItems.forEach(item => {
                    const corInput = item.querySelector('input[name*="[cor]"]');
                    const quantidadeInput = item.querySelector('input[name*="[quantidade]"]');
                    if (corInput && corInput.value) {
                        existingColors[corInput.value] = {
                            codigo_cor: item.querySelector('input[name*="[codigo_cor]"]')?.value || '',
                            quantidade: quantidadeInput?.value || ''
                        };
                    }
                });

                // Se há cores disponíveis, atualizar inteligentemente
                if (availableColors.length > 0) {
                    // Limpar container completamente para recriar
                    coresContainer.innerHTML = '';

                    // Adicionar cores disponíveis dos tecidos, preservando quantidades existentes
                    availableColors.forEach((color, index) => {
                        // Verificar se esta cor já existe
                        const existingColor = existingColors[color.cor];
                        if (existingColor) {
                            // Cor já existe, manter quantidade existente
                            addCorItem(color.cor, color.codigo_cor, index, existingColor.quantidade);
                            delete existingColors[color.cor]; // Marcar como processada
                        } else {
                            // Nova cor dos tecidos
                            addCorItem(color.cor, color.codigo_cor, index);
                        }
                    });

                    // Adicionar cores existentes que não estão mais nos tecidos (para não perder dados)
                    Object.keys(existingColors).forEach(corNome => {
                        const existingColor = existingColors[corNome];
                        corCount++;
                        addCorItem(corNome, existingColor.codigo_cor, corCount, existingColor.quantidade);
                    });
                }
            }

            // Adicionar item de cor
            function addCorItem(corNome = '', codigoCor = '', index = null, quantidade = '') {
                if (index === null) {
                    corCount++;
                    index = corCount;
                }

                const newItem = document.createElement('div');
                newItem.className = 'cor-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t first:border-t-0 border-gray-200';

                newItem.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="flex-grow">
                            <input type="text" name="cores[${index}][cor]" value="${corNome}" placeholder="Nome da cor" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                        </div>
                        <div class="w-1/4">
                            <input type="text" name="cores[${index}][codigo_cor]" value="${codigoCor}" placeholder="Código" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="cores[${index}][quantidade]" value="${quantidade}" placeholder="Quantidade" min="1" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        <button type="button" class="remove-cor text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                `;

                coresContainer.appendChild(newItem);
                updateCoresRemoveButtons();
            }

            // Atualizar botões de remoção de cores
            function updateCoresRemoveButtons() {
                const items = coresContainer.querySelectorAll('.cor-item');
                items.forEach(item => {
                    const removeButton = item.querySelector('.remove-cor');
                    if (items.length > 1) {
                        removeButton.style.display = 'block';
                    } else {
                        removeButton.style.display = 'none';
                    }
                });
            }

            // Event listeners para cores
            $(addCorButton).on('click', function() {
                addCorItem();
            });

            $(coresContainer).on('click', '.remove-cor', function() {
                $(this).closest('.cor-item').remove();
                updateCoresRemoveButtons();
            });

            // Monitorar mudanças nos tecidos para atualizar cores disponíveis
            $(container).on('change', 'select[name^="tecidos"]', function() {
                updateSelectOptions();
                getAvailableColors();
            });

            // Inicializar cores quando a página carregar
            $(document).ready(function() {
                updateCoresRemoveButtons();
                getAvailableColors();
            });
        });
    </script>
    @endpush
</x-app-layout>
