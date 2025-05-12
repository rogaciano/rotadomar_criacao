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
                                <select name="marca_id" id="marca_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id', $produto->marca_id) == $marca->id ? 'selected' : '' }}>
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

                            <!-- Tecido -->
                            <div>
                                <label for="tecido_id" class="block text-sm font-medium text-gray-700 mb-1">Tecido</label>
                                <select name="tecido_id" id="tecido_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um tecido</option>
                                    @foreach($tecidos as $tecido)
                                        <option value="{{ $tecido->id }}" {{ old('tecido_id', $produto->tecido_id) == $tecido->id ? 'selected' : '' }}>
                                            {{ $tecido->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estilista -->
                            <div>
                                <label for="estilista_id" class="block text-sm font-medium text-gray-700 mb-1">Estilista</label>
                                <select name="estilista_id" id="estilista_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um estilista</option>
                                    @foreach($estilistas as $estilista)
                                        <option value="{{ $estilista->id }}" {{ old('estilista_id', $produto->estilista_id) == $estilista->id ? 'selected' : '' }}>
                                            {{ $estilista->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Grupo -->
                            <div>
                                <label for="grupo_id" class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                                <select name="grupo_id" id="grupo_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ old('grupo_id', $produto->grupo_id) == $grupo->id ? 'selected' : '' }}>
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
                                <select name="status_id" id="status_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id', $produto->status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ficha de Produção -->
                            <div>
                                <label for="ficha_producao" class="block text-sm font-medium text-gray-700 mb-1">Ficha de Produção</label>
                                @if($produto->ficha_producao)
                                    <div class="mb-2 flex items-center">
                                        <a href="{{ asset('storage/' . $produto->ficha_producao) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Visualizar ficha atual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="ficha_producao" id="ficha_producao" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <p class="mt-1 text-sm text-gray-500">Formatos aceitos: PDF, DOC, DOCX (máx. 10MB)</p>
                            </div>

                            <!-- Catálogo de Vendas -->
                            <div>
                                <label for="catalogo_vendas" class="block text-sm font-medium text-gray-700 mb-1">Catálogo de Vendas</label>
                                @if($produto->catalogo_vendas)
                                    <div class="mb-2 flex items-center">
                                        <a href="{{ asset('storage/' . $produto->catalogo_vendas) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Visualizar catálogo atual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="catalogo_vendas" id="catalogo_vendas" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <p class="mt-1 text-sm text-gray-500">Formatos aceitos: PDF, DOC, DOCX (máx. 10MB)</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Atualizar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
