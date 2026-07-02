# Runbook — atualizar banco do servidor (migrations pendentes)

Procedimento validado em 02/07/2026 em DUAS cópias do banco do servidor
restauradas localmente:

1. `rotadomar_produtos` — primeira cópia (com sobras do banco de dev antigo);
2. `rotadomar_produtos_copia` — cópia limpa, estado puro do servidor.

Nas duas o `migrate` passou limpo e o schema final ficou idêntico ao gabarito
de desenvolvimento (`cols_local.txt`).

## Contexto

- O servidor está ~50 migrations atrás do repositório (último registro:
  `2026_03_23_105955_add_is_faccao_to_users_table`).
- Quase todas as migrations pendentes têm guards (`hasTable`/`hasColumn`) e são
  seguras de rodar mesmo com estruturas parcialmente existentes.
- **Exceção:** `2026_05_25_070740_create_telescope_entries_table` não tem guard.
  Se as tabelas `telescope_*` já existirem no servidor, ela quebra o batch —
  nesse caso deve ser registrada como aplicada (passo 4).
  **Confirmado no ensaio com a cópia limpa (`rotadomar_produtos_copia`): o
  servidor NÃO tem essas tabelas** e a migration rodou normalmente, sem
  reconciliação manual. O passo 4 fica como salvaguarda barata.
- A migration nova `2026_07_02_100000_add_entregue_status_to_coletas_logisticas`
  adiciona o valor `entregue` ao enum de `coletas_logisticas.status`
  (obrigatória — sem ela a confirmação de entrega da logística falha).

## Passo a passo

### 1. Backup completo (obrigatório)

```bash
mysqldump -u USUARIO -p --single-transaction --routines --triggers NOME_DO_BANCO > backup_pre_migracao_$(date +%Y%m%d_%H%M).sql
```

Confirme o tamanho do arquivo antes de prosseguir.

### 2. Colocar a aplicação em manutenção

```bash
cd /caminho/do/projeto
php artisan down
```

### 3. Atualizar o código (deploy do repositório atualizado)

Garanta que a pasta `database/migrations/` contém tudo até
`2026_07_02_100000_add_entregue_status_to_coletas_logisticas`.

### 4. Verificar Telescope

```sql
SHOW TABLES LIKE 'telescope%';
```

- **Se não existirem** (cenário esperado no servidor), não faça nada — a
  migration cria as tabelas normalmente.
- **Se as tabelas existirem**, registre a migration como aplicada antes do
  migrate:

```sql
INSERT INTO migrations (migration, batch)
SELECT '2026_05_25_070740_create_telescope_entries_table', COALESCE(MAX(batch),0)+1 FROM migrations m
WHERE NOT EXISTS (SELECT 1 FROM migrations WHERE migration = '2026_05_25_070740_create_telescope_entries_table');
```

### 5. Ensaiar e executar

```bash
php artisan migrate --pretend   # conferir o SQL que será executado
php artisan migrate
```

### 6. Validar

```bash
php artisan etapas:auditar-logistica
```

Resultado esperado:

- 7 etapas logísticas ativas (agendamento → chegada_produto_fabrica);
- `Agendamento` marcado como `[INÍCIO LOG]`;
- transição `Acabamento → início logística` OK;
- 6 transições internas encadeadas.

Conferir também o enum:

```sql
SELECT COLUMN_TYPE FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coletas_logisticas' AND COLUMN_NAME = 'status';
-- esperado: enum('agendado','em_transito','entregue','finalizado','cancelado')
```

### 7. Liberar

```bash
php artisan up
```

## Efeitos colaterais conhecidos (observados no ensaio)

- `2026_02_06_180000_fix_produto_localizacao_historico_etapas_fk` **apaga
  registros órfãos** de `produto_localizacao_historico_etapas` (no ensaio: 23
  linhas) antes de criar a FK. É intencional, mas fica registrado no backup.
- `2026_06_01_150000_reorganize_logistica_etapas_flow` **altera dados** de
  `etapas_producao` e `etapas_transicoes` (cria/renomeia as 7 etapas e refaz as
  transições). Produtos parados em etapas logísticas legadas são apontados pelo
  comando de auditoria.
- As migrations de setembro/2025 (`produto_componentes`, `produto_combinacao`)
  incluem migração de dados; rodaram sem erro nos dois ensaios.
- A view `vw_movimentacoes_status_dias` (script manual `filtro_status_dias.sql`)
  existia no banco de dev antigo mas **não é referenciada pelo código** — o
  filtro `status_dias` é calculado no PHP. Não é necessário criá-la no servidor.

## Rollback

Restaurar o backup do passo 1:

```bash
mysql -u USUARIO -p NOME_DO_BANCO < backup_pre_migracao_XXXX.sql
```
