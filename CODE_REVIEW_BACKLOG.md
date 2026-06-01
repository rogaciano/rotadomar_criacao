# Backlog — Code review (Rota do Mar)

Documento gerado a partir do code review de **28/05/2026**. Use como guia de trabalho na próxima semana.

**Escopo principal:** aplicação Laravel em `windsurf/` + API/PWA em `motorista-app/`.

---

## Prioridade 1 — Segurança (crítico)

### 1.1 Leitura arbitrária de arquivos — `ArquivoController`

- **Arquivo:** `windsurf/app/Http/Controllers/ArquivoController.php`
- **Rota:** `GET /arquivo/rede` (`arquivo.rede`) — qualquer usuário autenticado
- **Problema:** parâmetro `path` é usado direto em `file_exists()` / `response()->file()` sem whitelist.
- **Impacto:** leitura de arquivos do servidor (inclui UNC usados em anexos de movimentação).
- **Tarefas:**
  - [ ] Definir root permitido em config (ex.: share de rede da empresa).
  - [ ] Validar com `realpath()` + prefixo; rejeitar `..` e paths fora do root.
  - [ ] Opcional: resolver path a partir de ID/registro no banco, não do cliente.
  - [ ] Teste manual: tentar `path` com `C:\Windows\...` ou `../../../.env` (deve falhar).

### 1.2 Path traversal em logs — `LogController`

- **Arquivo:** `windsurf/app/Http/Controllers/LogController.php`
- **Rotas:** `logs/{filename}` (show, download, destroy) — só admin
- **Problema:** `$filename` concatenado em `storage_path('logs/' . $filename)` sem sanitização.
- **Tarefas:**
  - [ ] Usar `basename($filename)` e/ou allowlist de arquivos listados em `index()`.
  - [ ] Garantir que `destroy` não apague arquivos fora de `storage/logs`.
  - [ ] Teste: `logs/../../../.env` (deve 404/403).

### 1.3 IDOR em anexos de produto — `ProdutoAnexoController::show`

- **Arquivo:** `windsurf/app/Http/Controllers/ProdutoAnexoController.php`
- **Rota:** `GET produtos/anexos/{anexo}`
- **Problema:** `show()` não verifica `canRead('produtos')` nem escopo por localização/produto.
- **Tarefas:**
  - [ ] Adicionar mesma autorização de `store`/`destroy` (ou `canRead` + regra de localização).
  - [ ] Verificar que o anexo pertence a um produto que o usuário pode ver.
  - [ ] Teste: usuário A não acessa anexo do produto restrito ao usuário B.

---

## Prioridade 2 — Segurança (alto)

### 2.1 API do motorista sem papel dedicado

- **Arquivo:** `windsurf/app/Http/Controllers/Api/MotoristaApiController.php`
- **Rotas:** `windsurf/routes/api.php` — `POST /api/motorista/login` sem throttle
- **Problema:** qualquer `User` válido obtém token Sanctum e pode agendar coletas / avançar etapas.
- **Tarefas:**
  - [ ] Criar flag, grupo ou permissão `motorista` (definir critério com negócio).
  - [ ] Bloquear login API se usuário não for motorista.
  - [ ] Aplicar `throttle` no login (ex.: 5 tentativas/minuto, como no web).
  - [ ] Revisar endpoints `disponiveis`, `agendar`, `confirmarChegada`, `confirmarEntrega` após restrição.
  - [ ] Documentar no `motorista-app/README.md`.

### 2.2 `is_admin` em `$fillable` do `User`

- **Arquivo:** `windsurf/app/Models/User.php`
- **Tarefas:**
  - [ ] Remover `is_admin` de `$fillable`.
  - [ ] Garantir que `UserController` continue setando `is_admin` explicitamente.
  - [ ] Buscar no projeto `User::create($request->all())` / `update($request->all())` perigosos.

### 2.3 Laravel Telescope em produção

- **Arquivos:** `windsurf/app/Providers/TelescopeServiceProvider.php`, `bootstrap/providers.php`
- **Problema:** provider ativo; gate com lista de e-mails vazia; risco se `APP_ENV=local` em prod.
- **Tarefas:**
  - [ ] Registrar `TelescopeServiceProvider` apenas em `local` (ou `App::environment('local')`).
  - [ ] Preencher gate com e-mails de admins OU desabilitar `TELESCOPE_ENABLED` em prod.
  - [ ] Confirmar que `/telescope` não responde em staging/produção.

### 2.4 Dump SQL no repositório

- **Arquivo:** `backup_db_dev.sql` (raiz do repo)
- **Tarefas:**
  - [ ] Remover do Git (`git rm --cached`) se não for necessário versionado.
  - [ ] Adicionar `*.sql` de backup ao `.gitignore` (com exceções se precisar).
  - [ ] Definir processo de backup fora do VCS.

