<!-- Modal Nova Observação -->
<div id="modal-nova-observacao" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4 pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Nova Observação</h3>
            <button onclick="fecharModalObservacao()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="form-observacao" onsubmit="salvarObservacao(event)">
            @csrf
            <input type="hidden" name="produto_id" value="{{ $produto->id }}">

            <div class="mb-4">
                <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">Observação *</label>

                <!-- Editor Quill -->
                <div id="editor-container" style="height: 75px; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem;"></div>
                <textarea
                    id="observacao"
                    name="observacao"
                    style="display: none;"
                ></textarea>

                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <p>💡 Use a barra de ferramentas acima para formatar o texto com cores, negrito, etc.</p>
                    <p class="mt-1" id="char-counter">
                        <span id="char-count">0</span> / 255 caracteres
                    </p>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="fecharModalObservacao()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    Salvar Observação
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Carrossel de Imagens das Movimentações -->
@php
    $movimentacoesComAnexo = $movimentacoes->filter(function($mov) {
        return !empty($mov->anexo);
    });
@endphp

@if($movimentacoesComAnexo->count() > 0)
<div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagens das Movimentações</h3>

    <div class="relative">
        <!-- Carrossel de imagens -->
        <div class="carousel-container overflow-hidden">
            <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                @foreach($movimentacoesComAnexo as $index => $movimentacao)
                    <div class="carousel-item min-w-full flex flex-col items-center justify-center" data-index="{{ $index }}">
                        <div class="relative w-full max-w-2xl">
                            <img src="{{ $movimentacao->anexo_url }}" alt="Anexo da movimentação" class="w-full h-auto max-h-96 object-contain rounded-lg shadow-md">
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-2 rounded-b-lg">
                                <p class="text-sm">{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }} -
                                {{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }} -
                                {{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Controles do carrossel -->
        @if($movimentacoesComAnexo->count() > 1)
            <button class="carousel-control prev absolute top-1/2 left-2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="carousel-control next absolute top-1/2 right-2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        @endif

        <!-- Indicadores do carrossel -->
        @if($movimentacoesComAnexo->count() > 1)
            <div class="carousel-indicators flex justify-center mt-4">
                @foreach($movimentacoesComAnexo as $index => $movimentacao)
                    <button class="carousel-indicator w-3 h-3 rounded-full mx-1 {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-300' }}" data-index="{{ $index }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif

<!-- Modal de Reprogramação -->
<div id="modal-reprogramar" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="relative p-4 border shadow-lg rounded-md bg-white" style="width: 400px; max-width: 90vw;">
        <div class="mt-1">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold text-gray-900">Reprogramar Produto</h3>
                <button onclick="document.getElementById('modal-reprogramar').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-2 px-2 py-2">
                <div class="bg-orange-50 border-l-4 border-orange-400 p-2 mb-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-700">
                                <strong>Atenção!</strong> Será criado um novo produto baseado em:
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-2 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-700">Original:</span>
                        <span class="text-gray-900 font-semibold">{{ $produto->referencia }}</span>
                    </div>
                    @php
                        $ultimaReprogramacao = $produto->reprogramacoes()->max('numero_reprogramacao') ?? 0;
                        $proximoNumero = $ultimaReprogramacao + 1;
                        $novaReferencia = $produto->referencia . '-' . str_pad($proximoNumero, 2, '0', STR_PAD_LEFT);
                    @endphp
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-700">Nova (sugerida):</span>
                        <span class="text-green-600 font-bold" id="preview-referencia">{{ $novaReferencia }}</span>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-3">
                    <label for="numero_reprogramacao_manual" class="block text-xs font-semibold text-yellow-800 mb-1">
                        Número de Reprogramação (opcional)
                    </label>
                    <input
                        type="number"
                        id="numero_reprogramacao_manual"
                        name="numero_reprogramacao_manual"
                        min="1"
                        max="99"
                        placeholder="{{ $proximoNumero }}"
                        class="w-full text-xs border-yellow-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"
                        onkeyup="atualizarPreviewReferencia({{ Js::from($produto->referencia) }}, {{ $proximoNumero }})"
                    >
                    <p class="text-xs text-yellow-700 mt-1">
                        Deixe em branco para usar o número sugerido ({{ $proximoNumero }}). Use este campo apenas para reprogramações iniciadas em sistemas antigos.
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded p-2 mb-2">
                    <p class="text-xs text-blue-800 mb-1 font-semibold">✔️ Será copiado:</p>
                    <ul class="text-xs text-blue-700 space-y-0.5 ml-3">
                        <li>• Dados, tecidos, observações, anexos, cores</li>
                    </ul>
                </div>

                <div class="bg-red-50 border border-red-200 rounded p-2 mb-2">
                    <p class="text-xs text-red-800 mb-1 font-semibold">❌ NÃO será copiado:</p>
                    <ul class="text-xs text-red-700 space-y-0.5 ml-3">
                        <li>• Localizações e movimentações</li>
                    </ul>
                </div>

                <p class="text-xs text-gray-600 mb-2">
                    <strong>Obs:</strong> Produto reprogramado <strong class="text-red-600">não</strong> pode ser reprogramado novamente.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 px-2 py-2 bg-gray-50 rounded-b">
                <button onclick="document.getElementById('modal-reprogramar').classList.add('hidden')" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs font-medium rounded hover:bg-gray-300">
                    Cancelar
                </button>
                <form id="form-reprogramar" action="{{ route('produtos.reprogramar', $produto->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="numero_reprogramacao" id="numero_reprogramacao_hidden">
                    <button type="submit" onclick="capturarNumeroReprogramacao()" class="px-3 py-1.5 bg-orange-500 text-white text-xs font-medium rounded hover:bg-orange-600">
                        Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data Entrega Facção -->
<div id="modal-data-entrega" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-data-entrega-titulo">Data de Entrega</h3>
            <form id="form-data-entrega" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="data_entrega_faccao" class="block text-sm font-medium text-gray-700 mb-2">Selecione a Data</label>
                    <input type="date" name="data_entrega_faccao" id="input_data_entrega_faccao"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="fecharModalDataEntrega()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carouselInner = document.querySelector('.carousel-inner');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const prevButton = document.querySelector('.carousel-control.prev');
        const nextButton = document.querySelector('.carousel-control.next');
        const indicators = document.querySelectorAll('.carousel-indicator');

        if (!carouselInner || carouselItems.length === 0) return;

        let currentIndex = 0;
        const itemCount = carouselItems.length;

        function updateCarousel() {
            const translateValue = -currentIndex * 100 + '%';
            carouselInner.style.transform = 'translateX(' + translateValue + ')';

            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-blue-600');
                } else {
                    indicator.classList.remove('bg-blue-600');
                    indicator.classList.add('bg-gray-300');
                }
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + itemCount) % itemCount;
                updateCarousel();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % itemCount;
                updateCarousel();
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                currentIndex = index;
                updateCarousel();
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggers = document.querySelectorAll('.tooltip-trigger');

        tooltipTriggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', function(e) {
                const tooltipContent = this.closest('.tooltip-container').querySelector('.tooltip-content');

                const triggerRect = this.getBoundingClientRect();

                tooltipContent.style.position = 'fixed';
                tooltipContent.style.zIndex = '9999';
                tooltipContent.style.top = (triggerRect.top - 15) + 'px';
                tooltipContent.style.left = (triggerRect.left + (triggerRect.width / 2)) + 'px';
                tooltipContent.style.transform = 'translate(-50%, -100%)';

                tooltipContent.classList.remove('hidden');

                const tooltipRect = tooltipContent.getBoundingClientRect();
                if (tooltipRect.top < 10) {
                    tooltipContent.style.top = (triggerRect.bottom + 15) + 'px';
                    tooltipContent.style.transform = 'translate(-50%, 0)';

                    const arrow = tooltipContent.querySelector('div[class*="absolute"]');
                    if (arrow) {
                        arrow.style.top = '-5px';
                        arrow.style.bottom = 'auto';
                    }
                }
            });

            trigger.addEventListener('mouseleave', function() {
                const tooltipContent = this.closest('.tooltip-container').querySelector('.tooltip-content');
                tooltipContent.classList.add('hidden');
            });
        });
    });
