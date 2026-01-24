<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Fluxo de Etapas com Quantidades nas Facções') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('etapas-producao.visualizar-fluxo') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    Ver Fluxo Processo
                </a>
                <a href="{{ route('etapas-producao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para Listagem
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Container do Diagrama Mermaid -->
                    <div class="overflow-auto bg-white rounded-lg p-4 min-h-[600px] flex flex-col items-center" id="flowContainer">
                        @if(isset($etapasFluxo) && $etapasFluxo->count() > 0)
                            @php
                                // Gerar a sintaxe Mermaid
                                $mermaidCode = "graph TD\n";
                                
                                // Cores hex para estilos
                                $corHex = [
                                    'blue' => '#3B82F6',
                                    'green' => '#22C55E',
                                    'yellow' => '#FACC15',
                                    'red' => '#EF4444',
                                    'purple' => '#A855F7',
                                    'gray' => '#6B7280',
                                    'indigo' => '#6366F1',
                                    'pink' => '#EC4899',
                                    'orange' => '#F97316',
                                ];
                                
                                // Identificar primeira e últimas etapas
                                $primeiraOrdem = $etapasFluxo->min('ordem');
                                $etapasFinaisIds = $etapasFluxo->filter(fn($e) => $e->transicoesOrigem->count() == 0)->pluck('id')->toArray();
                                
                                // Criar nós
                                foreach ($etapasFluxo as $etapa) {
                                    $nodeId = 'E' . $etapa->id;
                                    $icone = $etapa->icone ? $etapa->icone . ' ' : '';
                                    $quantidade = number_format($etapa->quantidade_produtos ?? 0, 0, ',', '.');
                                    $nomeRaw = $icone . $etapa->nome;
                                    $nomeLabel = "{$nomeRaw}<br/>📦 Qtd: {$quantidade}";
                                    $nome = str_replace('"', "'", $nomeLabel);
                                    
                                    // Usar formato especial para primeira e última etapa
                                    if ($etapa->ordem == $primeiraOrdem) {
                                        $mermaidCode .= "    {$nodeId}([\"🚀 {$nome}\"])\n";
                                    } elseif (in_array($etapa->id, $etapasFinaisIds)) {
                                        $mermaidCode .= "    {$nodeId}[[\"🏁 {$nome}\"]]\n";
                                    } else {
                                        $mermaidCode .= "    {$nodeId}[\"{$nome}\"]\n";
                                    }
                                }
                                
                                $mermaidCode .= "\n";
                                
                                // Criar conexões
                                foreach ($etapasFluxo as $etapa) {
                                    $origemId = 'E' . $etapa->id;
                                    foreach ($etapa->transicoesOrigem as $transicao) {
                                        if ($transicao->etapaDestino) {
                                            $destinoId = 'E' . $transicao->etapaDestino->id;
                                            $label = str_replace('"', "'", $transicao->label_botao ?: '');
                                            
                                            if ($label) {
                                                $mermaidCode .= "    {$origemId} -->|\"{$label}\"| {$destinoId}\n";
                                            } else {
                                                $mermaidCode .= "    {$origemId} --> {$destinoId}\n";
                                            }
                                        }
                                    }
                                }
                                
                                $mermaidCode .= "\n";
                                
                                // Aplicar estilos por cor
                                foreach ($etapasFluxo as $etapa) {
                                    $nodeId = 'E' . $etapa->id;
                                    $cor = $corHex[$etapa->cor] ?? '#6B7280';
                                    $textColor = in_array($etapa->cor, ['yellow']) ? '#1F2937' : '#FFFFFF';
                                    $mermaidCode .= "    style {$nodeId} fill:{$cor},stroke:{$cor},color:{$textColor}\n";
                                }
                            @endphp
                            
                            <div class="mermaid" id="mermaidDiagram">
{{ $mermaidCode }}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-[400px] text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium">Nenhuma etapa de produção ativa encontrada</p>
                                <p class="text-sm">Adicione etapas para visualizar o fluxo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Mermaid.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        // Inicializar Mermaid
        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            flowchart: {
                useMaxWidth: false,
                htmlLabels: true,
                curve: 'basis',
                padding: 40
            },
            securityLevel: 'loose'
        });
    </script>
    @endpush
</x-app-layout>
