<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        /* Ajuste de larguras das colunas (ajustado para 11 colunas) */
        th:nth-child(1), td:nth-child(1) { width: 9%; }  /* Referência */
        th:nth-child(2), td:nth-child(2) { width: 18%; } /* Descrição */
        th:nth-child(3), td:nth-child(3) { width: 8%; }  /* Data Prev. Produção */
        th:nth-child(4), td:nth-child(4) { width: 9%; }  /* 1ª Data Prev. Facção */
        th:nth-child(5), td:nth-child(5) { width: 10%; } /* Marca */
        th:nth-child(6), td:nth-child(6) { width: 10%; } /* Grupo */
        th:nth-child(7), td:nth-child(7) { width: 13%; } /* Direcionamento Comercial */
        th:nth-child(8), td:nth-child(8) { width: 8%; }  /* Status */
        th:nth-child(9), td:nth-child(9) { width: 5%; }  /* Concluído */
        th:nth-child(10), td:nth-child(10) { width: 6%; } /* Localização */
        th:nth-child(11), td:nth-child(11) { width: 4%; } /* Situação */

        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
        /* Estilo para o ícone de concluído */
        .concluido {
            color: green;
            font-weight: bold;
            font-size: 16px;
        }
        /* Estilo para o ícone de não concluído */
        .nao-concluido {
            color: red;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Produtos</h1>
        <p>Gerado em: {{ date('d/m/Y H:i:s') }}</p>
        <p>Total de produtos: {{ $produtos->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Referência</th>
                <th>Descrição</th>
                <th>Data Prev. Produção</th>
                <th>1ª Data Prev. Facção</th>
                <th>Marca</th>
                <th>Grupo</th>
                <th>Direcionamento Comercial</th>
                <th>Status</th>
                <th class="text-center">Concluído</th>
                <th>Localização</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
            <tr>
                <td>{{ $produto->referencia }}</td>
                <td>{{ $produto->descricao }}</td>
                <td>
                    {{ $produto->data_prevista_producao_mes_ano ?? ($produto->data_prevista_producao ? date('m/Y', strtotime($produto->data_prevista_producao)) : 'N/A') }}
                </td>
                <td>
                    @if(!empty($produto->primeira_data_prevista_faccao))
                        {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
                    @else
                        <span style="color:#999; font-size:10px;">Sem data</span>
                    @endif
                </td>
                <td>{{ $produto->marca->nome_marca ?? '-' }}</td>
                <td>{{ $produto->grupoProduto->descricao ?? '-' }}</td>
                <td>{{ $produto->direcionamentoComercial->descricao ?? 'Sem direcionamento' }}</td>
                <td>{{ $produto->status->descricao ?? '-' }}</td>
                <td class="text-center">
                    <span class="{{ $produto->concluido_atual ? 'concluido' : 'nao-concluido' }}">
                        {{ $produto->concluido_atual ? '✓' : '✗' }}
                    </span>
                </td>
                <td>
                    {{ $produto->localizacao_atual ? $produto->localizacao_atual->nome_localizacao : 'Não localizado' }}
                </td>
                <td>
                    {{ $produto->situacao_atual->descricao ?? 'Sem situação' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página {PAGENO} de {nb}
    </div>
</body>
</html>
