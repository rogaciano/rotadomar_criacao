<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Etapas de Produção') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('etapas-producao.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Etapa
                </a>
                
                <button onclick="openFlowModal()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Visualizar Fluxo
                </button>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                        <form action="{{ route('etapas-producao.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Buscar por nome</label>
                                <input type="text" name="nome" id="nome" value="{{ request('nome') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite o nome da etapa">
                            </div>

                            <div>
                                <label for="ativo" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="ativo" id="ativo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                <a href="{{ route('etapas-producao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ordem
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cor / Ícone
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transições
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($etapas as $etapa)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $etapa->ordem }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($etapa->icone)
                                                    <span class="mr-2 text-lg">{{ $etapa->icone }}</span>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $etapa->nome }}</div>
                                                    @if($etapa->descricao)
                                                        <div class="text-xs text-gray-500">{{ Str::limit($etapa->descricao, 40) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $corClasses = [
                                                    'blue' => 'bg-blue-100 text-blue-800',
                                                    'green' => 'bg-green-100 text-green-800',
                                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                                    'red' => 'bg-red-100 text-red-800',
                                                    'purple' => 'bg-purple-100 text-purple-800',
                                                    'gray' => 'bg-gray-100 text-gray-800',
                                                    'indigo' => 'bg-indigo-100 text-indigo-800',
                                                    'pink' => 'bg-pink-100 text-pink-800',
                                                    'orange' => 'bg-orange-100 text-orange-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $corClasses[$etapa->cor] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $etapa->icone ?? '●' }} {{ ucfirst($etapa->cor) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @php
                                                $transicoes = $etapa->transicoesOrigem()->with('etapaDestino')->get();
                                            @endphp
                                            @if($transicoes->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($transicoes->take(3) as $transicao)
                                                        <span class="px-2 py-0.5 text-xs bg-gray-200 text-gray-700 rounded">
                                                            → {{ $transicao->etapaDestino->nome ?? '?' }}
                                                        </span>
                                                    @endforeach
                                                    @if($transicoes->count() > 3)
                                                        <span class="text-xs text-gray-500">+{{ $transicoes->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $etapa->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $etapa->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex item-center justify-center">
                                                <a href="{{ route('etapas-producao.show', $etapa->id) }}" class="text-blue-600 hover:text-blue-900 bg-transparent p-1 rounded-full hover:bg-blue-100 transition-all" title="Ver detalhes">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                
                                                <a href="{{ route('etapas-producao.edit', $etapa->id) }}" class="text-amber-600 hover:text-amber-900 bg-transparent p-1 rounded-full hover:bg-amber-100 transition-all" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                
                                                <form action="{{ route('etapas-producao.destroy', $etapa) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent p-1 rounded-full hover:bg-red-100 transition-all" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta etapa?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 px-6 text-center text-gray-500">Nenhuma etapa de produção encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $etapas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização do Fluxo com Mermaid.js -->
    <div id="flowModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-5 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-lg bg-white mb-10">
            <div class="flex justify-between items-center pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Fluxo de Etapas de Produção
                </h3>
                <button onclick="closeFlowModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4">
                <!-- Container do Diagrama Mermaid -->
                <div class="overflow-auto bg-white rounded-lg p-4 min-h-[400px] max-h-[70vh] flex flex-col items-center" id="flowContainer">
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
                                $nome = str_replace('"', "'", $icone . $etapa->nome);
                                
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

            <div class="mt-4 pt-4 border-t flex justify-end gap-3">
                <button onclick="closeFlowModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Mermaid.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        // Inicializar Mermaid
        mermaid.initialize({
            startOnLoad: false,
            theme: 'default',
            flowchart: {
                useMaxWidth: true,
                htmlLabels: true,
                curve: 'basis',
                padding: 20
            },
            securityLevel: 'loose'
        });

        function openFlowModal() {
            document.getElementById('flowModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Re-renderizar o diagrama Mermaid
            const mermaidDiv = document.getElementById('mermaidDiagram');
            if (mermaidDiv && !mermaidDiv.querySelector('svg')) {
                mermaid.run({
                    nodes: [mermaidDiv]
                });
            }
        }

        function closeFlowModal() {
            document.getElementById('flowModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Fechar ao clicar fora do modal
        document.getElementById('flowModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFlowModal();
            }
        });

        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('flowModal').classList.contains('hidden')) {
                closeFlowModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
