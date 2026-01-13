<!DOCTYPE html>
<html>
<head>
    <title>Produto - {{ $produto->referencia }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1F2937;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
            color: #374151;
        }
        .info-section {
            margin-bottom: 20px;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 12px;
            background-color: #F9FAFB;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #D1D5DB;
            color: #1F2937;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 4px 8px;
            width: 33.33%;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #6B7280;
            font-size: 10px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .info-value {
            display: block;
            color: #1F2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px solid #E5E7EB;
        }
        th, td {
            padding: 5px 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #F3F4F6;
            font-weight: bold;
            color: #374151;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-blue {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        .badge-green {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-purple {
            background-color: #EDE9FE;
            color: #6D28D9;
        }
        .badge-yellow {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        .highlight-box {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 10px;
        }
        .obs-section {
            background-color: #FFFBEB;
            border: 1px solid #FCD34D;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .obs-item {
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #F59E0B;
        }
        .obs-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Rota do Mar - DETALHES DO PRODUTO</div>
            <div class="subtitle">{{ $produto->referencia }} - {{ $produto->descricao }}</div>
            <div style="font-size: 10px; color: #6B7280;">Gerado em: {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Informações Básicas -->
        <div class="info-section">
            <div class="section-title">Informações Básicas</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Referência</span>
                        <span class="info-value">
                            {{ $produto->referencia }}
                            @if($produto->isReprogramacao())
                                <span class="badge badge-yellow">Rep. #{{ str_pad($produto->numero_reprogramacao, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Descrição</span>
                        <span class="info-value">{{ $produto->descricao }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Marca</span>
                        <span class="info-value">{{ $produto->marca->nome_marca ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Data de Cadastro</span>
                        <span class="info-value">{{ $produto->data_cadastro ? $produto->data_cadastro->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Data Prevista para Produção</span>
                        <span class="info-value" style="color: #2563EB; font-weight: bold;">{{ $produto->data_prevista_producao ? $produto->data_prevista_producao->format('m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Estilista</span>
                        <span class="info-value">{{ $produto->estilista->nome_estilista ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Grupo</span>
                        <span class="info-value">{{ $produto->grupoProduto->descricao ?? 'N/A' }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Direcionamento Comercial</span>
                        <span class="info-value" style="color: #7C3AED; font-weight: bold;">{{ $produto->direcionamentoComercial->descricao ?? 'Sem direcionamento' }}</span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Quantidade</span>
                        <span class="info-value" style="font-weight: bold; font-size: 12px;">{{ number_format($produto->quantidade, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="badge badge-blue">{{ $produto->status->descricao ?? 'N/A' }}</span>
                        </span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Localização Atual</span>
                        <span class="info-value">
                            @if($produto->localizacao_atual)
                                <span class="badge badge-purple">{{ $produto->localizacao_atual->nome_localizacao }}</span>
                            @else
                                <span style="color: #9CA3AF; font-style: italic;">Não localizado</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Preço Atacado / Varejo</span>
                        <span class="info-value">
                            R$ {{ number_format($produto->preco_atacado, 2, ',', '.') }} /
                            R$ {{ number_format($produto->preco_varejo, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observações do Produto -->
        @if($produto->observacoes && $produto->observacoes->count() > 0)
        <div class="obs-section">
            <div class="section-title" style="border-color: #F59E0B;">📝 Observações do Produto</div>
            @foreach($produto->observacoes as $observacao)
                <div class="obs-item">
                    @php
                        $obsTexto = $observacao->observacao;
                        $obsTexto = preg_replace('/<red>(.*?)<\/red>/i', '<span style="color: #DC2626; font-weight: 600;">$1</span>', $obsTexto);
                        $obsTexto = preg_replace('/<blue>(.*?)<\/blue>/i', '<span style="color: #2563EB; font-weight: 600;">$1</span>', $obsTexto);
                        $obsTexto = preg_replace('/<green>(.*?)<\/green>/i', '<span style="color: #16A34A; font-weight: 600;">$1</span>', $obsTexto);
                    @endphp
                    {!! strip_tags($obsTexto, '<span><strong><b><i><em>') !!}
                </div>
            @endforeach
        </div>
        @endif

        <!-- Tecidos Associados -->
        @if($produto->tecidos && $produto->tecidos->count() > 0)
        <div class="info-section">
            <div class="section-title">Tecidos Associados</div>
            <table>
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Descrição</th>
                        <th>Composição</th>
                        <th class="text-center">Consumo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produto->tecidos as $tecido)
                    <tr>
                        <td>{{ $tecido->referencia }}</td>
                        <td>{{ $tecido->descricao }}</td>
                        <td>{{ $tecido->composicao ?: '-' }}</td>
                        <td class="text-center">{{ $tecido->pivot->consumo ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Localizações do Produto -->
        @if($produto->localizacoes && $produto->localizacoes->count() > 0)
        <div class="info-section">
            <div class="section-title">Localizações do Produto</div>
            @php
                $totalLocalizacoes = $produto->localizacoes->sum('pivot.quantidade');
                $quantidadeProduto = $produto->quantidade ?? 0;
                $divergencia = $totalLocalizacoes - $quantidadeProduto;
            @endphp

            @if($divergencia != 0)
                <div class="highlight-box" style="background-color: {{ $divergencia > 0 ? '#FEF3C7' : '#FEE2E2' }}; border-color: {{ $divergencia > 0 ? '#F59E0B' : '#EF4444' }}; margin-bottom: 10px;">
                    <strong style="color: {{ $divergencia > 0 ? '#92400E' : '#991B1B' }};">Atenção:</strong>
                    Total nas localizações ({{ number_format($totalLocalizacoes, 0, ',', '.') }}) está
                    {{ abs($divergencia) }} unidade(s) {{ $divergencia > 0 ? 'acima' : 'abaixo' }}
                    da quantidade pretendida ({{ number_format($quantidadeProduto, 0, ',', '.') }}).
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Localização</th>
                        <th>Ordem Produção</th>
                        <th class="text-center">Qtd</th>
                        <th class="text-center">Dt. Prev. Facção</th>
                        <th class="text-center">Dt. Envio</th>
                        <th class="text-center">Dt. Retorno</th>
                        <th class="text-center">Concl.</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produto->localizacoes as $localizacao)
                    <tr>
                        <td>
                            <span class="badge badge-purple">{{ $localizacao->nome_localizacao }}</span>
                        </td>
                        <td>
                            @if($localizacao->pivot->ordem_producao)
                                <a href="{{ $localizacao->pivot->ordem_producao_url }}" style="text-decoration: none; color: #1E40AF;">
                                    <span class="badge badge-blue">{{ $localizacao->pivot->ordem_producao }}</span>
                                </a>
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td class="text-center" style="font-weight: bold;">{{ number_format($localizacao->pivot->quantidade, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($localizacao->pivot->data_prevista_faccao)
                                {{ is_string($localizacao->pivot->data_prevista_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_prevista_faccao)->format('d/m/Y') : $localizacao->pivot->data_prevista_faccao->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($localizacao->pivot->data_envio_faccao)
                                <span style="background-color: #FEF3C7; color: #92400E; padding: 1px 4px; border-radius: 3px; font-size: 9px;">
                                    {{ is_string($localizacao->pivot->data_envio_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_envio_faccao)->format('d/m/Y') : $localizacao->pivot->data_envio_faccao->format('d/m/Y') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($localizacao->pivot->data_retorno_faccao)
                                <span style="background-color: #D1FAE5; color: #065F46; padding: 1px 4px; border-radius: 3px; font-size: 9px;">
                                    {{ is_string($localizacao->pivot->data_retorno_faccao) ? \Carbon\Carbon::parse($localizacao->pivot->data_retorno_faccao)->format('d/m/Y') : $localizacao->pivot->data_retorno_faccao->format('d/m/Y') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($localizacao->pivot->concluido == 1)
                                <span style="color: #059669; font-weight: bold;">✓</span>
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td style="font-size: 9px;">{{ Str::limit($localizacao->pivot->observacao, 30) ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background-color: #F3F4F6;">
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total nas Localizações:</td>
                        <td class="text-center" style="font-weight: bold; color: {{ $divergencia != 0 ? ($divergencia > 0 ? '#D97706' : '#DC2626') : '#059669' }};">
                            {{ number_format($totalLocalizacoes, 0, ',', '.') }}
                        </td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Histórico de Movimentações -->
        <div class="info-section">
            <div class="section-title">Histórico de Movimentações</div>
            @if($produto->movimentacoes && $produto->movimentacoes->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Data Entrada</th>
                        <th width="15%">Data Conclusão</th>
                        <th width="18%">Localização</th>
                        <th width="12%">Tipo</th>
                        <th width="15%">Situação</th>
                        <th width="5%">Concl.</th>
                        <th width="15%">Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $movimentacoesOrdenadas = collect();
                        if ($produto->movimentacoes && $produto->movimentacoes->count() > 0) {
                            $movimentacoesOrdenadas = $produto->movimentacoes->filter(function($mov) {
                                return $mov && $mov->data_entrada;
                            })->sortByDesc(function($mov) {
                                return $mov->data_entrada;
                            });
                        }
                    @endphp
                    @foreach($movimentacoesOrdenadas as $movimentacao)
                    <tr>
                        <td>{{ $movimentacao->id ?? 'N/A' }}</td>
                        <td>{{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $movimentacao->localizacao ? $movimentacao->localizacao->nome_localizacao : 'N/A' }}</td>
                        <td>{{ $movimentacao->tipo ? $movimentacao->tipo->descricao : 'N/A' }}</td>
                        <td>{{ $movimentacao->situacao ? $movimentacao->situacao->descricao : 'N/A' }}</td>
                        <td class="text-center">
                            @if($movimentacao->concluido)
                                <span style="color: #059669; font-weight: bold;">✓</span>
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td style="font-size: 9px;">{{ $movimentacao->observacao ? Str::limit($movimentacao->observacao, 25, '...') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; color: #6B7280; font-style: italic;">Nenhuma movimentação registrada para este produto.</p>
            @endif
        </div>

        <div class="footer">
            <p>Rota do Mar - Documento gerado em {{ now()->format('d/m/Y às H:i:s') }}</p>
        </div>
    </div>

</body>
</html>