---

## Prioridade 3 — Bugs e qualidade (médio)

### 3.1 Redirect quebrado após criar Status

- **Arquivo:** `windsurf/app/Http/Controllers/StatusController.php` — método `store`
- **Problema:** `redirect()->route('status.show', $data['id'])` — `id` não existe no request após `create`.
- **Tarefas:**
  - [ ] Usar `$status = Status::create(...)` e redirect com `$status->id`.
  - [ ] Teste manual: criar status e verificar redirect.

### 3.2 Mensagens de exceção na API

- **Arquivo:** `MotoristaApiController` (vários `catch` com `$e->getMessage()` na resposta JSON)
- **Tarefas:**
  - [ ] Resposta genérica ao cliente; log completo no servidor.
  - [ ] Padronizar formato de erro JSON.

### 3.3 Tokens Sanctum sem expiração

- **Arquivo:** `windsurf/config/sanctum.php` — `'expiration' => null`
- **Tarefas:**
  - [ ] Definir expiração (ex.: 7 ou 30 dias) para tokens `motorista-pwa`.
  - [ ] Validar fluxo de re-login no PWA.

### 3.4 Logging excessivo em middleware

- **Arquivo:** `windsurf/app/Http/Middleware/CheckUserAccessSchedule.php`
- **Tarefas:**
  - [ ] Reduzir logs `debug` em produção ou usar canal dedicado.
  - [ ] Evitar logar `session_id` em produção.

### 3.5 `CheckPermission` — redirect inválido

- **Arquivo:** `windsurf/app/Http/Middleware/CheckPermission.php`
- **Problema:** `redirect()->back()` em GET direto (sem referer).
- **Tarefas:**
  - [ ] Usar `abort(403)` ou redirect fixo para `dashboard`.

---

## Prioridade 4 — Manutenção e testes (médio/baixo)

### 4.1 Cobertura de testes

- **Hoje:** apenas testes Breeze em `windsurf/tests/` (auth/perfil).
- **Tarefas sugeridas (mínimo viável):**
  - [ ] Teste: usuário sem permissão recebe 403 em rota `permission:produtos`.
  - [ ] Teste: API motorista — login negado para usuário não-motorista.
  - [ ] Teste: `ArquivoController` rejeita path fora do root.
  - [ ] Teste: `LogController` rejeita path traversal.
  - [ ] Teste: `ProdutoAnexoController::show` respeita autorização.

### 4.2 Limpeza de código morto

- [ ] Avaliar remoção/arquivo de: `MovimentacaoController_temp.php`, `windsurf/temp_check.php`, `windsurf/debug_filters.php`, `windsurf/backup/ProdutoController.php`.
- [ ] Remover blocos DEBUG em views (ex.: `resources/views/localizacao-capacidade/show.blade.php`).

### 4.3 Configuração de ambiente

- [ ] Revisar `.env.example`: `APP_DEBUG=false` como padrão documentado para produção.
- [ ] Checklist de deploy: `APP_ENV`, `APP_DEBUG`, Telescope, `LOG_LEVEL`.

### 4.4 Pasta `exportar e importar banco de tiago/venv`

- [ ] Confirmar se `venv/` está no Git; se sim, adicionar ao `.gitignore`.

---

## Referência rápida — arquivos tocados

| Área | Caminhos |
|------|----------|
| Arquivo rede | `app/Http/Controllers/ArquivoController.php`, `routes/web.php`, `app/Models/Movimentacao.php` |
| Logs admin | `app/Http/Controllers/LogController.php` |
| Anexos | `app/Http/Controllers/ProdutoAnexoController.php` |
| API motorista | `app/Http/Controllers/Api/MotoristaApiController.php`, `routes/api.php`, `motorista-app/` |
| Auth/permissões | `app/Models/User.php`, `app/Http/Middleware/CheckPermission.php`, `AdminMiddleware.php` |
| Status (bug) | `app/Http/Controllers/StatusController.php` |
| Telescope | `app/Providers/TelescopeServiceProvider.php` |

---

## Sugestão de divisão na semana

| Dia | Foco |
|-----|------|
| Seg | 1.1 + 1.2 (ArquivoController + LogController) |
| Ter | 1.3 + testes manuais de anexos |
| Qua | 2.1 API motorista (papel + throttle) |
| Qui | 2.2–2.4 + 3.1 Status redirect |
| Sex | 4.1 testes mínimos + limpeza 4.2 |

---

## Notas

- Registro público já está **desativado** em `routes/auth.php` — manter assim.
- Estrutura de rotas com `permission:*` está adequada; reforçar autorização nos pontos listados acima.
- Este arquivo pode ser atualizado marcando `[x]` conforme as tarefas forem concluídas.
