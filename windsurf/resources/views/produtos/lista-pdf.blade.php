<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
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
                <th>Marca</th>
                <th>Grupo</th>
                <th>Status</th>
                <th class="text-center">Quantidade</th>
                <th class="text-right">Preço</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
            <tr>
                <td>{{ $produto->referencia }}</td>
                <td>{{ $produto->descricao }}</td>
                <td>{{ $produto->marca->nome ?? '-' }}</td>
                <td>{{ $produto->grupoProduto->nome ?? '-' }}</td>
                <td>{{ $produto->status->nome ?? '-' }}</td>
                <td class="text-center">{{ number_format($produto->quantidade, 0, ',', '.') }}</td>
                <td class="text-right">R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página {PAGENO} de {nb}
    </div>
</body>
</html>
