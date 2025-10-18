<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Capacidade - {{ $mesNome }}/{{ $ano }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20px;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #6B7280;
        }
        
        .localizacao-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .localizacao-header {
            background-color: #F3F4F6;
            padding: 10px;
            border-left: 4px solid #3B82F6;
            margin-bottom: 10px;
        }
        
        .localizacao-title {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stat-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #E5E7EB;
            width: 25%;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .stat-value {
            font-size: 13px;
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
            margin-top: 15px;
        }
        
        .produtos-title {
            font-size: 11px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        th {
            background-color: #F9FAFB;
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            color: #6B7280;
            text-transform: uppercase;
            border: 1px solid #E5E7EB;
        }
        
        td {
            padding: 5px 4px;
            font-size: 9px;
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
        
        .observacao {
            font-size: 8px;
            color: #4B5563;
            line-height: 1.3;
        }
        
        .no-produtos {
            text-align: center;
            padding: 20px;
            color: #9CA3AF;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 8px;
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
                <div class="localizacao-title">{{ $dado['localizacao']->nome_localizacao }}</div>
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
                    <div class="produtos-title">Produtos Previstos ({{ $dado['produtos']->count() }})</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 8%;">Ref</th>
                                <th style="width: 18%;">Descrição</th>
                                <th style="width: 12%;">Marca</th>
                                <th style="width: 15%;">Grupo</th>
                                <th style="width: 27%;">Observações</th>
                                <th style="width: 10%;" class="text-center">Qtd</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dado['produtos'] as $produto)
                                <tr>
                                    <td class="font-semibold">{{ $produto->referencia }}</td>
                                    <td>{{ $produto->descricao }}</td>
                                    <td>
                                        @if($produto->marca)
                                            @if($produto->marca->cor_fundo && $produto->marca->cor_fonte)
                                                <span style="background-color: {{ $produto->marca->cor_fundo }}; color: {{ $produto->marca->cor_fonte }}; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                                                    {{ $produto->marca->nome_marca }}
                                                </span>
                                            @else
                                                {{ $produto->marca->nome_marca }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $produto->grupoProduto->descricao ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $obs = \App\Models\ProdutoObservacao::where('produto_id', $produto->id)->get();
                                        @endphp
                                        @if($obs->count() > 0)
                                            @foreach($obs as $observacao)
                                                <div class="observacao">
                                                    {{ Str::limit($observacao->observacao, 120) }}
                                                </div>
                                                @if(!$loop->last)
                                                    <hr style="margin: 2px 0; border: none; border-top: 1px solid #E5E7EB;">
                                                @endif
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center font-semibold">
                                        {{ number_format($produto->quantidade_alocada ?? $produto->quantidade, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $produto->status->descricao ?? 'N/A' }}</td>
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
