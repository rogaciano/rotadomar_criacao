<!-- Variações de Cores -->
<div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Variações de Cores</h3>

    @php
       // Calcular totais para uso em toda a tabela
       $totalCores = collect($coresEnriquecidas)->sum('quantidade');
       $totalCombinacoes = $produto->combinacoes ? $produto->combinacoes->sum('quantidade_pretendida') : 0;
       $totalGeral = $totalCores + $totalCombinacoes;
       $quantidadeProduto = $produto->quantidade ?? 0;
       $isEqual = $totalGeral == $quantidadeProduto;
    @endphp

    @if($produto->cores->count() > 0)
       <!-- Vista Desktop -->
       <div class="hidden lg:block overflow-x-auto">
           <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                   <tr>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque (m)</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                           <div class="flex items-center">
                               <span>Necessidade Geral(m)</span>
                               <div class="tooltip-container ml-1" style="position: relative;">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 hover:text-gray-600 cursor-help tooltip-trigger" viewBox="0 0 20 20" fill="currentColor">
                                       <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                   </svg>
                                   <div class="tooltip-content fixed z-[9999] w-64 p-2 bg-black text-xs rounded-lg hidden" style="color: white !important; box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                                       Necessidade total de todos os produtos que usam esta cor de tecido.
                                       <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-black rotate-45"></div>
                                   </div>
                               </div>
                           </div>
                       </th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumo deste Produto (m)</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo (m)</th>
                       <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produção Possível</th>
                   </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                   @foreach($coresEnriquecidas as $cor)
                       <tr>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                               <div class="flex items-center">
                                   @if($cor['codigo_cor'])
                                       <div class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: {{ $cor['codigo_cor'] }}"></div>
                                   @endif
                                   {{ $cor['cor'] }}
                               </div>
                           </td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $cor['codigo_cor'] ?? 'N/A' }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($cor['quantidade'], 0, ',', '.') }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['estoque'], 2, ',', '.') }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['necessidade'], 2, ',', '.') }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($cor['consumo_deste_produto'], 2, ',', '.') }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm {{ $cor['saldo'] >= 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                               {{ number_format($cor['saldo'], 2, ',', '.') }}
                           </td>
                           <td class="px-4 py-2 whitespace-nowrap text-sm {{ $cor['producao_possivel'] > 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                               {{ number_format($cor['producao_possivel'], 0, ',', '.') }}
                           </td>
                       </tr>
                   @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                   <tr>
                       <td colspan="2" class="px-4 py-2 text-sm text-gray-700">Total Variações:</td>
                       <td class="px-4 py-2 text-sm">{{ number_format(collect($coresEnriquecidas)->sum('quantidade'), 0, ',', '.') }}</td>
                       <td class="px-4 py-2 text-sm">{{ number_format(collect($coresEnriquecidas)->sum('estoque'), 2, ',', '.') }}</td>
                       <td class="px-4 py-2 text-sm">{{ number_format(collect($coresEnriquecidas)->sum('necessidade'), 2, ',', '.') }}</td>
                       <td class="px-4 py-2 text-sm">{{ number_format(collect($coresEnriquecidas)->sum('consumo_deste_produto'), 2, ',', '.') }}</td>
                       <td class="px-4 py-2 text-sm {{ collect($coresEnriquecidas)->sum('saldo') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                           {{ number_format(collect($coresEnriquecidas)->sum('saldo'), 2, ',', '.') }}
                       </td>
                       <td></td>
                   </tr>
                </tfoot>
           </table>
       </div>

       <!-- Vista Mobile (Cards) -->
       <div class="lg:hidden space-y-4">
           @foreach($coresEnriquecidas as $cor)
               <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                   <div class="flex items-center justify-between mb-3">
                       <div class="flex items-center">
                           <div class="w-5 h-5 rounded-full mr-2 border border-gray-200" style="background-color: {{ $cor['codigo_cor'] ?? '#eee' }}"></div>
                           <span class="font-bold text-gray-900">{{ $cor['cor'] }}</span>
                       </div>
                       <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $cor['codigo_cor'] ?? 'S/ CÓDIGO' }}</span>
                   </div>

                   <div class="grid grid-cols-2 gap-y-3 gap-x-6">
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Qtd. Pretendida</span>
                           <span class="text-sm font-bold text-gray-900">{{ number_format($cor['quantidade'], 0, ',', '.') }}</span>
                       </div>
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Produção Possível</span>
                           <span class="text-sm font-bold {{ $cor['producao_possivel'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                               {{ number_format($cor['producao_possivel'], 0, ',', '.') }}
                           </span>
                       </div>
                       <div class="col-span-2 h-px bg-gray-50 my-1"></div>
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Estoque (m)</span>
                           <span class="text-xs font-semibold text-gray-700">{{ number_format($cor['estoque'], 2, ',', '.') }}</span>
                       </div>
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Saldo (m)</span>
                           <span class="text-xs font-bold {{ $cor['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                               {{ number_format($cor['saldo'], 2, ',', '.') }}
                           </span>
                       </div>
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Consumo Prod.</span>
                           <span class="text-xs font-semibold text-gray-600">{{ number_format($cor['consumo_deste_produto'], 2, ',', '.') }}</span>
                       </div>
                       <div>
                           <span class="text-[10px] font-bold text-gray-400 uppercase block">Necessidade Total</span>
                           <span class="text-xs font-semibold text-gray-600">{{ number_format($cor['necessidade'], 2, ',', '.') }}</span>
                       </div>
                   </div>
               </div>
           @endforeach

           <!-- Totais Mobile -->
           <div class="bg-gray-100 rounded-xl p-4 mt-6 border-2 border-gray-200">
               <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 text-center">Resumo de Produção</h4>
               <div class="space-y-2">
                   <div class="flex justify-between items-center bg-white p-2 rounded-lg">
                       <span class="text-xs text-gray-600 font-medium">Total Variações</span>
                       <span class="text-sm font-bold text-gray-900">{{ number_format(collect($coresEnriquecidas)->sum('quantidade'), 0, ',', '.') }}</span>
                   </div>
                   <div class="flex justify-between items-center bg-white p-2 rounded-lg">
                       <span class="text-xs text-gray-600 font-medium">Total Combinações</span>
                       <span class="text-sm font-bold text-gray-900">{{ number_format($totalCombinacoes, 0, ',', '.') }}</span>
                   </div>
                   <div class="flex justify-between items-center bg-purple-600 p-3 rounded-lg text-white shadow-md">
                       <div class="flex items-center">
                           <span class="text-xs font-bold uppercase tracking-wider mr-2">Total Geral</span>
                           @if($isEqual)
                               <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                           @endif
                       </div>
                       <span class="text-lg font-black">{{ number_format($totalGeral, 0, ',', '.') }}</span>
                   </div>
                   @if(!$isEqual)
                       <div class="bg-yellow-100 border-l-4 border-yellow-500 p-2 rounded-r-lg mt-2 font-medium text-[10px] text-yellow-800">
                           ⚠️ A soma das cores e combinações difere da quantidade pretendida ({{ number_format($quantidadeProduto, 0, ',', '.') }}).
                       </div>
                   @endif
               </div>
           </div>
       </div>
    @else
        <span class="text-gray-400 italic">Nenhuma variação de cor definida para este produto</span>
    @endif
</div>
