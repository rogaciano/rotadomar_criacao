<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                🚛 Logística de Coleta
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('veiculos.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700">
                    Veículos
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensagens de sucesso/erro --}}
            {{-- Erros de validação (não exibidos pelo toast global) --}}
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-1">Erro ao agendar coleta:</p>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ===== SEÇÃO 1: COLETAS ATIVAS ===== --}}
            @if($coletasAtivas->count() > 0)
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 bg-indigo-50 dark:bg-indigo-900/30">
                    <h3 class="text-sm font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wider">
                        📋 Coletas Ativas ({{ $coletasAtivas->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Referência</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Origem</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Destino</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Qtd</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Motorista</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Veículo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Início</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Retorno</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($coletasAtivas as $coleta)
                                @php
                                    $pl = $coleta->produtoLocalizacao;
                                    $produto = $pl?->produto;
                                    $statusClasses = [
                                        'agendado' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                                        'em_transito' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300',
                                    ];
                                    $statusLabels = [
                                        'agendado' => 'Aguardando Motorista',
                                        'em_transito' => 'Em Trânsito',
                                    ];
                                    $user = auth()->user();
                                    $isOrigem = $user->isAdmin() || $user->localizacao_id === $pl?->localizacao_id;
                                    $isDestino = $user->isAdmin() || $user->localizacao_id === $coleta->destino_localizacao_id;
                                    $isMotorista = $user->isAdmin() || $coleta->motorista_user_id === $user->id;
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $produto?->referencia ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $pl?->localizacao?->nome_reduzido ?? $pl?->localizacao?->nome_localizacao ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->destinoLocalizacao?->nome_reduzido ?? $coleta->destinoLocalizacao?->nome_localizacao ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $pl?->quantidade ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->motorista?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->veiculo?->placa ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->inicio_previsto_em?->format('d/m H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->retorno_previsto_em?->format('d/m H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$coleta->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$coleta->status] ?? $coleta->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-1">
                                        {{-- Confirmar Chegada (origem) --}}
                                        @if($coleta->status === 'agendado' && $isOrigem)
                                            <button type="button"
                                                onclick="document.getElementById('modal-chegada-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-orange-500 text-white text-xs font-semibold rounded hover:bg-orange-600">
                                                Confirmar Chegada
                                            </button>
                                        @endif

                                        {{-- Confirmar Recebimento (destino) --}}
                                        @if($coleta->status === 'em_transito' && $isDestino)
                                            <button type="button"
                                                onclick="document.getElementById('modal-recebimento-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                                                Confirmar Recebimento
                                            </button>
                                        @endif

                                        {{-- Cancelar (motorista, só agendado) --}}
                                        @if($coleta->status === 'agendado' && $isMotorista)
                                            <form action="{{ route('logistica-coleta.cancelar', $coleta) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Tem certeza que deseja cancelar esta coleta?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal: Confirmar Chegada na Origem --}}
                                @if($coleta->status === 'agendado' && $isOrigem)
                                <div id="modal-chegada-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Chegada do Motorista</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Motorista <strong>{{ $coleta->motorista?->name }}</strong> chegou para coletar
                                            <strong>{{ $produto?->referencia }}</strong>?
                                        </p>
                                        <form action="{{ route('logistica-coleta.confirmar-chegada-origem', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_origem" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-orange-500 rounded-md hover:bg-orange-600">Confirmar Chegada</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                {{-- Modal: Confirmar Recebimento no Destino --}}
                                @if($coleta->status === 'em_transito' && $isDestino)
                                <div id="modal-recebimento-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Recebimento do Produto</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Confirmar que o produto <strong>{{ $produto?->referencia }}</strong> foi recebido?
                                        </p>
                                        <form action="{{ route('logistica-coleta.confirmar-recebimento-destino', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_destino" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700">Confirmar Recebimento</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- ===== SEÇÃO 2: FILTROS + PRODUTOS AGUARDANDO RETIRADA ===== --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                        📍 Produtos Aguardando Retirada ({{ $aguardandoRetirada->count() }})
                    </h3>
                </div>

                {{-- Filtros --}}
                <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                    <form method="GET" action="{{ route('logistica-coleta.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 md:items-end">
                        <div>
                            <label for="localizacao_id" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Localização (Origem)</label>
                            <select name="localizacao_id" id="localizacao_id" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm">
                                <option value="">Todas</option>
                                @foreach($localizacoes as $loc)
                                    <option value="{{ $loc->id }}" {{ ($localizacaoId ?? '') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->nome_reduzido ?? $loc->nome_localizacao }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="referencia" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Referência</label>
                            <input type="text" name="referencia" id="referencia" value="{{ $referencia ?? '' }}" placeholder="Buscar referência"
                                   class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Filtrar</button>
                            <a href="{{ route('logistica-coleta.index') }}" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-gray-300">Limpar</a>
                        </div>
                    </form>
                </div>

                {{-- Lista de produtos --}}
                @if($aguardandoRetirada->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Referência</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Origem</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Qtd</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Situação</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Motorista</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Veículo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Destino</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Aguardando desde</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @foreach($aguardandoRetirada as $pl)
                                    @php
                                        $produto = $pl->produto;
                                        $coleta = $pl->coletaLogisticaAtiva ?? null;
                                        $temColeta = $coleta !== null;
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 {{ $temColeta ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : '' }}">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $produto?->referencia ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $pl->localizacao?->nome_reduzido ?? $pl->localizacao?->nome_localizacao ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $pl->quantidade ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($temColeta)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">
                                                    🚛 Agendado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-slate-600 dark:text-gray-300">
                                                    ⏳ Sem coleta
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $temColeta ? $coleta->motorista?->name : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $temColeta ? $coleta->veiculo?->placa : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $temColeta ? ($coleta->destinoLocalizacao?->nome_reduzido ?? $coleta->destinoLocalizacao?->nome_localizacao) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $pl->updated_at?->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if(!$temColeta)
                                                <button type="button"
                                                    onclick="document.getElementById('modal-agendar-{{ $pl->id }}').classList.remove('hidden')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                                                    Agendar Coleta
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $coleta->inicio_previsto_em?->format('d/m H:i') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Modal: Agendar Coleta (só se não tem coleta ativa) --}}
                                    @if(!$temColeta)
                                    <div id="modal-agendar-{{ $pl->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Agendar Coleta</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                Produto: <strong>{{ $produto?->referencia }}</strong> — {{ $pl->localizacao?->nome_localizacao }} — {{ $pl->quantidade }} un.
                                            </p>
                                            <form action="{{ route('logistica-coleta.agendar') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="produto_localizacao_id" value="{{ $pl->id }}">

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Veículo *</label>
                                                        <select name="veiculo_id" required class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm">
                                                            <option value="">Selecione...</option>
                                                            @foreach($veiculos as $v)
                                                                <option value="{{ $v->id }}">{{ $v->placa }} {{ $v->descricao ? '- ' . $v->descricao : '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Destino *</label>
                                                        <select name="destino_localizacao_id" required class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm">
                                                            <option value="">Selecione...</option>
                                                            @foreach($destinosDisponiveis as $dest)
                                                                <option value="{{ $dest->id }}">{{ $dest->nome_reduzido ?? $dest->nome_localizacao }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Início previsto *</label>
                                                        <input type="datetime-local" name="inicio_previsto_em" required
                                                               class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Retorno previsto *</label>
                                                        <input type="datetime-local" name="retorno_previsto_em" required
                                                               class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                    <textarea name="observacao_motorista" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                                </div>

                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Agendar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhum produto aguardando retirada no momento.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
