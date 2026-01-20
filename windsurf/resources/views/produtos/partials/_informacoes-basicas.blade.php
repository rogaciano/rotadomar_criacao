<!-- Informações do Produto -->
<div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Básicas</h3>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-grow space-y-8">
            <!-- Identificação e Estilo -->
            <div>
                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Identificação e Estilo
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-5 gap-x-8">
                    <div class="sm:col-span-1">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Referência</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-black text-gray-900">{{ $produto->referencia }}</span>
                            @if($produto->isReprogramacao())
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-orange-100 text-orange-700">#{{ str_pad($produto->numero_reprogramacao, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="sm:col-span-1 lg:col-span-2">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Descrição</span>
                        <span class="block mt-1 text-sm font-semibold text-gray-900 leading-tight">{{ $produto->descricao }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Marca</span>
                        <span class="block mt-1 text-sm font-medium text-gray-800">{{ $produto->marca->nome_marca ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Estilista</span>
                        <span class="block mt-1 text-sm font-medium text-gray-800">{{ $produto->estilista->nome_estilista ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Grupo</span>
                        <span class="block mt-1 text-sm font-medium text-gray-800">{{ $produto->grupoProduto->descricao ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Comercial e Produção -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <h4 class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Comercial e Produção
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-y-6 gap-x-4">
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Quantidade</span>
                        <span class="block mt-1 text-base font-black text-gray-900 tracking-tighter">{{ number_format($produto->quantidade, 0, ',', '.') }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Status</span>
                        <div class="mt-1">
                            <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ $produto->status ? $produto->status->descricao : 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Localização Atual</span>
                        <div class="mt-1">
                            @if($produto->localizacao_atual)
                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                    {{ $produto->localizacao_atual->nome_localizacao }}
                                </span>
                            @else
                                <span class="text-gray-400 text-[10px] italic">Não localizado</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Direcionamento</span>
                        <span class="block mt-1 text-xs font-bold text-gray-700 uppercase">{{ $produto->direcionamentoComercial ? $produto->direcionamentoComercial->descricao : 'Sem info' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Preço Atacado</span>
                        <span class="block mt-1 text-sm font-bold text-gray-900">R$ {{ number_format($produto->preco_atacado, 2, ',', '.') }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Preço Varejo</span>
                        <span class="block mt-1 text-sm font-bold text-gray-900">R$ {{ number_format($produto->preco_varejo, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Cronograma e Datas -->
            <div>
                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Cronograma e Registro
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Data de Cadastro</span>
                        <span class="block mt-1 text-xs font-semibold text-gray-700">{{ $produto->data_cadastro ? $produto->data_cadastro->format('d/m/Y') : '—' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Produção Prevista</span>
                        <span class="block mt-1 text-xs font-bold text-purple-700">{{ $produto->data_prevista_producao_mes_ano ?: '—' }}</span>
                    </div>

                    <div class="col-span-1">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Criado em</span>
                        <span class="block mt-1 text-[11px] text-gray-600">{{ $produto->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="col-span-1">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Última Atualização</span>
                        <span class="block mt-1 text-[11px] text-gray-600">{{ $produto->updated_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if($produto->deleted_at)
                        <div class="col-span-2">
                            <span class="block text-[10px] font-bold text-red-400 uppercase tracking-tight">Excluído em</span>
                            <span class="block mt-1 text-xs font-bold text-red-600">{{ $produto->deleted_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($produto->foto_principal)
        <div class="lg:w-80 shrink-0">
            <span class="block text-sm font-medium text-gray-500 mb-2">Foto Principal</span>
            <div class="relative group">
                <img src="{{ asset('storage/' . $produto->foto_principal) }}"
                     alt="Foto do Produto"
                     class="w-full rounded-lg shadow-lg border-2 border-white object-cover cursor-pointer hover:scale-[1.02] transition-all duration-300"
                     style="aspect-ratio: 3/4;"
                     onclick="this.style.transform = (this.style.transform.includes('rotate(90deg)') ? 'rotate(0deg)' : 'rotate(90deg)')">
                <div class="absolute top-2 right-2 bg-white/80 rounded-full p-1.5 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
