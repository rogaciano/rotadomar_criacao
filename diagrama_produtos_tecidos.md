# Diagrama de Produtos e Tecidos
## Descrição do Sistema


```mermaid

classDiagram
    %% Foco em Produtos e Tecidos
    class Produto {
        +string referencia
        +string descricao
        +date data_cadastro
        +date data_prevista_producao
        +int quantidade
        +decimal preco_atacado
        +decimal preco_varejo
    }

    class ProdutoCor {
        +string cor
        +string codigo_cor
        +int quantidade
    }

    class ProdutoCombinacao {
        +string descricao
        +int quantidade_pretendida
        +string observacoes
    }

    class ProdutoCombinacaoComponente {
        +string cor
        +string codigo_cor
        +decimal consumo
    }

    class Tecido {
        +string descricao
        +string referencia
        +date data_cadastro
        +boolean ativo
        +decimal quantidade_estoque
        +getNecessidadeTotalAttribute()
        +getTotalEstoquePorCoresAttribute()
    }

    class TecidoCorEstoque {
        +string cor
        +string codigo_cor
        +decimal quantidade
        +decimal quantidade_pretendida
        +getNecessidadeAttribute()
        +getSaldoAttribute()
        +getProdutosPossiveisAttribute()
    }

    class Movimentacao {
        +datetime data_entrada
        +datetime data_saida
        +datetime data_devolucao
        +string observacao
        +boolean concluido
    }

    class Localizacao {
        +string nome
        +int prazo
    }

    %% Relacionamentos
    Produto "1" --> "*" ProdutoCor : tem
    Produto "1" --> "*" ProdutoCombinacao : tem
    Produto "*" --> "*" Tecido : usa com consumo
    Produto "1" --> "*" Movimentacao : tem

    ProdutoCombinacao "1" --> "*" ProdutoCombinacaoComponente : tem
    ProdutoCombinacaoComponente "*" --> "1" Tecido : usa

    Tecido "1" --> "*" TecidoCorEstoque : tem

    Movimentacao "*" --> "1" Localizacao : está em

    