</script>

<script>
    let quillEditor = null;

    function abrirModalObservacao() {
        document.getElementById('modal-nova-observacao').classList.remove('hidden');

        if (!quillEditor) {
            quillEditor = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Digite sua observação aqui...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'color': ['#DC2626', '#2563EB', '#16A34A', '#CA8A04', '#EA580C', '#9333EA', '#DB2777', '#000000'] }],
                        [{ 'background': ['#FEE2E2', '#DBEAFE', '#D1FAE5', '#FEF3C7', '#FFEDD5', '#F3E8FF', '#FCE7F3', '#FFFFFF'] }],
                        ['clean']
                    ]
                }
            });

            quillEditor.on('text-change', function() {
                const html = quillEditor.root.innerHTML;
                const htmlLength = html.length;

                const charCountEl = document.getElementById('char-count');
                const charCounterEl = document.getElementById('char-counter');
                charCountEl.textContent = htmlLength;

                if (htmlLength > 255) {
                    charCounterEl.classList.add('text-red-600', 'font-semibold');
                    charCounterEl.classList.remove('text-gray-500');
                } else {
                    charCounterEl.classList.remove('text-red-600', 'font-semibold');
                    charCounterEl.classList.add('text-gray-500');
                }

                document.getElementById('observacao').value = html;
            });
        } else {
            quillEditor.setText('');
        }

        document.getElementById('char-count').textContent = '0';
        document.getElementById('char-counter').classList.remove('text-red-600', 'font-semibold');
        document.getElementById('char-counter').classList.add('text-gray-500');
    }

    function fecharModalObservacao() {
        document.getElementById('modal-nova-observacao').classList.add('hidden');
        if (quillEditor) {
            quillEditor.setText('');
        }
        document.getElementById('form-observacao').reset();
    }

    document.getElementById('modal-nova-observacao')?.addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalObservacao();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modal-nova-observacao');
            if (modal && !modal.classList.contains('hidden')) {
                fecharModalObservacao();
            }
        }
    });

    async function salvarObservacao(event) {
        event.preventDefault();

        console.log('Função salvarObservacao chamada');

        if (quillEditor) {
            const html = quillEditor.root.innerHTML;
            document.getElementById('observacao').value = html;
            console.log('HTML do Quill:', html);
        }

        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        const observacaoValue = document.getElementById('observacao').value;
        const htmlLength = observacaoValue.length;

        const text = quillEditor ? quillEditor.getText() : observacaoValue;
        const textoLimpo = text.replace(/\n$/, '').trim();

        console.log('HTML:', observacaoValue);
        console.log('Tamanho do HTML:', htmlLength);
        console.log('Texto limpo:', textoLimpo);
        console.log('Produto ID:', document.querySelector('input[name="produto_id"]').value);

        if (!textoLimpo || textoLimpo === '') {
            alert('Por favor, digite uma observação.');
            return;
        }

        if (htmlLength > 255) {
            alert('A observação não pode ter mais de 255 caracteres (incluindo formatação). Atualmente: ' + htmlLength + ' caracteres.\n\nDica: Use menos formatação de cores/fundos para reduzir o tamanho.');
            return;
        }

        const formData = new FormData();
        formData.append('produto_id', document.querySelector('input[name="produto_id"]').value);
        formData.append('observacao', observacaoValue);
        formData.append('_token', '{{ csrf_token() }}');

        submitButton.disabled = true;
        submitButton.textContent = 'Salvando...';

        try {
            const response = await fetch('{{ route("produtos.observacoes.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            console.log('Resposta do servidor:', data);

            if (response.ok && data.success) {
                alert('Observação adicionada com sucesso!');
                window.location.reload();
            } else {
                let errorMessage = 'Erro ao salvar observação.';

                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }

                alert(errorMessage);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao salvar observação. Por favor, tente novamente.');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    async function removerObservacao(id) {
        if (!confirm('Tem certeza que deseja remover esta observação?')) {
            return;
        }

        try {
            const response = await fetch(`/produtos/observacoes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('Observação removida com sucesso!');
                window.location.reload();
            } else {
                alert('Erro ao remover observação: ' + (data.message || 'Erro desconhecido'));
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao remover observação. Por favor, tente novamente.');
        }
    }
</script>

<script>
    function atualizarPreviewReferencia(referenciaBase, numeroSugerido) {
        const inputNumero = document.getElementById('numero_reprogramacao_manual');
        const previewElement = document.getElementById('preview-referencia');

        let numero = parseInt(inputNumero.value);

        if (!numero || numero < 1 || numero > 99) {
            numero = numeroSugerido;
        }

        const numeroFormatado = numero.toString().padStart(2, '0');
        const novaReferencia = referenciaBase + '-' + numeroFormatado;

        previewElement.textContent = novaReferencia;
    }

    function capturarNumeroReprogramacao() {
        const inputNumero = document.getElementById('numero_reprogramacao_manual');
        const hiddenInput = document.getElementById('numero_reprogramacao_hidden');

        if (inputNumero.value && parseInt(inputNumero.value) > 0) {
            hiddenInput.value = inputNumero.value;
        }
    }
</script>

<script>
    function abrirModalDataEntrega(produtoLocalizacaoId, dataAtual, nomeLocalizacao) {
        const modal = document.getElementById('modal-data-entrega');
        const form = document.getElementById('form-data-entrega');
        const input = document.getElementById('input_data_entrega_faccao');
        const titulo = document.getElementById('modal-data-entrega-titulo');

        titulo.textContent = 'Data de Entrega: ' + nomeLocalizacao;
        input.value = dataAtual;

        let url = "{{ route('produtos.localizacoes.update-data-entrega', [$produto->id, 'PLACEHOLDER']) }}";
        form.action = url.replace('PLACEHOLDER', produtoLocalizacaoId);

        modal.classList.remove('hidden');
    }

    function fecharModalDataEntrega() {
        document.getElementById('modal-data-entrega').classList.add('hidden');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollPos = sessionStorage.getItem('scrollPos');
        if (scrollPos) {
            setTimeout(() => {
                window.scrollTo({
                    top: parseInt(scrollPos),
                    behavior: 'instant'
                });
                sessionStorage.removeItem('scrollPos');
            }, 50);
        }

        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('scrollPos', window.scrollY);
            });
        });
    });
</script>

@push('styles')
<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<!-- Quill.js JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush
