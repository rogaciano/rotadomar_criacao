<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicialização do JavaScript

        // Configuração padrão para Select2 multiselect
        const select2Config = {
            allowClear: true,
            closeOnSelect: false,
            width: '100%'
        };

        // Inicializar Select2 multiselect nos filtros com placeholders personalizados
        $('#grupo_produto_id').select2({
            ...select2Config,
            placeholder: "Selecione grupos de produto"
        });

        $('#localizacao_id').select2({
            ...select2Config,
            placeholder: "Selecione localizações"
        });

        $('#situacao_id').select2({
            ...select2Config,
            placeholder: "Selecione situações"
        });

        $('#tecido_id').select2({
            ...select2Config,
            placeholder: "Selecione tecidos"
        });

        $('#direcionamento_comercial_id').select2({
            ...select2Config,
            placeholder: "Selecione direcionamentos"
        });

        // Limpar o campo de busca após selecionar um item (comportamento melhorado)
        $('.select2-multi').on('select2:select', function(e) {
            // Limpar o texto de busca após a seleção
            const $select = $(this);
            setTimeout(function() {
                $select.data('select2').$container.find('.select2-search__field').val('');
            }, 1);
        });

        // Ajustar estilo do Select2 para combinar com Tailwind
        $('.select2-container--default .select2-selection--single').css({
            'height': '38px',
            'padding': '5px 0',
            'border-color': 'rgb(209, 213, 219)'
        });

        // Limpar filtros: função utilitária e bind do botão
        const form = document.getElementById('filters-form');
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
            // Resetar Select2 explicitamente
            if (typeof $ !== 'undefined') {
                $('.select2-multi').val(null).trigger('change');
            }
        }

        // Se a página foi carregada sem query string, garantir que a UI dos filtros esteja limpa
        if (!window.location.search) {
            resetFiltersUI();
        }

        // Ao clicar em Limpar, limpar a UI e navegar para a rota base (sem parâmetros)
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
                        page_type: 'movimentacoes',
                        filters_visible: !!visible
                    })
                });
            } catch (e) {
            }
        }

        // Mapeamento de nomes de filtros para labels amigáveis
        const filterLabels = {
            'referencia': 'Referência',
            'produto': 'Produto',
            'produto_id': 'Produto ID',
            'tipo_id': 'Tipo',
            'situacao_id': 'Situação',
            'localizacao_id': 'Localização',
            'marca_id': 'Marca',
            'status_id': 'Status',
            'tecido_id': 'Tecido',
            'grupo_produto_id': 'Grupo de Produto',
            'direcionamento_comercial_id': 'Direcionamento Comercial',
            'data_inicio': 'Data (De)',
            'data_fim': 'Data (Até)',
            'comprometido': 'Comprometido',
            'concluido': 'Concluído',
            'status_dias': 'Status de Dias'
        };

        // Função para obter o texto de um select pelo valor
        function getSelectText(selectId, value) {
            const select = document.getElementById(selectId);
            if (!select) return value;
            const option = select.querySelector(`option[value="${value}"]`);
            return option ? option.textContent.trim() : value;
        }

        // Função para atualizar a lista de filtros ativos
        function updateActiveFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            activeFiltersList.innerHTML = '';

            let hasActiveFilters = false;

            urlParams.forEach((value, key) => {
                if (value && value !== '' && key !== 'page') {
                    hasActiveFilters = true;
                    let displayValue = value;

                    // Formatar valor baseado no tipo de filtro
                    if (key === 'comprometido' || key === 'concluido') {
                        displayValue = value === '1' ? 'Sim' : 'Não';
                    } else if (key === 'status_dias') {
                        displayValue = value === 'atrasados' ? 'Atrasados' : (value === 'em_dia' ? 'Em Dia' : value);
                    } else if (key.endsWith('_id') || key.endsWith('_id[]')) {
                        // Lidar com arrays (multiselect)
                        const cleanKey = key.replace('[]', '');
                        if (Array.isArray(value)) {
                            displayValue = value.map(v => getSelectText(cleanKey, v)).join(', ');
                        } else {
                            displayValue = getSelectText(cleanKey, value);
                        }
                    } else if (key.includes('data_')) {
                        // Formatar datas
                        try {
                            const date = new Date(value + 'T00:00:00');
                            displayValue = date.toLocaleDateString('pt-BR');
                        } catch (e) {
                            displayValue = value;
                        }
                    }

                    const badge = document.createElement('span');
                    badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200';
                    badge.innerHTML = `
                        <span class="font-semibold mr-1">${filterLabels[key] || key}:</span>
                        <span>${displayValue}</span>
                    `;
                    activeFiltersList.appendChild(badge);
                }
            });

            return hasActiveFilters;
        }

        // Função para alternar visibilidade dos filtros
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
            const filterForm = document.getElementById('filters-form');
            if (!filterForm) return;

            // Selecionar todos os inputs de texto e data
            const textInputs = filterForm.querySelectorAll('input[type="text"], input[type="date"]');
            textInputs.forEach(field => {
                const hasValue = field.value && field.value.trim() !== '';
                if (hasValue) {
                    field.classList.add('bg-yellow-100', 'border-yellow-400', 'dark:bg-yellow-900/40', 'dark:border-yellow-600');
                    field.classList.remove('bg-white', 'dark:bg-slate-800');
                } else {
                    field.classList.remove('bg-yellow-100', 'border-yellow-400', 'dark:bg-yellow-900/40', 'dark:border-yellow-600');
                    field.classList.add('bg-white', 'dark:bg-slate-800');
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
                            selection.style.backgroundColor = '#fef9c3'; // yellow-100 (light mode)
                            selection.style.borderColor = '#facc15'; // yellow-400
                            // Note: Select2 inline styles might need !important logic or specific dark mode handling if extracted to CSS class
                        } else {
                            selection.style.backgroundColor = '';
                            selection.style.borderColor = '';
                        }
                    }
                } else {
                    // Select normal sem Select2
                    if (hasValue) {
                        select.classList.add('bg-yellow-100', 'border-yellow-400', 'dark:bg-yellow-900/40', 'dark:border-yellow-600');
                        select.classList.remove('bg-white', 'dark:bg-slate-800');
                    } else {
                        select.classList.remove('bg-yellow-100', 'border-yellow-400', 'dark:bg-yellow-900/40', 'dark:border-yellow-600');
                        select.classList.add('bg-white', 'dark:bg-slate-800');
                    }
                }
            });

            // Destacar checkboxes marcados
            const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const label = checkbox.closest('div');
                if (checkbox.checked && label) {
                    label.classList.add('bg-yellow-100', 'dark:bg-yellow-900/40', 'rounded', 'px-2', 'py-1');
                } else if (label) {
                    label.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/40', 'rounded', 'px-2', 'py-1');
                }
            });
        }

        // Chamar função de destaque na inicialização (com delay para Select2 carregar)
        setTimeout(highlightFilledFilters, 100);

        // Atualizar destaque quando campos mudarem
        const filtersForm = document.getElementById('filters-form');
        if (filtersForm) {
            filtersForm.addEventListener('change', highlightFilledFilters);
            filtersForm.addEventListener('input', highlightFilledFilters);
        }

        // Escutar eventos do Select2
        $(document).on('select2:select select2:unselect select2:clear', function() {
            highlightFilledFilters();
        });

        // Mini-modal de observações (clicável)
        window.openObsPopup = function(btn, movimentacaoId) {
            // Fechar qualquer popup aberto
            closeObsPopup();

            const template = document.getElementById('obs-text-' + movimentacaoId);
            if (!template) return;

            const obsText = template.innerHTML.trim();

            // Criar overlay
            const overlay = document.createElement('div');
            overlay.id = 'obs-popup-overlay';
            overlay.className = 'fixed inset-0 bg-black/40 z-[9998]';
            overlay.addEventListener('click', closeObsPopup);

            // Criar popup
            const popup = document.createElement('div');
            popup.id = 'obs-popup';
            popup.className = 'fixed z-[9999] bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-600 p-0 max-w-md w-[90vw]';
            popup.style.top = '50%';
            popup.style.left = '50%';
            popup.style.transform = 'translate(-50%, -50%)';

            popup.innerHTML = `
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                        </svg>
                        Observações
                    </h4>
                    <button onclick="closeObsPopup()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line max-h-[60vh] overflow-y-auto leading-relaxed">${obsText}</div>
            `;

            document.body.appendChild(overlay);
            document.body.appendChild(popup);
        };

        window.closeObsPopup = function() {
            const overlay = document.getElementById('obs-popup-overlay');
            const popup = document.getElementById('obs-popup');
            if (overlay) overlay.remove();
            if (popup) popup.remove();
        };

        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeObsPopup();
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
