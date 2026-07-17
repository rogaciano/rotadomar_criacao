# Fluxo logístico de IDA (fábrica → facção)

> **Última atualização:** 2026-07-06  
> **Branch de trabalho:** `main` (alterações locais ainda **não commitadas**)  
> **Contexto:** complementa o fluxo de **volta** (facção → fábrica) já em produção desde 02/07/2026.

---

## 1. Visão geral do circuito completo

```
CRIAÇÃO
  status DESENVOLVIMENTO FINALIZADO
        │
        ▼  "Liberar para Produção" (permissão liberar_producao)
IDA — logística (fábrica → facção)
  Agendamento → … → Chegada na facção
        │
        ▼  handoff automático → etapa RECEBIMENTO na facção
PRODUÇÃO na facção
  Recebimento → … → Acabamento
        │
        ▼  handoff (inicia_logistica) — já existia
VOLTA — logística (facção → fábrica)
  Agendamento → … → Chegada na fábrica
```

A **ida** reutiliza as mesmas 7 etapas logísticas e o mesmo controller; a diferença é o gatilho inicial, o campo `tipo` da coleta (`ida`/`volta`) e os rótulos na tela.

---

## 2. O que JÁ FOI FEITO (sessão 02–06/07/2026)

### 2.1 Deploy em produção (02/07/2026)

- Merge `feature/backend-improvements` → `main`; 51 migrations no servidor `criacao`
- Auditoria OK: 7 etapas + handoff Acabamento → Agendamento + enum `entregue`
- Runbook: `docs/RUNBOOK_MIGRACAO_SERVIDOR.md` (deploy registrado como concluído)
- Backlog pós-deploy aplicado na `main`: remoção `laravel/ai`, Telescope só em local, `prism-php/prism` direto

### 2.2 Fluxo de ida — implementação (código local, pendente commit)

| Item | Arquivo / rota |
|------|----------------|
| Migration `tipo` em `coletas_logisticas` (`ida`/`volta`, default `volta`) | `2026_07_03_090000_add_fluxo_ida_logistica.php` |
| Migration `fluxo_logistica` em `produto_localizacao` | mesma migration |
| Permissão `liberar_producao` | `2026_07_03_090100_add_liberar_producao_permission.php` |
| Ação **Liberar para Produção** | `POST produtos/{produto}/liberar-producao` → `LogisticaColetaController@liberarProducao` |
| Botão + modal na ficha do produto | `resources/views/produtos/show.blade.php` |
| Badge **IDA** / **VOLTA** e rótulos dinâmicos nos modais | `resources/views/logistica-coleta/index.blade.php` |
| Coleta herda `tipo` no agendamento | `LogisticaColetaController@agendar` |
| Fechamento da ida → RECEBIMENTO na facção | `concluirIdaNaFaccao()` em `confirmarChegadaFabrica` |

**Gatilho “Liberar para Produção”**

- Visível quando: status = `DESENVOLVIMENTO FINALIZADO`, usuário tem `liberar_producao`, produto ainda não está em etapa logística
- Cria registro em `produto_localizacao` na **origem** escolhida (ex.: CHECK-LIST) com `fluxo_logistica = ida` e etapa **Agendamento**
- No banco de ensaio: **966** produtos com esse status (id 6)

### 2.3 Restrição de destino pelo planejamento (06/07/2026)

**Regra:** no agendamento, o destino só pode ser outra localização já cadastrada na ficha do produto (view), **exceto** a origem do registro que está sendo transportado.

- Métodos: `ProdutoLocalizacao::destinosLogisticaPermitidos()` e `destinoPermitidoParaColeta()`
- UI: modal lista só facções planejadas; se uma só, pré-seleciona; se nenhuma, mostra *“Sem destino planejado”*
- Validação no backend: `LogisticaColetaController@agendar` e `MotoristaApiController@agendar`

**Exemplo validado pelo usuário (06/07):**

| Localização | Qtd | Papel |
|-------------|-----|--------|
| CONFECÇÃO - MAURÍCIO LIMA | 700 | Destino planejado (ordem 110358A) |
| CHECK-LIST | 1.800 | Origem em Agendamento (ida) |

Modal de agendamento mostrou apenas **CONFECÇÃO - MAURÍCIO LIMA** como destino.

### 2.4 Migrations locais já rodadas

```bash
cd windsurf
php artisan migrate   # já aplicou 2026_07_03_090000 e 2026_07_03_090100 no banco local
```

**Produção:** essas duas migrations **ainda não** foram aplicadas no servidor.

---

## 3. O que está PENDENTE

### 3.1 Testes manuais (prioridade)

- [ ] **Circuito ida completo** (5 cliques em Coletas Ativas): Solicitar Retirada → Confirmar Retirada → Confirmar Entrega → Check-in → Confirmar Chegada Final
- [ ] **Após chegada final:** conferir na ficha do produto o estado das localizações (ver seção 4 — refinamento conhecido)
- [ ] **Cancelamento** de coleta ida (volta para Agendamento)
- [ ] Atribuir permissão `liberar_producao` aos grupos/usuários que precisam (além de admin)

