<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movimentações') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        @php
        // Função para calcular dias úteis entre duas datas (excluindo sábados e domingos)
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
        @endphp
        <div class="w-[98%] mx-auto px-2">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                @if(auth()->user() && auth()->user()->canCreate('movimentacoes'))
                <a href="{{ route('movimentacoes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Movimentação
                </a>
                @endif
                <!-- Botão para gerar PDF da lista -->
                @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
                <a href="{{ route('movimentacoes.lista.pdf', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar PDF
                </a>
                @endif
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                        <form action="{{ route('movimentacoes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ request('referencia') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência do produto">
                            </div>

                            <div class="md:col-span-2">
                                <label for="produto" class="block text-sm font-medium text-gray-700 mb-1">Produto</label>
                                <input type="text" name="produto" id="produto" value="{{ request('produto') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite a referência ou descrição do produto">
                            </div>

                            <div>
                                <label for="tipo_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                <select name="tipo_id" id="tipo_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($tipos ?? [] as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="situacao_id" class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                                <select name="situacao_id" id="situacao_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($situacoes ?? [] as $situacao)
                                        <option value="{{ $situacao->id }}" {{ request('situacao_id') == $situacao->id ? 'selected' : '' }}>{{ $situacao->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                                <select name="localizacao_id" id="localizacao_id" class="select2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($localizacoes ?? [] as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ request('localizacao_id') == $localizacao->id ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}{{ !$localizacao->ativo ? ' (Inativa)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="comprometido" class="block text-sm font-medium text-gray-700 mb-1">Comprometido</label>
                                <select name="comprometido" id="comprometido" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('comprometido') == '1' ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ request('comprometido') == '0' ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>

                            <div>
                                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Entrada (Início)</label>
                                <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Entrada (Fim)</label>
                                <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="concluido" class="block text-sm font-medium text-gray-700 mb-1">Status Conclusão</label>
                                <select name="concluido" id="concluido" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" {{ request('concluido') === null ? 'selected' : '' }}>Todos</option>
                                    <option value="1" {{ request('concluido') === '1' ? 'selected' : '' }}>Concluídas</option>
                                    <option value="0" {{ request('concluido') === '0' ? 'selected' : '' }}>Não Concluídas</option>
                                </select>
                            </div>

                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <select name="marca_id" id="marca_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($marcas ?? [] as $marca)
                                        <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nome_marca }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_id" id="status_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($status ?? [] as $st)
                                        <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>{{ $st->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('movimentacoes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

            <!-- Mensagem de sucesso -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            @if(session('warning') && session('pdf_count'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-medium">{{ session('warning') }}</p>
                    <div class="mt-2 flex items-center">
                        <p>Deseja continuar mesmo assim?</p>
                        <a href="{{ route('movimentacoes.lista.pdf', array_merge(request()->query(), ['confirmar_pdf' => 1])) }}" 
                           class="ml-4 bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs">
                            Sim, gerar PDF com {{ session('pdf_count') }} registros
                        </a>
                    </div>
                </div>
            @endif

                    <!-- Tabela de Movimentações -->
                    <div class="relative overflow-hidden">
                        <!-- Versão para desktop/tablet -->
                        <div class="block">
                            <table class="w-full divide-y divide-gray-200 text-sm table-fixed">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'produto', 'direction' => request('sort') == 'produto' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Produto
                                            @if(request('sort') == 'produto')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'produto.status', 'direction' => request('sort') == 'produto.status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center hover:text-gray-700">
                                            Status
                                            @if(request('sort') == 'status')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 whitespace-nowrap text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Concluído
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'localizacao', 'direction' => request('sort') == 'localizacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Localização
                                            @if(request('sort') == 'localizacao')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'tipo', 'direction' => request('sort') == 'tipo' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Tipo
                                            @if(request('sort') == 'tipo')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'situacao', 'direction' => request('sort') == 'situacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Situação
                                            @if(request('sort') == 'situacao')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[90px]">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_entrada', 'direction' => request('sort') == 'data_entrada' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                                            Data Entrada
                                            @if(request('sort') == 'data_entrada')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[90px]">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_saida', 'direction' => request('sort') == 'data_saida' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                                            Data Saída
                                            @if(request('sort') == 'data_saida')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[90px]">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'data_devolucao', 'direction' => request('sort') == 'data_devolucao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end hover:text-gray-700">
                                            Data Devolução
                                            @if(request('sort') == 'data_devolucao')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'comprometido', 'direction' => request('sort') == 'comprometido' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Comprometido
                                            @if(request('sort') == 'comprometido')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('movimentacoes.index', array_merge(request()->query(), ['sort' => 'observacao', 'direction' => request('sort') == 'observacao' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Observação
                                            @if(request('sort') == 'observacao')
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    @if(request('direction') == 'asc')
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                    @else
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dias
                                    </th>
                                    <th scope="col" class="sticky right-0 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50 shadow-md z-10">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($movimentacoes as $movimentacao)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                            @if($movimentacao->produto_id)
                                                <div>{{ $movimentacao->produto->referencia }}</div>
                                                <div class="text-gray-500 text-xs truncate max-w-[150px]" title="{{ $movimentacao->produto->descricao }}">
                                                    {{ Str::limit($movimentacao->produto->descricao, 25, '...') }}
                                                </div>
                                                @if($movimentacao->produto->marca)
                                                <div class="text-gray-400 text-[10px] truncate max-w-[150px]" title="{{ $movimentacao->produto->marca->nome_marca }}">
                                                    {{ $movimentacao->produto->marca->nome_marca }}
                                                </div>
                                                @endif
                                            @else
                                                <span class="text-red-500">Produto não encontrado</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-center">
                                            @if($movimentacao->produto && $movimentacao->produto->status)
                                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    {{ $movimentacao->produto->status->descricao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-center">
                                            @if($movimentacao->concluido)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                            @if($movimentacao->localizacao)
                                                <div class="truncate max-w-[100px]" title="{{ $movimentacao->localizacao->nome_localizacao }}">
                                                    {{ Str::limit($movimentacao->localizacao->nome_localizacao, 15, '...') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                            @if($movimentacao->situacao)
                                                <div class="truncate max-w-[100px]" title="{{ $movimentacao->situacao->descricao }}">
                                                    <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ Str::limit($movimentacao->situacao->descricao, 15, '...') }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500 text-right">
                                            {{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500 text-right">
                                            {{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500 text-right">
                                            {{ $movimentacao->data_devolucao ? $movimentacao->data_devolucao->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->comprometido ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $movimentacao->comprometido ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500 max-w-[120px] overflow-hidden">
                                            @if($movimentacao->observacao)
                                                <div class="truncate" title="{{ $movimentacao->observacao }}">
                                                    {{ Str::limit($movimentacao->observacao, 20, '...') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                            @php
                                                $diasEntre = null;
                                                $prazoExcedido = false;
                                                $prazoSetor = null;
                                                
                                                if ($movimentacao->data_entrada) {
                                                    if ($movimentacao->data_saida) {
                                                        // Se tem data de saída, calcular dias úteis entre entrada e saída
                                                        $diasEntre = calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                                                    } else {
                                                        // Se não tem data de saída, calcular dias úteis entre entrada e data atual
                                                        $diasEntre = calcularDiasUteis($movimentacao->data_entrada, now());
                                                    }
                                                    
                                                    // Verificar se excede o prazo do setor
                                                    if ($movimentacao->localizacao && $movimentacao->localizacao->prazo) {
                                                        $prazoExcedido = $diasEntre > $movimentacao->localizacao->prazo;
                                                        $prazoSetor = $movimentacao->localizacao->prazo;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($diasEntre !== null)
                                                <div class="text-center">
                                                    <span class="px-2 py-1 inline-block text-xs {{ $prazoExcedido ? 'bg-red-100 text-red-800 font-bold' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                                        {{ $diasEntre }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                                                    </span>
                                                    @if($prazoExcedido && isset($prazoSetor))
                                                        <div class="text-xs mt-1 text-red-600 font-medium">
                                                            (Prazo: {{ $prazoSetor }} {{ $prazoSetor == 1 ? 'dia' : 'dias' }})
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <span class="text-gray-400">-</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="sticky right-0 px-3 py-2 whitespace-nowrap text-right text-xs font-medium bg-white shadow-md z-10">
                                            <div class="flex justify-end space-x-1">
                                                @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
                                                <a href="{{ route('movimentacoes.show', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                @if(auth()->user() && auth()->user()->canUpdate('movimentacoes'))
                                                <a href="{{ route('movimentacoes.edit', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                @if($movimentacao->anexo && auth()->user() && auth()->user()->canRead('movimentacoes'))
                                                <button type="button" onclick="openImageModal('{{ $movimentacao->anexo_url }}', {{ $movimentacao->id }})" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                                @endif
                                                @if(auth()->user() && auth()->user()->canDelete('movimentacoes'))
                                                <form action="{{ route('movimentacoes.destroy', $movimentacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                                            Nenhuma movimentação encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        
                        <!-- Versão para dispositivos móveis (cards) -->
                        <div class="hidden space-y-4">
                            @forelse($movimentacoes as $movimentacao)
                                <div class="bg-white shadow rounded-lg p-4">
                                    <!-- Produto e Status -->
                                    <div class="mb-3 border-b pb-2">
                                        <div class="flex justify-between items-center">
                                            <div class="font-medium text-gray-900">{{ $movimentacao->produto->referencia }}</div>
                                            @if($movimentacao->produto && $movimentacao->produto->status)
                                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    {{ $movimentacao->produto->status->descricao }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 truncate" title="{{ $movimentacao->produto->descricao }}">{{ Str::limit($movimentacao->produto->descricao, 40, '...') }}</div>
                                    </div>
                                    
                                    <!-- Informações principais -->
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div>
                                            <div class="text-xs text-gray-500 font-medium">LOCALIZAÇÃO</div>
                                            <div class="text-sm">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 font-medium">SITUAÇÃO</div>
                                            @if($movimentacao->situacao)
                                                <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->situacao->cor ? 'bg-'.$movimentacao->situacao->cor.'-100 text-'.$movimentacao->situacao->cor.'-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $movimentacao->situacao->descricao ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Tipo e Dias -->
                                    <div class="flex justify-between mb-3">
                                        <div class="flex-1">
                                            <div class="text-xs text-gray-500 font-medium">TIPO</div>
                                            <span class="px-2 py-1 rounded-full text-xs {{ $movimentacao->tipo && $movimentacao->tipo->descricao == 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="flex-1 text-right">
                                            <div class="text-xs text-gray-500 font-medium">DIAS</div>
                                            @php
                                                $diasEntre = null;
                                                $prazoExcedido = false;
                                                $prazoSetor = null;
                                                
                                                if ($movimentacao->data_entrada) {
                                                    if ($movimentacao->data_saida) {
                                                        $diasEntre = calcularDiasUteis($movimentacao->data_entrada, $movimentacao->data_saida);
                                                    } else {
                                                        $diasEntre = calcularDiasUteis($movimentacao->data_entrada, now());
                                                    }
                                                    
                                                    if ($movimentacao->localizacao && $movimentacao->localizacao->prazo) {
                                                        $prazoExcedido = $diasEntre > $movimentacao->localizacao->prazo;
                                                        $prazoSetor = $movimentacao->localizacao->prazo;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($diasEntre !== null)
                                                <div class="text-sm {{ $prazoExcedido ? 'text-red-600 font-bold' : 'text-blue-600' }}">
                                                    {{ $diasEntre }} {{ $diasEntre == 1 ? 'dia' : 'dias' }}
                                                    @if($prazoSetor)
                                                        <span class="text-xs text-gray-500">({{ $prazoSetor }})</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Datas - Agora em uma única linha -->
                                    <div class="flex justify-between mb-3 text-right">
                                        <div class="flex-1 text-center">
                                            <div class="text-xs text-gray-500 font-medium">ENTRADA</div>
                                            <div class="text-sm">{{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : 'N/A' }}</div>
                                        </div>
                                        
                                        <div class="flex-1 text-center border-l border-r border-gray-200 px-2">
                                            <div class="text-xs text-gray-500 font-medium">SAÍDA</div>
                                            <div class="text-sm">{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y') : 'N/A' }}</div>
                                        </div>
                                        
                                        <div class="flex-1 text-center">
                                            <div class="text-xs text-gray-500 font-medium">DEVOLUÇÃO</div>
                                            <div class="text-sm">{{ $movimentacao->data_devolucao ? $movimentacao->data_devolucao->format('d/m/Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Ações -->
                                    <div class="flex justify-end space-x-2 pt-2 border-t">
                                        @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
                                        <a href="{{ route('movimentacoes.show', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @endif
                                        @if(auth()->user() && auth()->user()->canUpdate('movimentacoes'))
                                        <a href="{{ route('movimentacoes.edit', $movimentacao) }}?back_url={{ urlencode(Request::fullUrl()) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @endif
                                        @if($movimentacao->anexo && auth()->user() && auth()->user()->canRead('movimentacoes'))
                                        <button type="button" onclick="openImageModal('{{ $movimentacao->anexo_url }}', {{ $movimentacao->id }})" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                        @endif
                                        @if(auth()->user() && auth()->user()->canDelete('movimentacoes'))
                                        <form action="{{ route('movimentacoes.destroy', $movimentacao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white shadow rounded-lg p-4 text-center text-gray-500">
                                    Nenhuma movimentação encontrada.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $movimentacoes->appends(request()->query())->links('vendor.pagination.simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2 no filtro de localização
            $('#localizacao_id').select2({
                placeholder: "Selecione uma localização",
                allowClear: true
            });
            
            // Ajustar estilo do Select2 para combinar com Tailwind
            $('.select2-container--default .select2-selection--single').css({
                'height': '38px',
                'padding': '5px 0',
                'border-color': 'rgb(209, 213, 219)'
            });
            const toggleButton = document.getElementById('toggle-filters');
            const filterContainer = document.getElementById('filter-container');

            // Inicialmente mostrar os filtros
            // filterContainer.style.display = 'block';

            toggleButton.addEventListener('click', function() {
                if (filterContainer.style.display === 'none') {
                    filterContainer.style.display = 'block';
                    toggleButton.textContent = 'Ocultar Filtros';
                } else {
                    filterContainer.style.display = 'none';
                    toggleButton.textContent = 'Mostrar Filtros';
                }
            });

            // Funções para o modal de imagem
            window.openImageModal = function(imageUrl, id) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = imageUrl;
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Impede rolagem do body
                console.log('Modal aberto: ' + imageUrl);
            }

            window.closeImageModal = function() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Restaura rolagem do body
            }

            // Fechar o modal ao clicar fora da imagem
            const modal = document.getElementById('imageModal');
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeImageModal();
                }
            });

            // Fechar o modal com a tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                    closeImageModal();
                }
            });
        });
    </script>

    <!-- Modal para exibir imagem -->
    <div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
        <div class="relative max-w-4xl max-h-screen p-2">
            <button type="button" onclick="closeImageModal()" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Anexo da Movimentação" class="max-w-full max-h-[90vh] rounded-lg shadow-lg object-contain bg-white p-1">
        </div>
    </div>
</x-app-layout>
