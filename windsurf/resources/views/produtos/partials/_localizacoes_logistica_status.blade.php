@props([
    'etapaAtual' => null,
    'coleta' => null,
    'fluxoLogistica' => null,
    'referenciaProduto' => null,
])

@if($etapaAtual && $etapaAtual->isLogistica())
    <div class="mt-1.5 rounded-md border border-indigo-200 dark:border-indigo-800 bg-indigo-50/90 dark:bg-indigo-900/25 px-2 py-1.5 text-[10px] leading-relaxed text-indigo-950 dark:text-indigo-100 space-y-0.5">
        <div class="flex flex-wrap items-center gap-1">
            <span class="font-bold uppercase tracking-wide text-indigo-800 dark:text-indigo-200">🚛 Logística</span>
            @php
                $tipo = $coleta?->tipo ?? $fluxoLogistica;
            @endphp
            @if($tipo)
                <span class="px-1.5 py-0.5 rounded font-bold uppercase {{ $tipo === 'ida' ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' }}">
                    {{ $tipo === 'ida' ? 'Ida' : 'Volta' }}
                </span>
            @endif
        </div>

        @if($coleta)
            <div><span class="font-semibold">Status coleta:</span> {{ \App\Models\ColetaLogistica::labelsStatus()[$coleta->status] ?? $coleta->status }}</div>
            @if($coleta->destinoLocalizacao)
                <div><span class="font-semibold">Destino:</span> {{ $coleta->destinoLocalizacao->nome_reduzido ?? $coleta->destinoLocalizacao->nome_localizacao }}</div>
            @endif
            @if($coleta->motorista)
                <div><span class="font-semibold">Motorista:</span> {{ $coleta->motorista->name }}</div>
            @endif
            @if($coleta->veiculo?->placa)
                <div><span class="font-semibold">Veículo:</span> {{ $coleta->veiculo->placa }}</div>
            @endif
        @else
            <div class="text-indigo-700 dark:text-indigo-300 italic">Aguardando agendamento da coleta</div>
        @endif

        <div class="text-indigo-800/80 dark:text-indigo-200/80">
            <span class="font-semibold">Etapa:</span> {{ $etapaAtual->nome }}
        </div>

        @if(auth()->user()->hasPermission('logistica') && $referenciaProduto)
            <a href="{{ route('logistica-coleta.index', ['referencia' => $referenciaProduto]) }}"
               class="inline-block mt-0.5 font-bold text-indigo-700 dark:text-indigo-300 hover:underline">
                Abrir na Logística →
            </a>
        @endif
    </div>
@endif
