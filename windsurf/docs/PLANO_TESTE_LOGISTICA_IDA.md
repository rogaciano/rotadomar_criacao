# Plano de testes — Logística IDA (fábrica → facção)

> **Ambiente:** banco local (`rotadomar_produtos`)  
> **Senha de todos os usuários (local):** `@Senha123`  
> **Login:** email ou nome na tela `/login`

---

## 1. Credenciais rápidas

| Papel no teste | Usuário | Login | Localização |
|----------------|---------|-------|-------------|
| Admin / liberar produção | Admin | `admin@rotadomar.com` ou `Admin` | T.i / CPD |
| Origem ida (fábrica) | CAROL | `checklist@1` ou `CAROL` | CHECK-LIST |
| Motorista | LEONARDO | `motorista@2` ou `LEONARDO` | CHECKLIST - MOTORISTAS |
| Destino ida (facção) | MAURICIO | `mauricio@1` ou `MAURICIO` | CONFECÇÃO - MAURÍCIO LIMA |
| Logística comercial (agendar) | DOUGLAS | `logistica@1` ou `DOUGLAS` | COMERCIAL |

**Resetar senhas novamente (somente local):**

```bash
cd windsurf
php scripts/reset_senhas_local.php
```

---

## 2. Produto sugerido

| Campo | Valor |
|-------|--------|
| Referência | `010034A` (id 7295) — **circuito ida já concluído** |
| Status | DESENVOLVIMENTO FINALIZADO |
| Planejamento | CHECK-LIST (origem) + MAURÍCIO LIMA (destino) |

Para repetir o teste do zero, escolha outro produto com status **DESENVOLVIMENTO FINALIZADO** que tenha pelo menos **duas localizações** na ficha (origem fábrica + facção destino) e que **ainda não** esteja em etapa logística.

---

## 3. Circuito IDA — passo a passo

### Legenda

- **Tela:** `/logistica-coleta` salvo quando indicado
- **Etapa esperada:** slug no banco / nome na coluna “Etapa Atual”
- Na **ida**, “Solicitar Retirada” = buscar na **fábrica**; “Confirmar Entrega” = motorista declara entrega na **facção**

---

### Passo 0 — Conferir pré-requisitos (Admin)

- [ ] `php artisan migrate` — migrations `2026_07_03_*` aplicadas
- [ ] `php artisan etapas:auditar-logistica` — 7 etapas OK
- [ ] Produto na ficha com localizações planejadas (ex.: CHECK-LIST + MAURÍCIO LIMA)
- [ ] Veículo ativo cadastrado (ex.: PEM4H92)

**Usuário:** Admin (`admin@rotadomar.com` / `@Senha123`)

---

### Passo 1 — Liberar para Produção

| Item | Detalhe |
|------|---------|
| **Usuário** | Admin (ou quem tiver permissão `liberar_producao`) |
| **Onde** | Ficha do produto → `/produtos/{id}` |
| **Ação** | Botão **Liberar para Produção** |
| **Formulário** | Origem: **CHECK-LIST**; quantidade conforme planejamento (ex.: 1800) |
| **Resultado** | Linha em CHECK-LIST, etapa **Agendamento**, badge fluxo **ida** |
| **Verificar** | Produto aparece em `/logistica-coleta` na lista de agendamento |

---

### Passo 2 — Agendar coleta

| Item | Detalhe |
|------|---------|
| **Usuário** | LEONARDO **ou** DOUGLAS (permissão `logistica`) |
| **Onde** | `/logistica-coleta` → Agendar |
| **Formulário** | Motorista: **LEONARDO**; veículo; destino: **CONFECÇÃO - MAURÍCIO LIMA** (só opções planejadas) |
| **Resultado** | Coleta **IDA**, status `agendado`, etapa ainda **Agendamento** |
| **Verificar** | Badge **Ida** na coleta; destino correto no modal |

---

### Passo 3 — Solicitar retirada (motorista)

| Item | Detalhe |
|------|---------|
| **Usuário** | **LEONARDO** |
| **Onde** | `/logistica-coleta` → Coletas Ativas |
| **Ação** | **Solicitar Retirada** |
| **Significado (ida)** | Motorista avisa que vai buscar o produto na **fábrica** (CHECK-LIST) |
| **Resultado** | Etapa `saida_fabrica_solicitar_retirada` |

---

### Passo 4 — Confirmar retirada (origem)

