<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Produto') }}
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

                    <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Referência -->
                            <div>
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                                <input type="text" name="referencia" id="referencia" value="{{ old('referencia') }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <input type="text" name="descricao" id="descricao" value="{{ old('descricao') }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Data de Cadastro -->
                            <div>
                                <label for="data_cadastro" class="block text-sm font-medium text-gray-700 mb-1">Data de Cadastro</label>
                                <input type="date" name="data_cadastro" id="data_cadastro" value="{{ old('data_cadastro', date('Y-m-d')) }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Marca -->
                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <select name="marca_id" id="marca_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione uma marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $marca->nome_marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantidade -->
                            <div>
                                <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', 0) }}" min="0" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Estilista -->
                            <div>
                                <label for="estilista_id" class="block text-sm font-medium text-gray-700 mb-1">Estilista</label>
                                <select name="estilista_id" id="estilista_id" class="estilista-select block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione um estilista</option>
                                    @foreach($estilistas as $estilista)
                                        <option value="{{ $estilista->id }}" {{ old('estilista_id') == $estilista->id ? 'selected' : '' }} class="text-gray-700">
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
                                        <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $grupo->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Preço Atacado -->
                            <div>
                                <label for="preco_atacado" class="block text-sm font-medium text-gray-700 mb-1">Preço Atacado (R\$)</label>
                                <input type="number" name="preco_atacado" id="preco_atacado" value="{{ old('preco_atacado', '0.00') }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Preço Varejo -->
                            <div>
                                <label for="preco_varejo" class="block text-sm font-medium text-gray-700 mb-1">Preço Varejo (R\$)</label>
                                <input type="number" name="preco_varejo" id="preco_varejo" value="{{ old('preco_varejo', '0.00') }}" min="0" step="0.01" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_id" id="status_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700" required>
                                    <option value="">Selecione um status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }} class="text-gray-700">
                                            {{ $status->descricao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                        <!-- Seção de Tecidos separada em uma única coluna -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tecidos</label>
                            <div class="border border-gray-300 rounded-md p-4">
                                <div id="tecidos-container">
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

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Salvar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Aguardar que o DOM e jQuery estejam totalmente carregados
        $(function() {
            const container = document.getElementById('tecidos-container');
            const addButton = document.getElementById('add-tecido');
            let tecidoCount = 0;

            // Inicializar Select2 para os selects existentes
            $(document).ready(function() {
                $('.tecido-select').select2({
                placeholder: "Selecione um tecido",
                allowClear: true,
                width: '100%'
            });

            // Ajustar estilo do Select2 para combinar com o Tailwind
            $('.select2-container--default .select2-selection--single').css({
                'height': '42px',
                'padding': '6px 4px',
                'border-color': '#d1d5db'
            });
            });

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

            // Update all selects to remove already selected options
            function updateSelectOptions() {
                const selectedIds = getSelectedTecidoIds();
                const selects = container.querySelectorAll('select[name^="tecidos"]');

                // Get all available options from the first select (which has all options)
                const firstSelect = container.querySelector('select');
                const allOptions = Array.from(firstSelect.options);

                selects.forEach(select => {
                    const currentValue = select.value;

                    // Destruir a instância Select2 antes de modificar as opções
                    $(select).select2('destroy');

                    // Clear all options except the first one (placeholder)
                    while (select.options.length > 1) {
                        select.remove(1);
                    }

                    // Add back all options that aren't selected elsewhere
                    allOptions.forEach(option => {
                        if (option.value === '' || option.value === currentValue || !selectedIds.includes(option.value)) {
                            const newOption = document.createElement('option');
                            newOption.value = option.value;
                            newOption.text = option.text;
                            if (option.value === currentValue) {
                                newOption.selected = true;
                            }
                            select.add(newOption);
                        }
                    });

                    // Reinicializar Select2 após modificar as opções
                    $(select).select2({
                        placeholder: "Selecione um tecido",
                        allowClear: true,
                        width: '100%'
                    });
                });

                // Ajustar estilo do Select2 para combinar com o Tailwind
                $('.select2-container--default .select2-selection--single').css({
                    'height': '42px',
                    'padding': '6px 4px',
                    'border-color': '#d1d5db'
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

                // Add change event listener to the new select
                const newSelect = newItem.querySelector('select');
                newSelect.addEventListener('change', updateSelectOptions);

                // Initialize Select2 on the new select
                $(newSelect).select2({
                    placeholder: "Selecione um tecido",
                    allowClear: true,
                    width: '100%'
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
                    'display': 'block'
                });

                // Não precisamos adicionar event listener aqui, pois usaremos delegação de eventos
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
            });

            // Initialize
            updateRemoveButtons();
            updateSelectOptions();

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
        });
    </script>
    @endpush
</x-app-layout>
