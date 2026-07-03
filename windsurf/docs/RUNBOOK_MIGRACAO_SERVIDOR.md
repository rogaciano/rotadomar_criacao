# Runbook — atualizar banco do servidor (migrations pendentes)

> **DEPLOY EXECUTADO COM SUCESSO em 02/07/2026 ~22h.** As 51 migrations
> rodaram sem erro no banco `criacao`, a auditoria (`etapas:auditar-logistica`)
> confirmou as 7 etapas + handoff + 6 transições, o enum de
> `coletas_logisticas.status` inclui `entregue` e o teste no navegador passou.
> Ocorrências da janela (não previstas no ensaio, todas resolvidas):
> - `windsurf/public/motorista/` existia solto no servidor e bloqueou o
>   checkout — movido para `/root/deploy_backup_20260703/motorista_public_old`;
> - `composer install` exigiu `--ignore-platform-req=php` (o `laravel/ai`
>   v0.3.2 do lock pede PHP 8.3 e o VPS tem 8.2.28; pacote não é usado pelo
>   código) e `COMPOSER_ALLOW_SUPERUSER=1` (rodando como root);
> - build do frontend feito no próprio VPS (Node v20.20.2 disponível).
>
> Backlog pós-deploy: remover `laravel/ai` do composer.json (ou subir PHP para
> 8.3), apagar `MovimentacaoController_temp.php` do repositório, mover
> Telescope para carregar apenas em ambiente local.

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

## Ambiente confirmado (02/07/2026)

- Produção: `sistemasrota.com.br` → DocumentRoot
  `/var/www/html/criacao/windsurf/public` (o app do repositório git).
- Banco de produção: `criacao` (MySQL local do VPS). Os dois `.env` do
  servidor apontam para ele.
- O Laravel solto na raiz `/var/www/html/criacao/` (app/, config/, artisan…)
  está **fora do git** e não é servido — lixo de deploy antigo. Arquivar depois.
- App do motorista (PWA): servido de `/var/www/html/motorista-app` (porta
  3000, `motorista.conf`) — pasta FORA do repositório. Sincronizar manualmente
  no deploy (passo 3).
- O checkout do servidor está na branch local `master`
  (= `feature/backend-improvements`). O deploy deve trocar para `main`
  **somente depois** que o merge de integração
  (`integracao/main-backend`) estiver na `main` do GitHub.
- As centenas de arquivos "modified" no `git status` do servidor são ruído de
  permissões (`core.fileMode=true`) + 1 linha em `navigation.blade.php`.
  Conferir essa linha antes do checkout: `git diff windsurf/resources/views/layouts/navigation.blade.php`.

## Passo a passo

### 1. Backup completo (obrigatório)

```bash
mysqldump -u root -p --single-transaction --routines --triggers criacao > backup_pre_migracao_$(date +%Y%m%d_%H%M).sql
```

Confirme o tamanho do arquivo antes de prosseguir.

### 2. Colocar a aplicação em manutenção

```bash
cd /var/www/html/criacao/windsurf
php artisan down
```

### 3. Atualizar o código

```bash
cd /var/www/html/criacao
git config core.fileMode false   # remove o ruído de permissões do status
git fetch origin
git status                # deve sobrar pouca coisa; avaliar/stash antes do checkout
git stash                 # se restar modificação local (ex.: navigation.blade.php)
git checkout main         # cria main local rastreando origin/main
cd windsurf
composer install --no-interaction   # com dev: TelescopeServiceProvider exige o pacote
npm install && npm run build        # requer Node no VPS; sem Node, copiar public/build gerado localmente
# Sincronizar PWA do motorista (pasta servida fica fora do repo):
rsync -av --delete /var/www/html/criacao/motorista-app/ /var/www/html/motorista-app/
```

Garanta que `database/migrations/` contém tudo até
`2026_07_02_100000_add_entregue_status_to_coletas_logisticas`.

> Pendência conhecida (backlog 2.3): Telescope está em `require-dev` e o
> provider é registrado sempre — por isso o `composer install` acima NÃO usa
> `--no-dev`. Endurecer isso depois do deploy.

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

Além das migrations ensaiadas, o merge de integração traz
`2026_03_24_120000_add_produto_observacoes_permission` (da feature branch) —
validada no ensaio local em 02/07/2026.

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