### 3.2 Git e deploy

Alterações **não commitadas** (ver `git status` em `windsurf/`):

- Controllers, models, views, routes, 2 migrations novas

Quando validar os testes:

```bash
git add windsurf/...
git commit -m "feat(logistica): fluxo de ida, liberar para producao e destino pelo planejamento"
git push origin main
```

**No servidor** (após push):

```bash
cd /var/www/html/criacao
git pull origin main
cd windsurf
composer install --no-interaction
npm run build
php artisan migrate
php artisan optimize:clear
```

### 3.3 Refinamento do fechamento da ida (backlog técnico)

Ver **seção 4** — comportamento atual pode gerar **duas linhas** para a mesma facção na view do produto. Não bloqueia teste do modal/agendamento; afeta o estado final após “Confirmar Chegada Final”.

### 3.4 Outros backlog (já documentados)

- App motorista: expor destinos permitidos por produto na API de listagem (hoje só valida no `agendar`)
- `liberarProducao`: opcionalmente usar origem/quantidade da linha já planejada em vez de pedir no modal
- Limpar arquivos Laravel soltos em `/var/www/html/criacao/` (fora do git) no servidor

---

## 4. Observação importante — fechamento da ida e duas linhas na view

### O que acontece hoje

Quando a ida termina (“Confirmar Chegada Final”), o método `concluirIdaNaFaccao()`:

1. Pega o registro `produto_localizacao` **da coleta** (ex.: linha CHECK-LIST, 1.800 un., que passou por Agendamento → … → logística)
2. **Altera** `localizacao_id` dessa mesma linha para a facção destino (ex.: MAURÍCIO LIMA)
3. Grava `data_entrega_faccao` e avança essa linha para etapa **RECEBIMENTO**

### O problema

Na ficha do produto costuma existir **outra linha** já planejada para a facção — criada antes, no planejamento:

```
CONFECÇÃO - MAURÍCIO LIMA   700 un.   (sem etapa logística — planejamento)
CHECK-LIST                  1.800 un. (Agendamento — linha da coleta/ida)
```

Após o fechamento, o código **não usa** a linha dos 700; ele **transforma** a linha do CHECK-LIST em “MAURÍCIO LIMA + RECEBIMENTO + 1.800”.

**Resultado possível na view:**

```
CONFECÇÃO - MAURÍCIO LIMA   700 un.   (linha antiga do planejamento — órfã)
CONFECÇÃO - MAURÍCIO LIMA   1.800 un. RECEBIMENTO (linha que era CHECK-LIST)
```

Ou seja: **duas linhas para a mesma facção**, com quantidades diferentes — confuso para produção e para totais.

### O que seria o comportamento ideal (refinamento futuro)

```
Confirmar Chegada Final (ida)
        │
        ├─► Atualizar a linha JÁ planejada da facção destino
        │     (ex.: MAURÍCIO LIMA 700 → RECEBIMENTO, data_entrega_faccao)
        │
        └─► Encerrar/remover a linha temporária de origem (CHECK-LIST)
              ou marcar como concluída / quantidade zerada
```

Regras de negócio a definir antes de codificar:

- Somar quantidades (700 + 1.800) na linha da facção ou manter separado por ordem de produção?
- Vincular explicitamente a coleta à linha destino (`produto_localizacao_destino_id`)?

---

## 5. Como RETOMAR em outro chat

Cole algo como:

```
Leia windsurf/docs/FLUXO_LOGISTICA_IDA.md e windsurf/docs/FLUXO_LOGISTICA_TESTE.md.
Quero continuar o fluxo de ida: testar o circuito completo e decidir o refinamento do fechamento (seção 4).
```

**Comandos úteis:**

```bash
cd E:\projetos\RotaDoMar\windsurf
php artisan serve
php artisan etapas:auditar-logistica
php artisan migrate:status | findstr 2026_07_03
```

**URLs:**

- Logística: `/logistica-coleta`
- Produto teste (status 6): referência `240115` (id 1118 no banco de ensaio)

**Permissões:**

- `logistica` — tela de coleta
- `liberar_producao` — botão na ficha do produto (admins já têm via bypass / isAdmin)

---

## 6. Referências

- Volta + handoff: `docs/FLUXO_LOGISTICA_TESTE.md`, `docs/ETAPAS_CONTEXTO.md`
- Deploy servidor: `docs/RUNBOOK_MIGRACAO_SERVIDOR.md`
- Controller principal: `app/Http/Controllers/LogisticaColetaController.php`
- Model coleta: `app/Models/ColetaLogistica.php` (`TIPO_IDA`, `TIPO_VOLTA`, `isIda()`)
