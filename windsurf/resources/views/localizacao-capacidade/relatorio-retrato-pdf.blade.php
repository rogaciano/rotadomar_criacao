<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Capacidade - {{ $mesNome }}/{{ $ano }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #6B7280;
        }

        .localizacao-section {
            margin-bottom: 20px;
        }

        .localizacao-header {
            background-color: #F3F4F6;
            padding: 8px;
            border-left: 4px solid #3B82F6;
            margin-bottom: 8px;
        }

        .localizacao-title {
            font-size: 12px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .localizacao-obs {
            display: inline-block;
            font-size: 9px;
            font-weight: 500;
            color: #92400E;
            background-color: #FEF3C7;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            border: 1px solid #FCD34D;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .stats-row {
            display: table-row;
        }

        .stat-cell {
            display: table-cell;
            padding: 6px;
            border: 1px solid #E5E7EB;
            width: 25%;
        }

        .stat-label {
            font-size: 8px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 11px;
            font-weight: bold;
        }

        .stat-value.green {
            color: #059669;
        }

        .stat-value.red {
            color: #DC2626;
        }

        .stat-value.yellow {
            color: #D97706;
        }

        .produtos-section {
            margin-top: 12px;
        }

        .produtos-title {
            font-size: 10px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th {
            background-color: #F9FAFB;
            padding: 5px 3px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
            color: #6B7280;
            text-transform: uppercase;
            border: 1px solid #E5E7EB;
        }

        td {
            padding: 4px 3px;
            font-size: 8px;
            border: 1px solid #E5E7EB;
        }

        tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .text-center {
            text-align: center;
        }

        .font-semibold {
            font-weight: 600;
        }

        .sub-info {
            display: block;
            font-size: 7px;
            font-weight: bold;
            margin-top: 2px;
            color: #000;
        }

        .header-sub {
            display: block;
            font-size: 6px;
            font-weight: bold;
            color: #444;
            margin-top: 1px;
        }

        .observacao {
            font-size: 7px;
            color: #4B5563;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .qtd-alocada {
            display: inline-block;
            background-color: #DBEAFE;
            color: #1E40AF;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            margin-top: 3px;
        }

        .check-icon {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #059669;
            border-radius: 50%;
            margin-right: 3px;
            position: relative;
            vertical-align: middle;
        }

        .check-icon::after {
            content: '✓';
            color: white;
            font-size: 6px;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .alocacao-item {
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #E5E7EB;
        }

        .alocacao-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .obs-table {
            width: 100%;
            border: none;
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        .obs-table tr {
            border: none;
            border-bottom: 1px solid #E5E7EB;
        }

        .obs-table tr:last-child {
            border-bottom: none;
        }

        .obs-table td {
            border: none;
            padding: 3px 3px;
            vertical-align: top;
        }

        .obs-info {
            width: 70%;
            padding-right: 6px;
        }

        .obs-qtd {
            width: 30%;
            text-align: right;
            padding-left: 6px;
        }

        .no-produtos {
            text-align: center;
            padding: 15px;
            color: #9CA3AF;
            font-style: italic;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 7px;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Capacidade por Localização</h1>
        <p>Período: {{ $mesNome }} de {{ $ano }}</p>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @forelse($dadosDashboard as $dado)
        <div class="localizacao-section">
            <div class="localizacao-header">
                <div class="localizacao-title">
                    {{ $dado['localizacao']->nome_localizacao }}
                    @if(!empty($dado['observacoes']))
                        <span class="localizacao-obs">{{ $dado['observacoes'] }}</span>
                    @endif
                </div>
            </div>

            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stat-cell">
                        <div class="stat-label">Capacidade</div>
                        <div class="stat-value">{{ number_format($dado['capacidade'], 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-label">Previstos</div>
                        <div class="stat-value {{ $dado['acima_capacidade'] ? 'red' : 'green' }}">
                            {{ number_format($dado['produtos_previstos'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-label">Saldo</div>
                        <div class="stat-value {{ $dado['saldo'] < 0 ? 'red' : 'green' }}">
                            {{ number_format($dado['saldo'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-label">Ocupação</div>
                        <div class="stat-value {{ $dado['percentual'] > 100 ? 'red' : ($dado['percentual'] > 80 ? 'yellow' : 'green') }}">
                            {{ number_format($dado['percentual'], 1, ',', '.') }}%
                        </div>
                    </div>
                </div>
            </div>

            @if($dado['produtos']->count() > 0)
                <div class="produtos-section">
                    @php
                        // Agrupar produtos por referência + descrição + marca + grupo + qtd total + data + status
                        $produtosAgrupados = $dado['produtos']->groupBy(function($produto) {
                            $primeiraData = $produto->localizacoes
                                ->whereNotNull('pivot.data_prevista_faccao')
                                ->sortBy('pivot.data_prevista_faccao')
                                ->first();

                            $dataFormatada = 'N/A';
                            if ($primeiraData && $primeiraData->pivot->data_prevista_faccao) {
                                $dataFormatada = is_string($primeiraData->pivot->data_prevista_faccao)
                                    ? \Carbon\Carbon::parse($primeiraData->pivot->data_prevista_faccao)->format('Y-m-d')
                                    : $primeiraData->pivot->data_prevista_faccao->format('Y-m-d');
                            }

                            return $produto->id . '|' .
                                   $produto->referencia . '|' .
                                   $produto->descricao . '|' .
                                   ($produto->marca ? $produto->marca->id : 'sem_marca') . '|' .
                                   ($produto->grupoProduto ? $produto->grupoProduto->id : 'sem_grupo') . '|' .
                                   $produto->quantidade . '|' .
                                   $dataFormatada . '|' .
                                   ($produto->status ? $produto->status->id : 'sem_status');
                        });
                    @endphp
                    <div class="produtos-title">Produtos Previstos ({{ $produtosAgrupados->count() }} produtos, {{ $dado['produtos']->count() }} alocações)</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 12%;">Ref</th>
                                <th style="width: 22%;">
                                    Descrição
                                    <span class="header-sub">GRUPO DE PRODUTOS</span>
                                </th>
                                <th style="width: 12%;">
                                    Marca
                                    <span class="header-sub">STATUS</span>
                                </th>
                                <th style="width: 46%;">Produção e Detalhes</th>
                                <th style="width: 8%;" class="text-center">Qtd</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $corHex = [
                                    'blue' => '#EFF6FF', 'blue-text' => '#1E40AF', 'blue-border' => '#BFDBFE',
                                    'green' => '#F0FDF4', 'green-text' => '#166534', 'green-border' => '#BBF7D0',
                                    'yellow' => '#FEFCE8', 'yellow-text' => '#854D0E', 'yellow-border' => '#FEF08A',
                                    'red' => '#FEF2F2', 'red-text' => '#991B1B', 'red-border' => '#FECACA',
                                    'purple' => '#FAF5FF', 'purple-text' => '#6B21A8', 'purple-border' => '#E9D5FF',
                                    'gray' => '#F9FAFB', 'gray-text' => '#374151', 'gray-border' => '#E5E7EB',
                                    'indigo' => '#EEF2FF', 'indigo-text' => '#3730A3', 'indigo-border' => '#C3DAFE',
                                    'pink' => '#FDF2F8', 'pink-text' => '#9D174D', 'pink-border' => '#FBCFE8',
                                    'orange' => '#FFF7ED', 'orange-text' => '#9A3412', 'orange-border' => '#FED7AA',
                                ];
                            @endphp
                            @foreach($produtosAgrupados as $chave => $produtosGrupo)
                                @php
                                    $produtoPrincipal = $produtosGrupo->first();

                                    // Identificar todas as etapas presentes neste grupo de produtos
                                    $etapaIdsNoGrupo = $produtosGrupo->flatMap(function($p) {
                                        return $p->localizacoes->pluck('pivot.etapa_atual_id');
                                    })->unique()->filter()->toArray();

                                    $etapasNoGrupo = $etapasProducao->whereIn('id', $etapaIdsNoGrupo);
                                @endphp
                                <tr>
                                    <td class="font-semibold">
                                        {{ $produtoPrincipal->referencia }}
                                        @php
                                            // Buscar a data prevista para produção
                                            $dataPrevista = null;
                                            foreach($produtosGrupo as $produto) {
                                                $locComData = $produto->localizacoes
                                                    ->whereNotNull('pivot.data_prevista_faccao')
                                                    ->sortBy('pivot.data_prevista_faccao')
                                                    ->first();
                                                if ($locComData && $locComData->pivot->data_prevista_faccao) {
                                                    $dataPrevista = is_string($locComData->pivot->data_prevista_faccao)
                                                        ? \Carbon\Carbon::parse($locComData->pivot->data_prevista_faccao)->format('d/m/Y')
                                                        : $locComData->pivot->data_prevista_faccao->format('d/m/Y');
                                                    break;
                                                }
                                            }
                                        @endphp
                                        @if($dataPrevista)
                                            <div style="font-size: 6px; color: #111; font-weight: bold; margin-top: 2px;">PREV: {{ $dataPrevista }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $produtoPrincipal->descricao }}
                                        <span class="sub-info">{{ $produtoPrincipal->grupoProduto->descricao ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($produtoPrincipal->marca)
                                            @if($produtoPrincipal->marca->cor_fundo && $produtoPrincipal->marca->cor_fonte)
                                                <span style="background-color: {{ $produtoPrincipal->marca->cor_fundo }}; color: {{ $produtoPrincipal->marca->cor_fonte }}; padding: 1px 4px; border-radius: 10px; font-size: 7px; font-weight: 600; white-space: nowrap; display: inline-block;">
                                                    {{ $produtoPrincipal->marca->nome_marca }}
                                                </span>
                                            @else
                                                {{ $produtoPrincipal->marca->nome_marca }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                        <span class="sub-info">{{ $produtoPrincipal->status->descricao ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            // Carregar observações do produto (apenas uma vez)
                                            $obs = \App\Models\ProdutoObservacao::where('produto_id', $produtoPrincipal->id)->get();

                                            // Carregar todas as observações das localizações de todas as alocações
                                            $todasObsLocalizacoes = collect();
                                            foreach($produtosGrupo as $produto) {
                                                $obsLoc = $produto->localizacoes->filter(function($loc) {
                                                    return !empty($loc->pivot->ordem_producao) || !empty($loc->pivot->observacao);
                                                });
                                                $todasObsLocalizacoes = $todasObsLocalizacoes->merge($obsLoc);
                                            }

                                            // Remover duplicatas baseado em ordem_producao + observacao
                                            $todasObsLocalizacoes = $todasObsLocalizacoes->unique(function($loc) {
                                                return $loc->pivot->ordem_producao . '|' . $loc->pivot->observacao;
                                            });

                                            $temObservacoes = $obs->count() > 0 || $todasObsLocalizacoes->count() > 0;
                                        @endphp

                                        {{-- Observações das Localizações (Ordem de Produção) --}}
                                        @if($todasObsLocalizacoes->count() > 0)
                                            <table class="obs-table" style="margin-bottom: 6px;">
                                                <thead style="background-color: #F3F4F6;">
                                                    <tr>
                                                        <th style="width: 14%; font-size: 6px; padding: 2px;">OP</th>
                                                        <th style="width: 14%; font-size: 6px; padding: 2px;">Etapa</th>
                                                        <th style="width: 10%; font-size: 6px; padding: 2px; text-align: center;">Qtd</th>
                                                        <th style="width: 14%; font-size: 6px; padding: 2px; text-align: center;">Envio</th>
                                                        <th style="width: 14%; font-size: 6px; padding: 2px; text-align: center;">Retorno</th>
                                                        <th style="width: 14%; font-size: 6px; padding: 2px; text-align: center;">Entrega</th>
                                                        <th style="width: 20%; font-size: 6px; padding: 2px;">Obs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $totalQuantidades = 0;
                                                @endphp
                                                @foreach($todasObsLocalizacoes as $loc)
                                                    @php
                                                        // Buscar a quantidade alocada
                                                        $qtdAlocada = 0;
                                                        foreach($produtosGrupo as $produto) {
                                                            $localizacaoAtual = $produto->localizacoes
                                                                ->where('pivot.ordem_producao', $loc->pivot->ordem_producao)
                                                                ->first();
                                                            if ($localizacaoAtual) {
                                                                $qtdAlocada = $localizacaoAtual->pivot->quantidade ?? 0;
                                                                break;
                                                            }
                                                        }
                                                        $totalQuantidades += $qtdAlocada;

                                                        // Etapa desta linha
                                                        $etapaLinhaId = $loc->pivot->etapa_atual_id;
                                                        $etapaL = $etapaLinhaId ? $etapasProducao->firstWhere('id', $etapaLinhaId) : null;

                                                        // Datas
                                                        $dataEnvio = $loc->pivot->data_envio_faccao
                                                            ? (is_string($loc->pivot->data_envio_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_envio_faccao)->format('d/m/Y') : $loc->pivot->data_envio_faccao->format('d/m/Y'))
                                                            : null;
                                                        $dataRetorno = $loc->pivot->data_retorno_faccao
                                                            ? (is_string($loc->pivot->data_retorno_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_retorno_faccao)->format('d/m/Y') : $loc->pivot->data_retorno_faccao->format('d/m/Y'))
                                                            : null;
                                                        $dataEntrega = $loc->pivot->data_entrega_faccao
                                                            ? (is_string($loc->pivot->data_entrega_faccao) ? \Carbon\Carbon::parse($loc->pivot->data_entrega_faccao)->format('d/m/Y') : $loc->pivot->data_entrega_faccao->format('d/m/Y'))
                                                            : null;
                                                    @endphp
                                                    <tr>
                                                        <td style="font-size: 7px; padding: 2px;">
                                                            @if($loc->pivot->concluido == 1)
                                                                <span style="color: #059669;">✓</span>
                                                            @endif
                                                            <strong>{{ $loc->pivot->ordem_producao ?: '-' }}</strong>
                                                        </td>
                                                        <td style="font-size: 6px; padding: 2px;">
                                                            @if($etapaL)
                                                                <span style="color: {{ $corHex[$etapaL->cor.'-text'] ?? '#333' }}; font-weight: bold; text-transform: uppercase;">{{ $etapaL->nome }}</span>
                                                            @else
                                                                <span style="color: #9CA3AF;">-</span>
                                                            @endif
                                                        </td>
                                                        <td style="font-size: 7px; padding: 2px; text-align: center;">
                                                            <span class="qtd-alocada" style="margin:0;">{{ number_format($qtdAlocada, 0, ',', '.') }}</span>
                                                        </td>
                                                        <td style="font-size: 6px; padding: 2px; text-align: center;">
                                                            @if($dataEnvio)
                                                                <span style="background-color: #FEF3C7; color: #92400E; padding: 1px 2px; border-radius: 2px;">{{ $dataEnvio }}</span>
                                                            @else
                                                                <span style="color: #9CA3AF;">-</span>
                                                            @endif
                                                        </td>
                                                        <td style="font-size: 6px; padding: 2px; text-align: center;">
                                                            @if($dataRetorno)
                                                                <span style="background-color: #D1FAE5; color: #065F46; padding: 1px 2px; border-radius: 2px;">{{ $dataRetorno }}</span>
                                                            @else
                                                                <span style="color: #9CA3AF;">-</span>
                                                            @endif
                                                        </td>
                                                        <td style="font-size: 6px; padding: 2px; text-align: center;">
                                                            @if($dataEntrega)
                                                                <span style="background-color: #F3E8FF; color: #6B21A8; padding: 1px 2px; border-radius: 2px;">{{ $dataEntrega }}</span>
                                                            @else
                                                                <span style="color: #9CA3AF;">-</span>
                                                            @endif
                                                        </td>
                                                        <td style="font-size: 6px; padding: 2px;">
                                                            @if($loc->pivot->observacao)
                                                                {{ Str::limit(strip_tags($loc->pivot->observacao), 40) }}
                                                            @else
                                                                <span style="color: #9CA3AF;">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                {{-- Linha de Total quando houver mais de 1 item --}}
                                                @if($todasObsLocalizacoes->count() > 1)
                                                    <tr style="border-top: 1px solid #9CA3AF; background-color: #F9FAFB;">
                                                        <td colspan="2" style="font-size: 7px; padding: 2px;"><strong>TOTAL:</strong></td>
                                                        <td style="font-size: 7px; padding: 2px; text-align: center;">
                                                            <span style="display: inline-block; background-color: #059669; color: white; padding: 1px 3px; border-radius: 2px; font-weight: 700;">{{ number_format($totalQuantidades, 0, ',', '.') }}</span>
                                                        </td>
                                                        <td colspan="4"></td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        @endif

                                        {{-- Direcionamento Comercial --}}
                                        @php
                                            $direcionamentoComercial = null;
                                            foreach($produtosGrupo as $produto) {
                                                if($produto->direcionamentoComercial) {
                                                    $direcionamentoComercial = $produto->direcionamentoComercial;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        @if($direcionamentoComercial)
                                            <div style="margin-top: 3px; padding-top: 3px; border-top: 1px solid #E5E7EB;">
                                                <span style="font-size: 6px; font-weight: 600; color: #7C3AED;">Dir. Comercial:</span>
                                                <span style="font-size: 6px; color: #4B5563;">{{ $direcionamentoComercial->descricao }}</span>
                                            </div>
                                        @endif

                                        {{-- Observações do Produto (movidas para o final) --}}
                                        @if($obs->count() > 0)
                                            <div style="margin-top: 3px; padding-top: 3px; border-top: 1px dashed #D1D5DB;">
                                                <span style="font-size: 6px; font-weight: 600; color: #374151;">📝 Obs:</span>
                                                @foreach($obs as $observacao)
                                                    @php
                                                        $obsTexto = $observacao->observacao;
                                                        if (strpos($obsTexto, '<p>') === false && strpos($obsTexto, '<span') === false) {
                                                            $obsTexto = preg_replace('/<red>(.*?)<\/red>/i', '<span style="color: #DC2626; font-weight: 600;">$1</span>', $obsTexto);
                                                            $obsTexto = preg_replace('/<blue>(.*?)<\/blue>/i', '<span style="color: #2563EB; font-weight: 600;">$1</span>', $obsTexto);
                                                            $obsTexto = preg_replace('/<green>(.*?)<\/green>/i', '<span style="color: #16A34A; font-weight: 600;">$1</span>', $obsTexto);
                                                        }
                                                        $textoLimpo = strip_tags($obsTexto);
                                                        if (strlen($textoLimpo) > 60) {
                                                            $obsTexto = Str::limit($textoLimpo, 60);
                                                        }
                                                    @endphp
                                                    <div class="observacao" style="margin-top: 1px;">{!! $obsTexto !!}</div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if(!$temObservacoes && !$direcionamentoComercial)
                                            <div class="observacao" style="color: #9CA3AF; font-style: italic;">-</div>
                                        @endif
                                    </td>
                                    <td class="text-center font-semibold">
                                        {{ number_format($produtoPrincipal->quantidade ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-produtos">Nenhum produto previsto para este período</div>
            @endif
        </div>
    @empty
        <div class="no-produtos" style="margin-top: 50px;">Nenhuma capacidade cadastrada para este período</div>
    @endforelse

    <div class="footer">
        <p>Sistema de Gestão de Capacidade - Rota do Amar</p>
    </div>
</body>
</html>
