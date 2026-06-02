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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Etapa Atual</th>
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
                                        'entregue' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
                                    ];
                                    $etapaAtualSlug = $pl?->etapaAtual?->slug;
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
                                            {{ $coletaStatusLabels[$coleta->status] ?? $coleta->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $pl?->etapaAtual?->nome ?? '-' }}</div>
                                        @if($pl?->etapaAtual?->slug)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $pl->etapaAtual->slug }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-1">
                                        @if($coleta->status === 'agendado' && $isMotorista && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_AGENDAMENTO)
                                            <button type="button"
                                                onclick="document.getElementById('modal-solicitar-retirada-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-orange-500 text-white text-xs font-semibold rounded hover:bg-orange-600">
                                                Solicitar Retirada
                                            </button>
                                        @endif

                                        @if($coleta->status === 'agendado' && $isOrigem && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA)
                                            <button type="button"
                                                onclick="document.getElementById('modal-confirmar-retirada-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-amber-600 text-white text-xs font-semibold rounded hover:bg-amber-700">
                                                Confirmar Retirada
                                            </button>
                                        @endif

                                        @if($coleta->status === 'em_transito' && $isMotorista && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_EM_TRANSITO)
                                            <button type="button"
                                                onclick="document.getElementById('modal-entrega-fabrica-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                                                Confirmar Entrega
                                            </button>
                                        @endif

                                        @if($coleta->status === 'entregue' && $isDestino && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA)
                                            <button type="button"
                                                onclick="document.getElementById('modal-checkin-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                                                Registrar Check-in
                                            </button>
                                        @endif

                                        @if($coleta->status === 'entregue' && $isDestino && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_CHECK_IN)
                                            <button type="button"
                                                onclick="document.getElementById('modal-chegada-fabrica-{{ $coleta->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded hover:bg-emerald-700">
                                                Confirmar Chegada Final
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

                                @if($coleta->status === 'agendado' && $isMotorista && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_AGENDAMENTO)
                                <div id="modal-solicitar-retirada-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Solicitar Retirada na Facção</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Registrar que o motorista <strong>{{ $coleta->motorista?->name }}</strong> solicitou a retirada de
                                            <strong>{{ $produto?->referencia }}</strong> na facção.
                                        </p>
                                        <form action="{{ route('logistica-coleta.solicitar-retirada', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_motorista" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-orange-500 rounded-md hover:bg-orange-600">Solicitar Retirada</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                @if($coleta->status === 'agendado' && $isOrigem && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_SAIDA_FABRICA_SOLICITAR_RETIRADA)
                                <div id="modal-confirmar-retirada-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Retirada pela Facção</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Confirmar a retirada do produto <strong>{{ $produto?->referencia }}</strong> pela facção? Após isso, ele entra automaticamente em trânsito.
                                        </p>
                                        <form action="{{ route('logistica-coleta.confirmar-retirada-faccao', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_origem" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-amber-600 rounded-md hover:bg-amber-700">Confirmar Retirada</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                @if($coleta->status === 'em_transito' && $isMotorista && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_EM_TRANSITO)
                                <div id="modal-entrega-fabrica-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Entrega na Fábrica</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Confirmar que o produto <strong>{{ $produto?->referencia }}</strong> foi entregue na fábrica?
                                        </p>
                                        <form action="{{ route('logistica-coleta.confirmar-entrega-fabrica', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_destino" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700">Confirmar Entrega</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                @if($coleta->status === 'entregue' && $isDestino && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_ENTREGA_CONFIRMADA_FABRICA)
                                <div id="modal-checkin-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Registrar Check-in</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Registrar o check-in do produto <strong>{{ $produto?->referencia }}</strong> na fábrica?
                                        </p>
                                        <form action="{{ route('logistica-coleta.registrar-checkin', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_destino" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Registrar Check-in</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif

                                @if($coleta->status === 'entregue' && $isDestino && $etapaAtualSlug === \App\Models\EtapaProducao::SLUG_CHECK_IN)
                                <div id="modal-chegada-fabrica-{{ $coleta->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this)this.classList.add('hidden')">
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Chegada Final</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Confirmar a chegada final do produto <strong>{{ $produto?->referencia }}</strong> na fábrica e encerrar o processo?
                                        </p>
                                        <form action="{{ route('logistica-coleta.confirmar-chegada-fabrica', $coleta) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Observação (opcional)</label>
                                                <textarea name="observacao_destino" rows="2" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('[id^=modal-]').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-600 rounded-md hover:bg-gray-300">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-md hover:bg-emerald-700">Encerrar Processo</button>
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
                <div class="px-4 py-4 border-b border-gray-200 dark:border-slate-700 bg-indigo-50 dark:bg-indigo-900/30">
                    <h3 class="text-base font-extrabold text-indigo-900 dark:text-indigo-100 uppercase tracking-wider">
                        📍 Produtos Disponíveis para Agendamento ({{ $aguardandoRetirada instanceof \Illuminate\Pagination\LengthAwarePaginator ? $aguardandoRetirada->total() : $aguardandoRetirada->count() }})
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
                @if(($aguardandoRetirada instanceof \Illuminate\Pagination\LengthAwarePaginator ? $aguardandoRetirada->total() : $aguardandoRetirada->count()) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Referência</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Origem</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Qtd</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Situação</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Etapa Atual</th>
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
                                                    🚛 {{ $coletaStatusLabels[$coleta->status] ?? 'Agendado' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-slate-600 dark:text-gray-300">
                                                    ⏳ Disponível
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $pl->etapaAtual?->nome ?? '-' }}
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
                    {{-- Paginação Aguardando Retirada --}}
                    @if($aguardandoRetirada instanceof \Illuminate\Pagination\LengthAwarePaginator && $aguardandoRetirada->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700">
                            {{ $aguardandoRetirada->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhum produto disponível para agendamento logístico no momento.
                    </div>
                @endif
            </div>

            {{-- ===== SEÇÃO 3: HISTÓRICO DE COLETAS ===== --}}
            @php
                $historicoTotal = $historicoColetas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $historicoColetas->total() : 0;
            @endphp
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 bg-green-50 dark:bg-green-900/20">
                    <h3 class="text-sm font-bold text-green-800 dark:text-green-300 uppercase tracking-wider">
                        ✅ Histórico de Coletas ({{ $historicoTotal }})
                    </h3>
                </div>

                {{-- Filtro por período --}}
                <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                    <form method="GET" action="{{ route('logistica-coleta.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 md:items-end">
                        {{-- Manter filtros da seção de retirada --}}
                        @if(request('localizacao_id'))
                            <input type="hidden" name="localizacao_id" value="{{ request('localizacao_id') }}">
                        @endif
                        @if(request('referencia'))
                            <input type="hidden" name="referencia" value="{{ request('referencia') }}">
                        @endif
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">De</label>
                            <input type="date" name="historico_de" value="{{ $historicoDataDe ?? '' }}"
                                   class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Até</label>
                            <input type="date" name="historico_ate" value="{{ $historicoDataAte ?? '' }}"
                                   class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white text-sm font-semibold hover:bg-green-700">Filtrar</button>
                            <a href="{{ route('logistica-coleta.index', array_filter(['localizacao_id' => request('localizacao_id'), 'referencia' => request('referencia')])) }}" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-gray-300">Limpar</a>
                        </div>
                    </form>
                </div>

                @if($historicoTotal > 0)
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Etapa Final</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Início</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Finalizado em</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($historicoColetas as $coleta)
                                @php
                                    $pl = $coleta->produtoLocalizacao;
                                    $produto = $pl?->produto;
                                    $statusClasses = [
                                        'entregue' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
                                        'finalizado' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                                        'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                                    ];
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
                                        {{ $pl?->etapaAtual?->nome ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $coleta->inicio_previsto_em?->format('d/m H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $coleta->updated_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$coleta->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $coletaStatusLabels[$coleta->status] ?? $coleta->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Paginação --}}
                @if($historicoColetas->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700">
                        {{ $historicoColetas->appends(request()->query())->links() }}
                    </div>
                @endif
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhuma coleta finalizada no período selecionado.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
