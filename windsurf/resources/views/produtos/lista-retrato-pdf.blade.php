<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Produtos - Retrato</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }
        .info {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 4px;
            word-wrap: break-word;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 9px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Larguras das colunas para Retrato (A4 Portrait ~ 190mm úteis) */
        .col-ref { width: 45px; }
        .col-desc { width: auto; }
        .col-data { width: 55px; }
        .col-marca { width: 80px; }
        .col-dir { width: 70px; }
        .col-concluido { width: 35px; }
        .col-loc { width: 65px; }
        .col-sit { width: 60px; }

        .sub-info {
            display: block;
            font-size: 8.5px;
            font-weight: bold;
            margin-top: 3px;
            color: #000;
        }
        .header-sub {
            display: block;
            font-size: 7px;
            font-weight: bold;
            color: #444;
            margin-top: 2px;
        }

        .concluido { color: green; font-weight: bold; }
        .nao-concluido { color: red; font-weight: bold; }

        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            height: 0.5cm;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Produtos</h1>
        <div class="info">
            Gerado em: {{ date('d/m/Y H:i:s') }} | Total: {{ $produtos->count() }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-ref">Referência</th>
                <th class="col-desc">
                    DESCRICAO
                    <span class="header-sub">DESCRIÇÃO GRUPO DE PRODUTOS</span>
                </th>
                <th class="col-data">Data Prev.<br>Produção</th>
                <th class="col-data">1ª Data Prev.<br>Facção</th>
                <th class="col-marca">
                    MARCA
                    <span class="header-sub">STATUS</span>
                </th>
                <th class="col-dir">Direcionamento<br>Comercial</th>
                <th class="col-concluido text-center">Concl.</th>
                <th class="col-loc">Localização</th>
                <th class="col-sit">Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
            <tr>
                <td class="text-center"><strong>{{ $produto->referencia }}</strong></td>
                <td>
                    {{ $produto->descricao }}
                    <span class="sub-info">{{ $produto->grupoProduto->descricao ?? '-' }}</span>
                </td>
                <td class="text-center">
                    {{ $produto->data_prevista_producao_mes_ano ?? ($produto->data_prevista_producao ? date('m/Y', strtotime($produto->data_prevista_producao)) : 'N/A') }}
                </td>
                <td class="text-center">
                    @if(!empty($produto->primeira_data_prevista_faccao))
                        {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{ $produto->marca->nome_marca ?? '-' }}
                    <span class="sub-info">{{ $produto->status->descricao ?? '-' }}</span>
                </td>
                <td>{{ $produto->direcionamentoComercial->descricao ?? '-' }}</td>
                <td class="text-center">
                    <span class="{{ $produto->concluido_atual ? 'concluido' : 'nao-concluido' }}">
                        {{ $produto->concluido_atual ? 'Sim' : 'Não' }}
                    </span>
                </td>
                <td>
                    {{ $produto->localizacao_atual ? $produto->localizacao_atual->nome_localizacao : 'Não localizado' }}
                </td>
                <td>
                    {{ $produto->situacao_atual->descricao ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Grupo Rota do Mar - Sistema de Gestão de Produção | Página {PAGENO} de {nb}
    </div>
</body>
</html>