| Item | Detalhe |
|------|---------|
| **Usuário** | **CAROL** (localização CHECK-LIST) |
| **Onde** | `/logistica-coleta` → Coletas Ativas |
| **Ação** | **Confirmar Retirada** |
| **Significado (ida)** | Fábrica confirma que o motorista retirou o material |
| **Resultado** | Coleta `em_transito`; etapas `retirada_confirmada_faccao` + `em_transito` |

---

### Passo 5 — Confirmar entrega (motorista)

| Item | Detalhe |
|------|---------|
| **Usuário** | **LEONARDO** |
| **Onde** | `/logistica-coleta` |
| **Ação** | **Confirmar Entrega** |
| **Significado (ida)** | Motorista declara que entregou na **facção** |
| **Resultado** | Coleta `entregue`; etapa `entrega_confirmada_fabrica` *(rótulo herdado da volta)* |

---

### Passo 6 — Registrar check-in (destino)

| Item | Detalhe |
|------|---------|
| **Usuário** | **MAURICIO** (localização MAURÍCIO LIMA) |
| **Onde** | `/logistica-coleta` |
| **Ação** | **Registrar Check-in** |
| **Resultado** | Etapa `check_in` |

---

### Passo 7 — Confirmar chegada final (destino)

| Item | Detalhe |
|------|---------|
| **Usuário** | **MAURICIO** |
| **Onde** | `/logistica-coleta` |
| **Ação** | **Confirmar Chegada Final** |
| **Resultado** | Coleta `finalizado`; handoff para produção na facção |
| **Verificar na ficha** | Linha da ida em **RECEBIMENTO** na facção destino |

---

### Passo 8 — Conferência pós-teste (Admin)

- [ ] Abrir ficha do produto e revisar **todas** as linhas de localização
- [ ] Conferir se há **duas linhas** para a mesma facção (backlog conhecido — ver `FLUXO_LOGISTICA_IDA.md` §4)
- [ ] Coleta no histórico com status **finalizado** e tipo **ida**

**Usuário:** Admin

---

## 4. Teste opcional — Cancelamento (ida)

Repetir passos 1–2 com outro produto, depois:

| Item | Detalhe |
|------|---------|
| **Usuário** | LEONARDO (motorista da coleta) |
| **Quando** | Coleta ainda `agendado` |
| **Ação** | **Cancelar** |
| **Resultado esperado** | Produto volta para etapa **Agendamento** |

---

## 5. Estado atual do produto 010034A (06/07/2026)

O circuito ida **já foi concluído** no banco local:

| Item | Valor |
|------|--------|
| Coleta | #133, tipo `ida`, status `finalizado`, motorista LEONARDO |
| Linha pós-ida | PL em MAURÍCIO LIMA, 1800 un., etapa **RECEBIMENTO** |
| Linha antiga planejamento | MAURÍCIO LIMA, 700 un. (possível duplicata — conferir passo 8) |

**Próximo passo sugerido:** executar **Passo 8** como Admin; depois, se quiser repetir, usar outro produto no passo 1.

---

## 6. Fluxo resumido (quem loga quando)

```
Admin          → Liberar para Produção
LEONARDO       → Agendar (ou DOUGLAS) → Solicitar Retirada → Confirmar Entrega
CAROL          → Confirmar Retirada (CHECK-LIST)
MAURICIO       → Check-in → Confirmar Chegada Final
Admin          → Conferir ficha do produto
```

---

## 7. Problemas comuns

| Sintoma | Causa provável | Solução |
|---------|----------------|---------|
| Botão Liberar não aparece | Sem permissão ou status ≠ DESENVOLVIMENTO FINALIZADO | Login Admin; conferir status |
| Destino vazio no agendar | Facção não planejada na ficha | Cadastrar destino na view do produto |
| Coleta não aparece para CAROL/MAURICIO | Listagem filtrada (corrigido no controller) | Atualizar código; recarregar `/logistica-coleta` |
| “Sem permissão nesta localização” | Usuário logado não é da origem/destino | Trocar para CAROL (origem) ou MAURICIO (destino) |
| Duas linhas na mesma facção | Comportamento conhecido do fechamento ida | Documentado em `FLUXO_LOGISTICA_IDA.md` §4 |

---

## 8. Referências

- **`docs/CHECKLIST_TESTE_LOGISTICA_COMPLETO.md`** — checklist completo (ida + volta)
- `docs/FLUXO_LOGISTICA_IDA.md` — implementação e backlog
- `docs/FLUXO_LOGISTICA_TESTE.md` — fluxo de **volta**
- URL logística: `/logistica-coleta`
