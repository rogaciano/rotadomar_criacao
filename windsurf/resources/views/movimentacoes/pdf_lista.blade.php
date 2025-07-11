<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Movimentações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        .container {
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 5px;
            font-size: 11px;
        }
        td {
            padding: 4px;
            font-size: 10px;
        }
        .filters-summary {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filter-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">ROTA DO AMAR - LISTA DE MOVIMENTAÇÕES</div>
            <div>Data do relatório: {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Resumo dos filtros aplicados -->
        <div class="filters-summary">
            <div style="font-weight: bold; margin-bottom: 5px;">Filtros aplicados:</div>
            @php
                $hasFilters = false;
            @endphp

            @if($request->filled('referencia'))
                <div class="filter-item">
                    <span class="filter-label">Referência:</span> {{ $request->referencia }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('produto_id'))
                <div class="filter-item">
                    <span class="filter-label">Produto:</span> 
                    @php 
                        $produtoSelecionado = $produtos->firstWhere('id', $request->produto_id);
                    @endphp
                    {{ $produtoSelecionado ? $produtoSelecionado->referencia . ' - ' . $produtoSelecionado->descricao : 'N/A' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('tipo_id'))
                <div class="filter-item">
                    <span class="filter-label">Tipo:</span> 
                    @php 
                        $tipoSelecionado = $tipos->firstWhere('id', $request->tipo_id);
                    @endphp
                    {{ $tipoSelecionado ? $tipoSelecionado->descricao : 'N/A' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('situacao_id'))
                <div class="filter-item">
                    <span class="filter-label">Situação:</span> 
                    @php 
                        $situacaoSelecionada = $situacoes->firstWhere('id', $request->situacao_id);
                    @endphp
                    {{ $situacaoSelecionada ? $situacaoSelecionada->descricao : 'N/A' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('localizacao_id'))
                <div class="filter-item">
                    <span class="filter-label">Localização:</span> 
                    @php 
                        $localizacaoSelecionada = $localizacoes->firstWhere('id', $request->localizacao_id);
                    @endphp
                    {{ $localizacaoSelecionada ? $localizacaoSelecionada->nome_localizacao : 'N/A' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('data_inicio') || $request->filled('data_fim'))
                <div class="filter-item">
                    <span class="filter-label">Período:</span> 
                    {{ $request->filled('data_inicio') ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Início' }}
                    até 
                    {{ $request->filled('data_fim') ? \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') : 'Hoje' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if($request->filled('comprometido'))
                <div class="filter-item">
                    <span class="filter-label">Comprometido:</span> 
                    {{ $request->comprometido == 1 ? 'Sim' : 'Não' }}
                </div>
                @php $hasFilters = true; @endphp
            @endif

            @if(!$hasFilters)
                <div class="filter-item">
                    Nenhum filtro aplicado
                </div>
            @endif
        </div>

        <!-- Total de registros -->
        <div style="margin-bottom: 10px; font-weight: bold;">
            Total de registros: {{ $totalRegistros }}
        </div>

        <!-- Tabela de movimentações -->
        @if(count($movimentacoes) > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Referência</th>
                        <th width="10%">Produto</th>
                        <th width="10%">Data Entrada</th>
                        <th width="10%">Data Saída</th>
                        <th width="15%">Localização</th>
                        <th width="10%">Tipo</th>
                        <th width="10%">Situação</th>
                        <th width="20%">Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimentacoes as $movimentacao)
                        <tr>
                            <td>{{ $movimentacao->id }}</td>
                            <td>{{ $movimentacao->produto->referencia ?? 'N/A' }}</td>
                            <td>{{ Str::limit($movimentacao->produto->descricao ?? 'N/A', 20) }}</td>
                            <td>{{ $movimentacao->data_entrada ? $movimentacao->data_entrada->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>{{ $movimentacao->data_saida ? $movimentacao->data_saida->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $movimentacao->localizacao->nome_localizacao ?? 'N/A' }}</td>
                            <td>{{ $movimentacao->tipo->descricao ?? 'N/A' }}</td>
                            <td>{{ $movimentacao->situacao->descricao ?? 'N/A' }}</td>
                            <td>{{ Str::limit($movimentacao->observacao, 40, '...') ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div>
                Nenhuma movimentação encontrada com os filtros aplicados.
            </div>
        @endif

        <div class="footer">
            <p>ROTA DO AMAR - Documento gerado em {{ now()->format('d/m/Y às H:i:s') }} - Página <span class="page-number"></span></p>
        </div>
    </div>
</body>
</html>
