<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Planejamento') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botões de ação -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('localizacao-capacidade.create') }}" class="btn-ghost-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Nova Capacidade
                    </a>
                    <a href="{{ route('localizacao-capacidade.dashboard') }}" class="btn-ghost-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('localizacao-capacidade.listagem-pdf', request()->query()) }}" target="_blank" class="btn-ghost-rose">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Gerar PDF
                    </a>
                </div>
            </div>

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">

                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Filtros -->
                    <div class="mb-6 bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('localizacao-capacidade.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="localizacao_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Localização</label>
                                <select name="localizacao_id[]" id="localizacao_id" multiple class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ (is_array(request('localizacao_id')) && in_array($localizacao->id, request('localizacao_id'))) ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="mes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mês</label>
                                <select name="mes" id="mes" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @php
                                        $meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                    @endphp
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                            {{ $meses[$m] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="ano" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ano</label>
                                <select name="ano" id="ano" class="w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach(range(now()->year - 1, now()->year + 2) as $a)
                                        <option value="{{ $a }}" {{ request('ano') == $a ? 'selected' : '' }}>
                                            {{ $a }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="table-base" style="min-width: 1200px;">
                            <thead class="table-header">
                                <tr>
                                    <th scope="col" class="table-header-cell">
                                        Localização
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Período
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Capacidade
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Previstos
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Saldo
                                    </th>
                                    <th scope="col" class="table-header-cell text-center">
                                        Ocupação
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        Observações
                                    </th>
                                    <th scope="col" class="table-header-cell text-right w-32">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @forelse ($capacidades as $capacidade)
                                    <tr class="table-row">
                                        <td class="table-cell table-cell-primary">
                                            {{ $capacidade->localizacao->nome_localizacao }}
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            {{ $capacidade->mes_ano_formatado }}
                                        </td>
                                        <td class="table-cell text-center">
                                            <span class="badge-info">
                                                {{ $capacidade->capacidade }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center">
                                            @php
                                                $previstos = $capacidade->getProdutosPrevistos();
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs {{ $previstos > $capacidade->capacidade ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }} rounded-full font-medium">
                                                {{ $previstos }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center">
                                            @php
                                                $saldo = $capacidade->getSaldo();
                                            @endphp
                                            <span class="font-semibold {{ $saldo < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $saldo }}
                                            </span>
                                        </td>
                                        <td class="table-cell text-center">
                                            @php
                                                $percentual = $capacidade->getPercentualOcupacao();
                                            @endphp
                                            <div class="flex items-center justify-center">
                                                <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full {{ $percentual > 100 ? 'bg-red-600' : ($percentual > 80 ? 'bg-yellow-600' : 'bg-green-600') }}" style="width: {{ min($percentual, 100) }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium {{ $percentual > 100 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                    {{ $percentual }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="table-cell table-cell-secondary">
                                            @if($capacidade->observacoes)
                                                <div class="max-w-xs">
                                                    <span class="text-xs">{{ Str::limit($capacidade->observacoes, 80) }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="table-cell text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('localizacao-capacidade.show', $capacidade->id) }}" class="btn-action-view" title="Visualizar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                <a href="{{ route('localizacao-capacidade.edit', $capacidade->id) }}" class="btn-action-edit" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <form action="{{ route('localizacao-capacidade.destroy', $capacidade) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete" onclick="return confirm('Tem certeza que deseja excluir esta capacidade?')" title="Excluir">
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
                                        <td colspan="8" class="table-cell table-empty">Nenhuma capacidade cadastrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $capacidades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            console.log('Inicializando Select2 para localização...');
            console.log('jQuery disponível:', typeof $ !== 'undefined');
            console.log('Select2 disponível:', typeof $.fn.select2 !== 'undefined');
            
            setTimeout(function() {
                $('#localizacao_id').select2({
                    placeholder: "Selecione uma ou mais localizações",
                    allowClear: true,
                    width: '100%',
                    language: "pt-BR",
                    closeOnSelect: false
                });
                console.log('Select2 inicializado!');
            }, 100);
        });
    </script>
    @endpush
</x-app-layout>
