<script>
    // Funções globais para gerenciar modais
    window.showCombinacaoModal = function() {
        // Chama a função interna se estiver disponível (via jQuery), senão abre apenas o modal
        if (typeof window.mostrarModalCombinacaoInternal === 'function') {
            window.mostrarModalCombinacaoInternal();
        } else {
            document.getElementById('modal-combinacao').classList.remove('hidden');
        }
    };

    window.fecharModalCombinacao = function() {
        const modal = document.getElementById('modal-combinacao');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    window.fecharModalComponente = function() {
        const modal = document.getElementById('modal-componente');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Function to delete attachment
    window.deleteAttachment = function(anexoId) {
        if (!confirm('Tem certeza que deseja excluir este anexo?')) {
            return;
        }

        // Criar um formulário temporário para enviar a requisição DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("produtos/anexos") }}/' + anexoId;
        form.style.display = 'none';

        // Adicionar campo CSRF
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = '{{ csrf_token() }}';
        form.appendChild(csrfField);

        // Adicionar campo METHOD spoofing para DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        // Adicionar o formulário ao body e submeter
        document.body.appendChild(form);
        form.submit();
    }

    $(document).ready(function() {
        console.log('Document ready - JavaScript carregado com sucesso!');

        // Variáveis para combinações
        const combinacoesContainer = $('#combinacoes-container');
        let combinacoes = [];

        // Atualizar o contador de cores
        function updateCoresCount(count) {
            $('#cores-count').text(count);
        }

        // Funções para gerenciar combinações
        function carregarCombinacoes() {
            $.ajax({
                url: '{{ url("produtos") }}/{{ $produto->id }}/combinacoes',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.combinacoes && response.combinacoes.length > 0) {
                        combinacoes = response.combinacoes;
                        renderizarCombinacoes();
                    } else {
                        combinacoesContainer.html('<div class="text-center py-4 text-gray-500 italic">Clique em "Nova Combinação" para adicionar uma combinação de cores.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    combinacoesContainer.html('<div class="text-center py-4 text-red-500 italic">Erro ao carregar combinações: ' + error + '</div>');
                }
            });
        }

        // Carregar combinações ao iniciar
        carregarCombinacoes();

        // Renderizar combinações
        function renderizarCombinacoes() {
            if (!combinacoes || combinacoes.length === 0) {
                combinacoesContainer.html('<div class="text-center py-4 text-gray-500 italic">Clique em "Nova Combinação" para adicionar uma combinação de cores.</div>');
                return;
            }

            let html = '';

            combinacoes.forEach(function(combinacao) {
                html += `
                <div class="combinacao-item mb-6 pb-6 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0" data-id="${combinacao.id}">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex-grow">
                            <h4 class="text-md font-medium text-gray-800">${combinacao.descricao}</h4>
                            <div class="text-sm text-gray-600">Quantidade pretendida: ${combinacao.quantidade_pretendida}</div>
                            ${combinacao.observacoes ? `<div class="text-sm text-gray-500 italic">${combinacao.observacoes}</div>` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" class="editar-combinacao text-blue-600 hover:text-blue-800" data-id="${combinacao.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button type="button" class="excluir-combinacao text-red-600 hover:text-red-800" data-id="${combinacao.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="componentes-container" data-combinacao-id="${combinacao.id}">
                        ${renderizarComponentes(combinacao.componentes)}
                    </div>

                    <div class="mt-3">
                        <button type="button" class="adicionar-componente inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" data-combinacao-id="${combinacao.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Adicionar Componente
                        </button>
                    </div>
                </div>
            `;
            });

            combinacoesContainer.html(html);
        }

        // Renderizar componentes de uma combinação
        function renderizarComponentes(componentes) {
            if (!componentes || componentes.length === 0) {
                return '<div class="text-center py-3 text-gray-500 italic">Nenhum componente adicionado.</div>';
            }

            let html = '<div class="grid grid-cols-1 gap-3">';

            componentes.forEach(function(componente) {
                html += `
                <div class="componente-item bg-gray-50 p-3 rounded-md border border-gray-200" data-id="${componente.id}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 rounded border border-gray-300" style="background-color: ${componente.codigo_cor || '#FFFFFF'}"></div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">${componente.tecido ? componente.tecido.descricao : 'Tecido não encontrado'}</div>
                                <div class="text-sm text-gray-600">${componente.cor} ${componente.codigo_cor ? `(${componente.codigo_cor})` : ''}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="text-sm text-gray-700">Consumo: <span class="font-medium">${componente.consumo} m</span></div>
                            <button type="button" class="editar-componente text-blue-600 hover:text-blue-800 ml-2" data-id="${componente.id}" data-combinacao-id="${componente.produto_combinacao_id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button type="button" class="excluir-componente text-red-600 hover:text-red-800" data-id="${componente.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            });

            html += '</div>';
            return html;
        }

        // Funções para gerenciar modais
        function mostrarModalCombinacao(combinacaoId = null) {
            // Limpar campos
            $('#combinacao-id').val('');
            $('#combinacao-descricao').val('');
            $('#combinacao-quantidade').val('1');
            $('#combinacao-observacoes').val('');

            // Se for edição, carregar dados da combinação
            if (combinacaoId) {
                const combinacao = combinacoes.find(c => c.id == combinacaoId);
                if (combinacao) {
                    $('#modal-combinacao-title').text('Editar Combinação');
                    $('#combinacao-id').val(combinacao.id);
                    $('#combinacao-descricao').val(combinacao.descricao);
                    $('#combinacao-quantidade').val(combinacao.quantidade_pretendida);
                    $('#combinacao-observacoes').val(combinacao.observacoes);
                }
            } else {
                $('#modal-combinacao-title').text('Nova Combinação');
            }

            // Exibir modal removendo a classe hidden
            $('#modal-combinacao').removeClass('hidden');
            // Garantir que o estilo inline display: none seja removido se existir
            document.getElementById('modal-combinacao').style.display = '';
        }

        // Expor a função para o escopo global para que possa ser usada pelo botão
        window.mostrarModalCombinacaoInternal = mostrarModalCombinacao;

        function mostrarModalComponente(combinacaoId, componenteId = null) {
            // Limpar campos
            $('#componente-id').val('');
            $('#componente-combinacao-id').val(combinacaoId);
            $('#componente-tecido').val('').trigger('change');
            $('#componente-cor').val('').prop('disabled', true);
            $('#componente-consumo').val('1');

            // Atualizar lista de tecidos disponíveis com base nos tecidos atualmente selecionados
            atualizarTecidosDisponiveis(componenteId);

            // Se for edição, carregar dados do componente
            if (componenteId) {
                const combinacao = combinacoes.find(c => c.id == combinacaoId);
                if (combinacao) {
                    const componente = combinacao.componentes.find(comp => comp.id == componenteId);
                    if (componente) {
                        $('#modal-componente-title').text('Editar Componente');
                        $('#componente-id').val(componente.id);
                        $('#componente-tecido').val(componente.tecido_id).trigger('change');

                        // Carregar cores do tecido e selecionar a cor atual
                        carregarCoresTecido(componente.tecido_id, componente.cor, componente.codigo_cor);

                        $('#componente-consumo').val(componente.consumo);
                    }
                }
            } else {
                $('#modal-componente-title').text('Adicionar Componente');
            }

            // Exibir modal
            $('#modal-componente').removeClass('hidden');
            document.getElementById('modal-componente').style.display = 'block';
        }

        // Função para atualizar a lista de tecidos disponíveis no modal de componente
        function atualizarTecidosDisponiveis(componenteId = null) {
            const tecidosDisponiveis = [];

            // Percorrer todos os selects de tecido na página
            $('.tecido-select').each(function() {
                const tecidoId = $(this).val();
                const tecidoText = $(this).find('option:selected').text();

                if (tecidoId && tecidoId !== '' && tecidoText && tecidoText !== 'Selecione um tecido') {
                    // Verificar se já não está na lista
                    if (!tecidosDisponiveis.find(t => t.id === tecidoId)) {
                        tecidosDisponiveis.push({
                            id: tecidoId,
                            text: tecidoText
                        });
                    }
                }
            });

            // Atualizar o select de tecidos no modal
            const selectTecido = $('#componente-tecido');
            const tecidoAtualSelecionado = selectTecido.val();

            // Limpar e reconstruir as opções
            selectTecido.html('<option value="">Selecione um tecido</option>');

            tecidosDisponiveis.forEach(function(tecido) {
                const option = $('<option></option>')
                    .attr('value', tecido.id)
                    .text(tecido.text);
                selectTecido.append(option);
            });

            // Se estava editando, manter o tecido selecionado
            if (tecidoAtualSelecionado && componenteId) {
                selectTecido.val(tecidoAtualSelecionado);
            }
        }

        // Função para carregar cores de um tecido
        function carregarCoresTecido(tecidoId, corSelecionada = null, codigoCor = null) {
            if (!tecidoId) {
                $('#componente-cor').html('<option value="">Selecione um tecido primeiro</option>');
                $('#componente-cor').prop('disabled', true);
                $('#info-estoque').addClass('hidden');
                return;
            }

            $.ajax({
                url: '{{ url("tecidos") }}/' + tecidoId + '/cores',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.cores && response.cores.length > 0) {
                        let options = '<option value="">Selecione uma cor</option>';

                        response.cores.forEach(function(cor) {
                            const selected = corSelecionada && cor.cor === corSelecionada ? ' selected' : '';
                            options += `<option value="${cor.cor}" data-codigo="${cor.codigo_cor || ''}" data-estoque="${cor.estoque || 0}" data-necessidade="${cor.necessidade || 0}" data-saldo="${cor.saldo || 0}" data-producao="${cor.producao_possivel || 0}"${selected}>${cor.cor} ${cor.codigo_cor ? `(${cor.codigo_cor})` : ''}</option>`;
                        });

                        $('#componente-cor').html(options);
                        $('#componente-cor').prop('disabled', false);

                        // Se temos uma cor selecionada mas não foi encontrada nas opções, adicionar manualmente
                        if (corSelecionada && !$('#componente-cor').val()) {
                            const newOption = `<option value="${corSelecionada}" data-codigo="${codigoCor || ''}" data-estoque="0" data-necessidade="0" data-saldo="0" data-producao="0" selected>${corSelecionada} ${codigoCor ? `(${codigoCor})` : ''}</option>`;
                            $('#componente-cor').append(newOption);
                        }
                        // Se já temos uma cor selecionada, mostrar informações de estoque
                        if (corSelecionada) {
                            atualizarInfoEstoque();
                        }
                    } else {
                        $('#componente-cor').html('<option value="">Nenhuma cor disponível</option>');
                        $('#componente-cor').prop('disabled', true);
                        $('#info-estoque').addClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    $('#componente-cor').html('<option value="">Erro ao carregar cores</option>');
                    $('#componente-cor').prop('disabled', true);
                    $('#info-estoque').addClass('hidden');
                }
            });
        }

        // Função para atualizar as informações de estoque
        function atualizarInfoEstoque() {
            const corOption = $('#componente-cor option:selected');

            if (corOption.val()) {
                const estoque = parseFloat(corOption.data('estoque')) || 0;
                const necessidade = parseFloat(corOption.data('necessidade')) || 0;
                const saldo = parseFloat(corOption.data('saldo')) || 0;
                const producao = parseFloat(corOption.data('producao')) || 0;

                $('#info-estoque-valor').text(estoque.toLocaleString('pt-BR') + ' m');
                $('#info-necessidade-valor').text(necessidade.toLocaleString('pt-BR') + ' m');

                // Aplicar cores para saldo
                const saldoElement = $('#info-saldo-valor');
                saldoElement.text(saldo.toLocaleString('pt-BR') + ' m');
                if (saldo < 0) {
                    saldoElement.removeClass('text-green-600').addClass('text-red-600');
                } else {
                    saldoElement.removeClass('text-red-600').addClass('text-green-600');
                }

                // Aplicar cores para produção possível
                const producaoElement = $('#info-producao-valor');
                producaoElement.text(producao.toLocaleString('pt-BR') + ' unidades');
                if (producao <= 0) {
                    producaoElement.removeClass('text-green-600').addClass('text-red-600');
                } else {
                    producaoElement.removeClass('text-red-600').addClass('text-green-600');
                }

                $('#info-estoque').removeClass('hidden');
            } else {
                $('#info-estoque').addClass('hidden');
            }
        }

        // Event handlers
        $(document).on('click', '#add-combinacao', function() {
            mostrarModalCombinacao();
        });

        $(document).on('click', '.editar-combinacao', function() {
            const combinacaoId = $(this).data('id');
            mostrarModalCombinacao(combinacaoId);
        });

        $(document).on('click', '.excluir-combinacao', function() {
            if (!confirm('Tem certeza que deseja excluir esta combinação?')) {
                return;
            }

            const combinacaoId = $(this).data('id');

            $.ajax({
                url: '{{ url("produtos/combinacoes") }}/' + combinacaoId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        carregarCombinacoes();
                    } else {
                        alert('Erro ao excluir combinação: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro ao excluir combinação: ' + error);
                }
            });
        });

        $(document).on('click', '#salvar-combinacao', function() {
            const combinacaoId = $('#combinacao-id').val();
            const descricao = $('#combinacao-descricao').val();
            const quantidade = $('#combinacao-quantidade').val();
            const observacoes = $('#combinacao-observacoes').val();

            if (!descricao || !quantidade) {
                alert('Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            const data = {
                descricao: descricao,
                quantidade_pretendida: quantidade,
                observacoes: observacoes,
                _token: '{{ csrf_token() }}'
            };

            let url, method;

            if (combinacaoId) {
                // Edição
                url = '{{ url("produtos/combinacoes") }}/' + combinacaoId;
                method = 'PUT';
            } else {
                // Nova combinação
                url = '{{ url("produtos") }}/{{ $produto->id }}/combinacoes';
                method = 'POST';
            }

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    if (response.success) {
                        $('#modal-combinacao').addClass('hidden');
                        document.getElementById('modal-combinacao').style.display = 'none';
                        carregarCombinacoes();
                    } else {
                        alert('Erro ao salvar combinação: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro ao salvar combinação: ' + error);
                }
            });
        });

        $(document).on('click', '.adicionar-componente', function() {
            const combinacaoId = $(this).data('combinacao-id');
            mostrarModalComponente(combinacaoId);
        });

        $(document).on('click', '.editar-componente', function() {
            const componenteId = $(this).data('id');
            const combinacaoId = $(this).data('combinacao-id');
            mostrarModalComponente(combinacaoId, componenteId);
        });

        $(document).on('click', '.excluir-componente', function() {
            if (!confirm('Tem certeza que deseja excluir este componente?')) {
                return;
            }

            const componenteId = $(this).data('id');

            $.ajax({
                url: '{{ url("combinacoes/componentes") }}/' + componenteId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        carregarCombinacoes();
                    } else {
                        alert('Erro ao excluir componente: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro ao excluir componente: ' + error);
                }
            });
        });

        $(document).on('change', '#componente-tecido', function() {
            const tecidoId = $(this).val();
            carregarCoresTecido(tecidoId);
        });

        $(document).on('change', '#componente-cor', function() {
            atualizarInfoEstoque();
        });

        $(document).on('click', '#salvar-componente', function() {
            const componenteId = $('#componente-id').val();
            const combinacaoId = $('#componente-combinacao-id').val();
            const tecidoId = $('#componente-tecido').val();
            const corOption = $('#componente-cor option:selected');
            const cor = corOption.val();
            const codigoCor = corOption.data('codigo');
            const consumo = $('#componente-consumo').val();

            if (!tecidoId || !cor || !consumo) {
                alert('Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            const data = {
                tecido_id: tecidoId,
                cor: cor,
                codigo_cor: codigoCor,
                consumo: consumo,
                _token: '{{ csrf_token() }}'
            };

            let url, method;

            if (componenteId) {
                // Edição
                url = '{{ url("combinacoes/componentes") }}/' + componenteId;
                method = 'PUT';
            } else {
                // Novo componente
                url = '{{ url("combinacoes") }}/' + combinacaoId + '/componentes';
                method = 'POST';
            }

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    if (response.success) {
                        $('#modal-componente').addClass('hidden');
                        document.getElementById('modal-componente').style.display = 'none';
                        carregarCombinacoes();
                    } else {
                        alert('Erro ao salvar componente: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro ao salvar componente: ' + error);
                }
            });
        });

        // Debug: Capturar evento de submit do formulário
        $('form').on('submit', function(e) {

            // Verificar campos obrigatórios
            let camposVazios = [];

            // Verificar campos required
            $(this).find('[required]').each(function() {
                const campo = $(this);
                const valor = campo.val();
                const nome = campo.attr('name') || campo.attr('id');

                if (!valor || valor.trim() === '') {
                    camposVazios.push(nome);
                }
            });

            // Verificar se há pelo menos um tecido selecionado
            const tecidosSelecionados = $('.tecido-select').filter(function() {
                return $(this).val() && $(this).val() !== '';
            }).length;

            if (tecidosSelecionados === 0) {
                camposVazios.push('tecidos');
            }

            if (camposVazios.length > 0) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios: ' + camposVazios.join(', '));
                return false;
            }

            // Não prevenir o envio se tudo estiver OK
        });

        const tecidosContainer = $('#tecidos-container');
        const coresContainer = $('#cores-container');
        const addTecidoBtn = $('#add-tecido');


        function initializeSelect2(selector, placeholder) {
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
                language: {
                    noResults: () => "Nenhum resultado encontrado"
                }
            }).on('select2:open', () => {
                $('.select2-dropdown').addClass('rounded-md shadow-lg');
                $('.select2-search__field').addClass('border-gray-300 rounded-md');
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function updateColorVariations() {
            const selectedTecidos = [];
            $('.tecido-select').each(function() {
                const val = $(this).val();
                if (val && val !== '') {
                    selectedTecidos.push(val);
                }
            });


            if (selectedTecidos.length === 0) {
                coresContainer.html('<div class="text-center py-4 text-gray-500 italic">Selecione pelo menos um tecido para ver as cores disponíveis.</div>');
                return;
            }

            // Log antes da requisição


            // Armazenar a requisição AJAX atual para poder cancelá-la se necessário
            if (window.currentAjaxRequest) {
                window.currentAjaxRequest.abort();
            }

            try {
                // Fazer requisição AJAX
                window.currentAjaxRequest = $.ajax({
                    url: '{{ route("produtos.get-available-colors") }}',
                    method: 'POST',
                    data: {
                        tecido_ids: selectedTecidos,
                        produto_id: {{ $produto->id }},
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        coresContainer.html('<div class="text-center py-4 text-gray-500 italic">Carregando cores disponíveis...</div>');
                    },
                    success: function(response) {
                        if (response && response.cores) {
                            renderCores(response.cores);
                        } else {
                            coresContainer.html('<div class="text-center py-4 text-gray-500 italic">Nenhuma cor disponível para os tecidos selecionados.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Não mostrar erro se a requisição foi abortada intencionalmente
                        if (status !== 'abort') {
                            coresContainer.html('<div class="text-center py-4 text-red-500 italic">Erro ao carregar cores: ' + error + '</div>');
                        }
                    },
                    complete: function() {
                        window.currentAjaxRequest = null;
                    }
                });
            } catch (e) {
                console.error('Erro ao iniciar requisição AJAX:', e);
                coresContainer.html('<div class="text-center py-4 text-red-500 italic">Erro ao iniciar requisição: ' + e.message + '</div>');
            }
        }

        function renderCores(cores) {

            if (!cores || cores.length === 0) {
                coresContainer.html('<div class="text-center py-4 text-gray-500 italic">Nenhuma cor disponível para os tecidos selecionados.</div>');
                updateCoresCount(0);
                return;
            }

            let htmlExistentes = '';
            let htmlDisponiveis = '';
            let indexExistentes = 0;
            let indexDisponiveis = 0;

            cores.forEach(function(cor) {
                const backgroundColor = cor.codigo_cor && cor.codigo_cor !== '' ? cor.codigo_cor : '#FFFFFF';
                const borderColor = cor.tipo === 'existente' ? 'border-green-300 bg-green-50' : 'border-blue-300 bg-blue-50';
                const labelColor = cor.tipo === 'existente' ? 'text-green-700' : 'text-blue-700';
                const typeLabel = cor.tipo === 'existente' ? 'Cadastrada' : 'Disponível';

                // Formatar valores numéricos
                const estoque = parseFloat(cor.estoque || 0).toFixed(2).replace('.', ',');
                const necessidade = parseFloat(cor.necessidade || 0).toFixed(2).replace('.', ',');
                const producaoPossivel = parseInt(cor.producao_possivel || 0);

                // Determinar a cor do saldo (estoque - necessidade)
                const saldo = parseFloat(cor.estoque || 0) - parseFloat(cor.necessidade || 0);
                const saldoFormatado = saldo.toFixed(2).replace('.', ',');
                const saldoClass = saldo >= 0 ? 'text-green-600' : 'text-red-600';

                const corHtml = `
                    <div class="mb-3 p-3 border ${borderColor} rounded-md">
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded border border-gray-300" style="background-color: ${backgroundColor}" title="${cor.codigo_cor || 'N/A'}"></div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">${cor.cor || ''}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">${cor.codigo_cor || ''}</div>
                                        <div class="text-xs ${labelColor} font-medium">${typeLabel}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <label class="text-sm text-gray-700">Quantidade:</label>
                                    <input type="number"
                                           name="cores[${cor.tipo === 'existente' ? indexExistentes : cores.filter(c => c.tipo === 'existente').length + indexDisponiveis}][quantidade]"
                                           value="${cor.quantidade || 0}"
                                           placeholder="0"
                                           min="0"
                                           class="w-20 text-center border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <input type="hidden" name="cores[${cor.tipo === 'existente' ? indexExistentes : cores.filter(c => c.tipo === 'existente').length + indexDisponiveis}][cor]" value="${cor.cor}">
                                    <input type="hidden" name="cores[${cor.tipo === 'existente' ? indexExistentes : cores.filter(c => c.tipo === 'existente').length + indexDisponiveis}][codigo_cor]" value="${cor.codigo_cor}">
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <table class="w-full text-sm">
                                    <tr>
                                        <td class="pr-2"><span class="font-medium text-gray-700">Estoque:</span></td>
                                        <td class="pr-4">${estoque} m</td>
                                        <td class="pr-2"><span class="font-medium text-gray-700">Necessidade:</span></td>
                                        <td class="pr-4">${necessidade} m</td>
                                        <td class="pr-2"><span class="font-medium text-gray-700">Saldo:</span></td>
                                        <td class="pr-4"><span class="${saldoClass}">${saldoFormatado} m</span></td>
                                        <td class="pr-2"><span class="font-medium text-gray-700">Produção possível:</span></td>
                                        <td><span class="font-semibold ${producaoPossivel > 0 ? 'text-green-600' : 'text-gray-600'}">${producaoPossivel} unidades</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                if (cor.tipo === 'existente') {
                    htmlExistentes += corHtml;
                    indexExistentes++;
                } else {
                    htmlDisponiveis += corHtml;
                    indexDisponiveis++;
                }
            });

            let finalHtml = '';

            if (htmlExistentes) {
                finalHtml += `
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-green-700 mb-2">Cores já cadastradas no produto:</h4>
                        ${htmlExistentes}
                    </div>
                `;
            }

            if (htmlDisponiveis) {
                finalHtml += `
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-blue-700 mb-2">Cores disponíveis nos tecidos:</h4>
                        ${htmlDisponiveis}
                    </div>
                `;
            }

            coresContainer.html(finalHtml);

            // Atualizar o contador de cores selecionadas
            updateCoresCount(cores.length);
        }

        // Função para atualizar o contador de cores
        function updateCoresCount(count) {
            $('#cores-count').text(count);
        }

        function updateRemoveTecidoButtons() {
            const items = tecidosContainer.find('.tecido-item');
            if (items.length > 1) {
                items.find('.remove-tecido').show();
            } else {
                items.find('.remove-tecido').hide();
            }
        }

        function reindexTecidos() {
            tecidosContainer.find('.tecido-item').each(function(index) {
                $(this).find('[name^="tecidos"]').each(function() {
                    const oldName = $(this).attr('name');
                    const newName = oldName.replace(/tecidos\[\d+\]/, `tecidos[${index}]`);
                    $(this).attr('name', newName);
                });
            });
        }

        // Adicionar tecido - usando event delegation para garantir que funcione
        $(document).on('click', '#add-tecido', function(e) {
            e.preventDefault();
            console.log('Botão Adicionar Tecido clicado!');

            const newIndex = tecidosContainer.find('.tecido-item').length;
            console.log('Novo índice:', newIndex);

            const template = `
                <div class="tecido-item mb-3 first:mt-0 mt-3 pt-3 first:pt-0 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-grow">
                            <select name="tecidos[${newIndex}][tecido_id]" class="tecido-select-new block w-full">
                                <option></option>
                                @foreach($tecidos as $tecido)
                                    <option value="{{ $tecido->id }}">{{ $tecido->descricao }} @if($tecido->referencia) ({{ $tecido->referencia }}) @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/4"><input type="number" name="tecidos[${newIndex}][consumo]" placeholder="Consumo" step="0.001" min="0" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></div>
                        <button type="button" class="remove-tecido text-red-500 hover:text-red-700"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                    </div>
                </div>`;

            tecidosContainer.append(template);
            const newSelect = tecidosContainer.find('.tecido-select-new');
            initializeSelect2(newSelect, 'Selecione um tecido');
            newSelect.removeClass('tecido-select-new').addClass('tecido-select');
            updateRemoveTecidoButtons();
            updateColorVariations();
        });

        // Remover tecido - usando event delegation
        $(document).on('click', '.remove-tecido', function(e) {
            e.preventDefault();
            $(this).closest('.tecido-item').remove();
            reindexTecidos();
            updateRemoveTecidoButtons();
            updateColorVariations();
        });

        // Initial setup
        initializeSelect2('.grupo-select', 'Selecione um grupo');
        initializeSelect2('.estilista-select', 'Selecione um estilista');

        // Inicializar Select2 para tecidos e verificar se foram inicializados corretamente
        const tecidoSelects = $('.tecido-select');
        initializeSelect2('.tecido-select', 'Selecione um tecido');

        // Verificar valores iniciais dos selects de tecido
        tecidoSelects.each(function(index) {});

        // Atualizar cores quando um tecido é alterado
        const debouncedUpdateColors = debounce(updateColorVariations, 400);
        tecidosContainer.on('change', '.tecido-select', function() {
            debouncedUpdateColors();
        });

        // Inicialização

        // Atualizar botões de remover tecido
        updateRemoveTecidoButtons();

        // Carregar cores diretamente com delay maior para garantir que Select2 esteja inicializado
        setTimeout(function() {
            // updateColorVariations(); // COMENTADO para evitar dupla chamada

            // Verificar novamente os tecidos selecionados
            const tecidosSelecionados = [];
            $('.tecido-select').each(function() {
                const val = $(this).val();
                if (val && val !== '') {
                    tecidosSelecionados.push(val);
                }
            });

            if (tecidosSelecionados.length > 0) {
                updateColorVariations(); // Chamar apenas se houver tecidos selecionados
            } else {
                coresContainer.html('<div class="text-center py-4 text-gray-500 italic">Selecione pelo menos um tecido para ver as cores disponíveis.</div>');
            }
        }, 1500);

        // Exibir mensagem ao usuário
        const infoMsg = $('<div class="mt-2 text-xs text-blue-600">Todas as cores disponíveis para os tecidos selecionados serão exibidas automaticamente.</div>');
        coresContainer.parent().append(infoMsg);

    });
</script>
