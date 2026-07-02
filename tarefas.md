# Tarefas — Code review Rota do Mar

Agenda da semana **02/06/2026 a 06/06/2026**, alinhada ao [CODE_REVIEW_BACKLOG.md](./CODE_REVIEW_BACKLOG.md).

**Como usar:** no dia indicado, abra uma sessão no Cursor, cole o **prompt do dia** e execute. Ao terminar, marque `[x]` no backlog e anote o status em **Feito?** abaixo.

| Legenda | Significado |
|---------|-------------|
| ⬜ | Pendente |
| ✅ | Concluído |
| ⏸️ | Pausado / remarcado |

---

## Segunda-feira — 02/06/2026

**Foco:** Prioridade 1.1 + 1.2 — `ArquivoController` e `LogController`

**Itens do backlog:** 1.1, 1.2

**Feito?** ⬜

### Prompt

```
Leia CODE_REVIEW_BACKLOG.md e implemente os itens 1.1 e 1.2 (Prioridade 1 — segurança crítica):

1.1 ArquivoController — whitelist de root, realpath(), bloquear path traversal e paths fora do share configurado.
1.2 LogController — basename/allowlist em show, download e destroy.

Escopo: windsurf/. Siga as convenções existentes do projeto. Ao final:
- marque [x] nos itens concluídos em CODE_REVIEW_BACKLOG.md;
- liste o que testar manualmente;
- não faça commit a menos que eu peça.
```

---

## Terça-feira — 03/06/2026

**Foco:** Prioridade 1.3 — IDOR em anexos de produto

**Itens do backlog:** 1.3

**Feito?** ⬜

### Prompt

```
Leia CODE_REVIEW_BACKLOG.md e implemente o item 1.3 (ProdutoAnexoController::show):

- Autorização equivalente a store/destroy (canRead/canUpdate em produtos).
- Garantir que o usuário só acesse anexo de produto permitido (localização/permissões existentes no projeto).

Atualize CODE_REVIEW_BACKLOG.md marcando [x] no que concluir. Teste manual descrito no backlog. Não commite sem eu pedir.
```

---

## Quarta-feira — 04/06/2026

**Foco:** API motorista + hardening da API

**Itens do backlog:** 2.1, 3.2, 3.3

**Feito?** ⬜

### Prompt

```
Leia CODE_REVIEW_BACKLOG.md e implemente:

2.1 API motorista — papel/permissão de motorista, bloquear login para quem não for motorista, throttle no POST /api/motorista/login, revisar endpoints sensíveis. Atualizar motorista-app/README.md se necessário.

3.2 MotoristaApiController — não expor $e->getMessage() ao cliente; log no servidor e resposta JSON genérica.

3.3 sanctum.php — definir expiração para tokens motorista-pwa e validar impacto no PWA.

Marque [x] em CODE_REVIEW_BACKLOG.md. Não commite sem eu pedir.
```

---

## Quinta-feira — 05/06/2026

**Foco:** Hardening de usuário, Telescope, repo e bug de Status

**Itens do backlog:** 2.2, 2.3, 2.4, 3.1

**Feito?** ⬜

### Prompt

```
Leia CODE_REVIEW_BACKLOG.md e implemente:

2.2 — remover is_admin de $fillable em User; revisar User::create/update com mass assignment.
2.3 — Telescope só em local ou gate seguro; TELESCOPE_ENABLED em prod.
2.4 — backup_db_dev.sql fora do Git + .gitignore para dumps SQL (sem apagar arquivo local sem confirmar comigo).
3.1 — corrigir redirect em StatusController::store usando o id do modelo criado.

Marque [x] no backlog. Resuma riscos remanescentes. Não commite sem eu pedir.
```

---

## Sexta-feira — 06/06/2026

**Foco:** Testes mínimos, limpeza e ajustes finais

**Itens do backlog:** 3.4, 3.5, 4.1, 4.2, 4.3, 4.4

**Feito?** ⬜

### Prompt

```
Leia CODE_REVIEW_BACKLOG.md e implemente o que couber nesta sessão:

3.4 — reduzir logs debug em CheckUserAccessSchedule em produção.
3.5 — CheckPermission: abort(403) ou redirect para dashboard em vez de back() inválido.
4.1 — testes Feature mínimos (permissão produtos, API motorista negada, ArquivoController, LogController, ProdutoAnexo show).
4.2 — remover ou arquivar código morto listado no backlog; remover DEBUG em localizacao-capacidade/show.blade.php.
4.3 — documentar APP_DEBUG no .env.example / checklist deploy.
4.4 — verificar venv na pasta exportar e importar banco de tiago no Git; .gitignore se preciso.

Marque [x] no backlog. Rode php artisan test em windsurf/. Não commite sem eu pedir.
```

---

## Prompt extra — revisão de fim de semana (opcional)

**Data sugerida:** 07/06/2026 ou quando encerrar a semana

**Feito?** ⬜

### Prompt

```
Compare o estado atual do código com CODE_REVIEW_BACKLOG.md e tarefas.md:

- Liste itens ainda [ ] por prioridade.
- Diga se algo crítico/alto ficou mal implementado ou pela metade.
- Sugira ordem para a semana seguinte se sobrar trabalho.
Não altere código — só relatório.
```

---

## Prompt extra — continuar de onde parou

Use em qualquer dia se não terminou o planejado:

```
Abra tarefas.md e CODE_REVIEW_BACKLOG.md. Identifique o primeiro dia/item ainda ⬜ ou [ ] pendente e continue a implementação a partir daí. Atualize ambos os arquivos ao concluir cada bloco. Não commite sem eu pedir.
```

---

## Controle rápido da semana

| Data | Dia | Itens | Status |
|------|-----|-------|--------|
| 02/06/2026 | Seg | 1.1, 1.2 | ⬜ |
| 03/06/2026 | Ter | 1.3 | ⬜ |
| 04/06/2026 | Qua | 2.1, 3.2, 3.3 | ⬜ |
| 05/06/2026 | Qui | 2.2, 2.3, 2.4, 3.1 | ⬜ |
| 06/06/2026 | Sex | 3.4, 3.5, 4.1–4.4 | ⬜ |

---

## Notas da equipe

_Espaço para anotações durante a semana (bloqueios, decisões de negócio, ex.: critério “quem é motorista”):_

- 

