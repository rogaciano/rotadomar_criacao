erDiagram
    USERS {
        bigint id PK
        bigint localizacao_id FK
        boolean is_admin
    }

    LOCALIZACOES {
        bigint id PK
        boolean ativo
        boolean faz_movimentacao
        boolean pode_ver_todas_notificacoes
    }

    PRODUTOS {
        bigint id PK
        bigint marca_id FK
        bigint estilista_id FK
        bigint grupo_id FK
        bigint status_id FK
        bigint direcionamento_comercial_id FK
        bigint produto_original_id FK
    }

    MARCAS {
        bigint id PK
    }

    ESTILISTAS {
        bigint id PK
        bigint marca_id FK
    }

    GRUPOS {
        bigint id PK
    }

    STATUS {
        bigint id PK
    }

    DIRECIONAMENTOS_COMERCIAIS {
        bigint id PK
    }

    MOVIMENTACOES {
        bigint id PK
        bigint produto_id FK
        bigint localizacao_id FK
        bigint tipo_id FK
        bigint situacao_id FK
        bigint created_by FK
        datetime data_entrada
        datetime data_saida
        datetime data_devolucao
    }

    NOTIFICACOES {
        bigint id PK
        bigint movimentacao_id FK
        bigint localizacao_id FK
        bigint visualizada_por FK
        datetime visualizada_em
    }

    PRODUTO_LOCALIZACAO {
        bigint id PK
        bigint produto_id FK
        bigint localizacao_id FK
        int quantidade
        date data_prevista_faccao
        date data_envio_faccao
        date data_retorno_faccao
    }

    PRODUTO_ANEXOS {
        bigint id PK
        bigint produto_id FK
    }

    PRODUTO_OBSERVACAO {
        bigint id PK
        bigint produto_id FK
        bigint usuario_id FK
    }

    PRODUTO_COR {
        bigint id PK
        bigint produto_id FK
    }

    TECIDOS {
        bigint id PK
    }

    PRODUTO_TECIDO {
        bigint produto_id FK
        bigint tecido_id FK
        decimal consumo
    }

    GROUPS {
        bigint id PK
    }

    PERMISSIONS {
        bigint id PK
    }

    USER_GROUP {
        bigint user_id FK
        bigint group_id FK
    }

    GROUP_PERMISSION {
        bigint group_id FK
        bigint permission_id FK
    }

    USER_PERMISSIONS {
        bigint id PK
        bigint user_id FK
        bigint permission_id FK
        boolean can_create
        boolean can_read
        boolean can_update
        boolean can_delete
    }

    %% RELACIONAMENTOS

    LOCALIZACOES ||--o{ USERS : "usuarios"
    USERS }o--|| LOCALIZACOES : "localizacao"

    MARCAS ||--o{ PRODUTOS : "produtos"
    ESTILISTAS ||--o{ PRODUTOS : "produtos"
    GRUPOS ||--o{ PRODUTOS : "produtos"
    STATUS ||--o{ PRODUTOS : "produtos"
    DIRECIONAMENTOS_COMERCIAIS ||--o{ PRODUTOS : "produtos"

    PRODUTOS ||--o{ MOVIMENTACOES : "movimentacoes"
    LOCALIZACOES ||--o{ MOVIMENTACOES : "movimentacoes"
    USERS ||--o{ MOVIMENTACOES : "criadoPor"

    MOVIMENTACOES ||--o{ NOTIFICACOES : "notificacoes"
    LOCALIZACOES ||--o{ NOTIFICACOES : "notificacoes"
    USERS ||--o{ NOTIFICACOES : "visualizadaPor"

    PRODUTOS ||--o{ PRODUTO_ANEXOS : "anexos"
    PRODUTOS ||--o{ PRODUTO_OBSERVACAO : "observacoes"
    USERS ||--o{ PRODUTO_OBSERVACAO : "observacoes"
    PRODUTOS ||--o{ PRODUTO_COR : "cores"

    PRODUTOS ||--o{ PRODUTO_LOCALIZACAO : "pivot"
    LOCALIZACOES ||--o{ PRODUTO_LOCALIZACAO : "pivot"

    PRODUTOS ||--o{ PRODUTO_TECIDO : "pivot"
    TECIDOS ||--o{ PRODUTO_TECIDO : "pivot"

    USERS ||--o{ USER_GROUP : "pivot"
    GROUPS ||--o{ USER_GROUP : "pivot"

    GROUPS ||--o{ GROUP_PERMISSION : "pivot"
    PERMISSIONS ||--o{ GROUP_PERMISSION : "pivot"

    USERS ||--o{ USER_PERMISSIONS : "userPermissions"
    PERMISSIONS ||--o{ USER_PERMISSIONS : "userPermissions"