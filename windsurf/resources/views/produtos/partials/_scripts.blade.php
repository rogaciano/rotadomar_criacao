<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicialização do JavaScript

        // Inicializar Select2 simples (single select)
        $('.select2').select2({
            placeholder: "Selecione uma opção",
            allowClear: true,
            width: '100%'
        });

        // Inicializar Select2 multi-select
        $('.js-select2-multi').select2({
            placeholder: "Selecione uma ou mais opções",
            allowClear: true,
            width: '100%',
            language: 'pt-BR',
            closeOnSelect: false
        });

        // Ajustar estilo do Select2 para combinar com Tailwind
        $('.select2-container--default .select2-selection--single').css({
            'height': '38px',
            'padding': '5px',
            'border-color': 'rgb(209, 213, 219)'
        });

        // Ajustar estilo do Select2 multi para combinar com Tailwind
        $('.select2-container--default .select2-selection--multiple').css({
            'min-height': '38px',
            'border-color': 'rgb(209, 213, 219)'
        });

        // Função para gerar PDF com orientação específica
        function gerarPdf(btn, orientation) {
            if (!btn) return;

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Mostrar indicador de carregamento
                const originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Gerando...';

                // Obter os filtros do formulário
                const formData = new FormData(document.getElementById('filter-form'));
                const queryParams = new URLSearchParams(formData).toString();

                // Gerar o PDF diretamente com force_generate=1 para evitar o erro de contagem
                const pdfUrl = '{{ route("produtos.lista.pdf") }}?' + queryParams + '&force_generate=1&orientation=' + orientation;

                // Abrir o PDF em uma nova aba
                const pdfWindow = window.open(pdfUrl, '_blank');

                // Restaurar o botão após um curto período
                setTimeout(function() {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }, 1500);

                // Verificar se a janela do PDF foi bloqueada pelo navegador
                if (!pdfWindow || pdfWindow.closed || typeof pdfWindow.closed === 'undefined') {
                    alert('Por favor, permita pop-ups para este site para visualizar o PDF.');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            });
        }

        // Gerar PDF Landscape
        gerarPdf(document.getElementById('btn-gerar-pdf-landscape'), 'landscape');

        // Gerar PDF Portrait
        gerarPdf(document.getElementById('btn-gerar-pdf-portrait'), 'portrait');

        // Limpar filtros: função utilitária e bind do botão
        const form = document.getElementById('filter-form');
        const clearButton = document.getElementById('btn-clear-filters');

        function resetFiltersUI() {
            if (!form) return;
            // Limpar inputs de texto e data
            form.querySelectorAll('input[type="text"], input[type="date"]').forEach(function(el) {
                el.value = '';
            });
            // Limpar selects (inclui Select2)
            form.querySelectorAll('select').forEach(function(sel) {
                sel.value = '';
            });
            // Resetar Select2 simples explicitamente
            if (typeof $ !== 'undefined' && $('.select2').length) {
                $('.select2').val(null).trigger('change');
            }
            // Resetar Select2 multi explicitamente
            if (typeof $ !== 'undefined' && $('.js-select2-multi').length) {
                $('.js-select2-multi').val(null).trigger('change');
            }
        }

        // Se a página foi carregada sem query string, garantir que a UI dos filtros esteja limpa
        if (!window.location.search) {
            resetFiltersUI();
        }

        // Ao clicar em Limpar, limpar a UI e submeter o formulário vazio para atualizar a listagem
        if (clearButton) {
            clearButton.addEventListener('click', function(e) {
                e.preventDefault();
                resetFiltersUI();
                const url = this.getAttribute('href');
                // Substitui a entrada no histórico para evitar back que traga filtros antigos
                window.location.replace(url);
            });
        }

        // Sistema de Toggle de Filtros com Filtros Ativos
        const toggleFiltersBtn = document.getElementById('toggle-filters-btn');
        const filtersContainer = document.getElementById('filters-container');
        const activeFiltersSummary = document.getElementById('active-filters-summary');
        const activeFiltersList = document.getElementById('active-filters-list');
        const filterToggleText = document.getElementById('filter-toggle-text');
        const filterIconShow = document.getElementById('filter-icon-show');
        const filterIconHide = document.getElementById('filter-icon-hide');

        // Mapeamento de nomes de filtros para labels amigáveis
        const filterLabels = {
            'referencia': 'Referência',
            'descricao': 'Descrição',
            'concluido': 'Status de Conclusão',
            'status_concluido': 'Status de Conclusão',
            'incluir_excluidos': 'Incluir Excluídos',
            'marca_id': 'Marca',
            'tecido_id': 'Tecido',
            'estilista_id': 'Estilista',
            'grupo_id': 'Grupo',
            'status_id': 'Status',
            'direcionamento_comercial_id': 'Direcionamento Comercial',
            'localizacao_id': 'Localização',
            'localizacao_planejamento_id': 'Localização Planejamento',
            'situacao_id': 'Situação',
            'data_inicio': 'Data Cadastro (De)',
            'data_fim': 'Data Cadastro (Até)',
            'data_prevista_inicio': 'Data Prev. Produção (De)',
            'data_prevista_fim': 'Data Prev. Produção (Até)',
            'data_prevista_faccao_inicio': 'Data Prev. Facção (De)',
            'data_prevista_faccao_fim': 'Data Prev. Facção (Até)'
        };

        // Função para obter o texto de um select pelo valor (suporta arrays)
        function getSelectText(selectId, value) {
            const select = document.getElementById(selectId);
            if (!select) return Array.isArray(value) ? value.join(', ') : value;

            // Se for array, mapear cada valor para o texto correspondente
            if (Array.isArray(value)) {
                return value.map(v => {
                    const option = select.querySelector(`option[value="${v}"]`);
                    return option ? option.textContent.trim() : v;
                }).join(', ');
            }

            const option = select.querySelector(`option[value="${value}"]`);
            return option ? option.textContent.trim() : value;
        }

        // Função para atualizar a lista de filtros ativos
        function updateActiveFilters() {
            const filters = @json($filters ?? []);
            activeFiltersList.innerHTML = '';

            let hasActiveFilters = false;

            Object.keys(filters).forEach(key => {
                const value = filters[key];
                // Verifica se tem valor (string não vazia ou array não vazio)
                const hasValue = Array.isArray(value) ? value.length > 0 : (value && value !== '');

                if (hasValue && key !== 'page') {
                    hasActiveFilters = true;
                    let displayValue = value;

                    // Formatar valor baseado no tipo de filtro
                    if (key === 'concluido') {
                        displayValue = value === '1' ? 'Concluídos' : 'Não Concluídos';
                    } else if (key === 'status_concluido') {
                        const statusMap = {
                            'todos_em_processo': '🔄 Todos em Processo',
                            'concluido': '✅ Concluídos',
                            'nao_concluido': '⏳ Não Concluídos',
                            'sem_movimentacao': '📋 Sem Movimentação'
                        };
                        displayValue = statusMap[value] || value;
                    } else if (key === 'incluir_excluidos') {
                        displayValue = 'Sim';
                    } else if (key.endsWith('_id')) {
                        displayValue = getSelectText(key, value);
                    } else if (key.includes('data_')) {
                        // Formatar datas
                        const date = new Date(value + 'T00:00:00');
                        displayValue = date.toLocaleDateString('pt-BR');
                    } else if (Array.isArray(value)) {
                        displayValue = value.join(', ');
                    }

                    const badge = document.createElement('span');
                    badge.className = 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 shadow-sm';
                    badge.innerHTML = `
                        <span class="font-bold mr-1 text-indigo-900 dark:text-indigo-200">${filterLabels[key] || key}:</span>
                        <span>${displayValue}</span>
                    `;
                    activeFiltersList.appendChild(badge);
                }
            });

            return hasActiveFilters;
        }

        // Função para alternar visibilidade dos filtros
        const initialFiltersVisible = @json($filtersVisible ?? true);

        async function salvarPreferenciaFiltrosVisiveis(visible) {
            try {
                await fetch('{{ route("ui.filters-visibility") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        page_type: 'produtos',
                        filters_visible: !!visible
                    })
                });
            } catch (e) {
            }
        }

        function toggleFilters() {
            const isHidden = filtersContainer.classList.contains('hidden');

            if (isHidden) {
                // Mostrar filtros
                filtersContainer.classList.remove('hidden');
                activeFiltersSummary.classList.add('hidden');
                filterToggleText.textContent = 'Ocultar Filtros';
                filterIconShow.classList.remove('hidden');
                filterIconHide.classList.add('hidden');
                salvarPreferenciaFiltrosVisiveis(true);
            } else {
                // Ocultar filtros
                filtersContainer.classList.add('hidden');
                const hasFilters = updateActiveFilters();
                if (hasFilters) {
                    activeFiltersSummary.classList.remove('hidden');
                }
                filterToggleText.textContent = 'Mostrar Filtros';
                filterIconShow.classList.add('hidden');
                filterIconHide.classList.remove('hidden');
                salvarPreferenciaFiltrosVisiveis(false);
            }
        }

        // Event listener para o botão de toggle
        if (toggleFiltersBtn) {
            toggleFiltersBtn.addEventListener('click', toggleFilters);
        }

        // Restaurar estado dos filtros pelo valor salvo para o usuário
        if (initialFiltersVisible === false) {
            // Ocultar filtros na carga da página
            filtersContainer.classList.add('hidden');
            const hasFilters = updateActiveFilters();
            if (hasFilters) {
                activeFiltersSummary.classList.remove('hidden');
            }
            filterToggleText.textContent = 'Mostrar Filtros';
            filterIconShow.classList.add('hidden');
            filterIconHide.classList.remove('hidden');
        }

        // Atualizar filtros ativos na carga inicial
        updateActiveFilters();

        // Função para destacar campos de filtro com valores preenchidos
        function highlightFilledFilters() {
            const filterForm = document.getElementById('filter-form');
            if (!filterForm) return;

            // Selecionar todos os inputs de texto e data
            const textInputs = filterForm.querySelectorAll('input[type="text"], input[type="date"]');
            textInputs.forEach(field => {
                const hasValue = field.value && field.value.trim() !== '';
                if (hasValue) {
                    field.classList.add('bg-yellow-100', 'border-yellow-400');
                    field.classList.remove('bg-white');
                } else {
                    field.classList.remove('bg-yellow-100', 'border-yellow-400');
                }
            });

            // Selecionar todos os selects (simples e múltiplos)
            const selects = filterForm.querySelectorAll('select');
            selects.forEach(select => {
                let hasValue = false;

                if (select.multiple) {
                    // Select múltiplo - verificar se tem alguma opção selecionada
                    hasValue = select.selectedOptions.length > 0;
                } else {
                    // Select simples - verificar se o valor não é vazio
                    hasValue = select.value !== '' && select.value !== null;
                }

                // Verificar se usa Select2
                const select2Container = select.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    // Aplicar estilo ao container do Select2
                    const selection = select2Container.querySelector('.select2-selection');
                    if (selection) {
                        if (hasValue) {
                            selection.style.backgroundColor = '#fef9c3'; // yellow-100
                            selection.style.borderColor = '#facc15'; // yellow-400
                        } else {
                            selection.style.backgroundColor = '';
                            selection.style.borderColor = '';
                        }
                    }
                } else {
                    // Select normal sem Select2
                    if (hasValue) {
                        select.classList.add('bg-yellow-100', 'border-yellow-400');
                        select.classList.remove('bg-white');
                    } else {
                        select.classList.remove('bg-yellow-100', 'border-yellow-400');
                    }
                }
            });

            // Destacar checkboxes marcados
            const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const label = checkbox.closest('div');
                if (checkbox.checked && label) {
                    label.classList.add('bg-yellow-100', 'rounded', 'px-2', 'py-1');
                } else if (label) {
                    label.classList.remove('bg-yellow-100', 'rounded', 'px-2', 'py-1');
                }
            });
        }

        // Chamar função de destaque na inicialização (com delay para Select2 carregar)
        setTimeout(highlightFilledFilters, 100);

        // Atualizar destaque quando campos mudarem
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.addEventListener('change', highlightFilledFilters);
            filterForm.addEventListener('input', highlightFilledFilters);
        }

        // Escutar eventos do Select2
        $(document).on('select2:select select2:unselect select2:clear', function() {
            highlightFilledFilters();
        });

    });
</script>
