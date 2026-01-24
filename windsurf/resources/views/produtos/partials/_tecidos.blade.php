<!-- Tecidos -->
<div class="bg-gray-50 dark:bg-slate-800/50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tecidos</h3>

    @if($produto->tecidos->count() > 0)
                <!-- Vista Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Referência</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Consumo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            @foreach($produto->tecidos as $tecido)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('tecidos.show', $tecido->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline">
                                            {{ $tecido->descricao }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tecido->referencia }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tecido->pivot->consumo ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vista Mobile (Cards) -->
                <div class="lg:hidden space-y-4">
                    @foreach($produto->tecidos as $tecido)
                        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ route('tecidos.show', $tecido->id) }}" class="text-blue-600 dark:text-blue-400 font-bold text-sm hover:underline">
                                    {{ $tecido->descricao }}
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Referência</div>
                                <div class="text-gray-900 dark:text-white">{{ $tecido->referencia }}</div>

                                <div class="text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Consumo</div>
                                <div class="text-gray-900 dark:text-white font-bold bg-gray-50 dark:bg-slate-700 px-2 py-0.5 rounded-md inline-block">
                                    {{ $tecido->pivot->consumo ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-gray-400 dark:text-gray-500 italic">Nenhum tecido associado a este produto</span>
            @endif
</div>
