# Checklist — Teste completo da Logística

> Marque `[x]` conforme for concluindo cada item.  
> **Ambiente:** banco local · **Senha (todos):** `@Senha123` · **Login:** email ou nome em `/login`  
> **Versão operacional (usuários finais):** [`CHECKLIST_OPERACIONAL_LOGISTICA.md`](CHECKLIST_OPERACIONAL_LOGISTICA.md)

---

## Dados do teste (preencher)


| Campo                | Valor                     |
| -------------------- | ------------------------- |
| Data                 | ***/***/2026              |
| Testador             |                           |
| Produto (referência) |                           |
| Produto (id)         |                           |
| Origem ida (fábrica) | CHECK-LIST                |
| Destino ida (facção) | CONFECÇÃO - MAURÍCIO LIMA |
| Motorista            | LEONARDO                  |
| Veículo              |                           |
| Coleta IDA #         |                           |
| Coleta VOLTA #       |                           |


---

## Visão do circuito completo

```
[IDA]     Fábrica ──logística──► Facção
[PROD]    Facção: Recebimento → … → Acabamento
[VOLTA]   Facção ──logística──► Fábrica
```

---

## 0. Preparação do ambiente

- [ ] MySQL local rodando (`127.0.0.1:3306`)
- [ ] `cd windsurf && php artisan serve` (ou ambiente já no ar)
- [ ] `php artisan migrate` — sem migrations pendentes
- [ ] `php artisan etapas:auditar-logistica` — saída OK (7 etapas + handoff)
- [ ] Senhas locais em `@Senha123` (`php scripts/reset_senhas_local.php` se necessário)
- [ ] Veículo ativo cadastrado em **Veículos** (admin)
- [ ] Produto escolhido com:
  - [ ] Status **DESENVOLVIMENTO FINALIZADO** (para iniciar ida)
  - [ ] Localização **CHECK-LIST** planejada na ficha
  - [ ] Localização **MAURÍCIO LIMA** planejada na ficha
  - [ ] **Não** estar já em etapa logística

**Login:** `admin@rotadomar.com` / `@Senha123`

---

## 1. Auditoria do sistema (uma vez)

- [ ] **Cadastros → Etapas de Produção** → filtro **Logística**
- [ ] Etapa **Agendamento** existe com indicador **→ LOG** (`inicia_logistica`)
- [ ] Fluxo visual (`?contexto=logistica`) mostra **7 etapas** encadeadas
- [ ] Em **Acabamento** (contexto produção) existe transição para **Agendamento**
- [ ] Tela `/logistica-coleta` abre sem erro

---

## 2. IDA — Fábrica → Facção (7 etapas logísticas)

### 2.1 Liberar para produção

**Login:** `admin@rotadomar.com` ou `Admin`

- [ ] Abrir ficha do produto `/produtos/{id}`
- [ ] Botão **Liberar para Produção** visível
- [ ] Modal: origem **CHECK-LIST**, quantidade preenchida
- [ ] Submit com sucesso (mensagem verde)
- [ ] Na ficha: linha em CHECK-LIST, etapa **Agendamento**
- [ ] Em `/logistica-coleta`: produto na lista de agendamento


| Conferir | Esperado      |
| -------- | ------------- |
| Etapa    | `agendamento` |
| Fluxo    | ida           |


---

### 2.2 Agendar coleta

**Login:** `motorista@2` ou `LEONARDO` *(ou `logistica@1` / DOUGLAS)*

- [ ] `/logistica-coleta` → **Agendar**
- [ ] Motorista: **LEONARDO**
- [ ] Veículo selecionado
- [ ] Destino: só **MAURÍCIO LIMA** (ou facção planejada) — sem destinos extras
- [ ] Datas início/retorno preenchidas
- [ ] Coleta criada com sucesso


| Conferir      | Esperado       |
| ------------- | -------------- |
| Badge coleta  | **Ida**        |
| Status coleta | `agendado`     |
| Etapa produto | `agendamento`  |
| Coleta #      | anotar: ______ |


---

### 2.3 Solicitar retirada

**Login:** `motorista@2` ou `LEONARDO`

- [ ] Coleta aparece em **Coletas Ativas**
- [ ] Botão **Solicitar Retirada** visível
- [ ] Submit com sucesso


| Conferir          | Esperado                            |
| ----------------- | ----------------------------------- |
| Significado (ida) | Motorista vai buscar na **fábrica** |
| Etapa             | `saida_fabrica_solicitar_retirada`  |
| Status coleta     | `agendado`                          |


---

### 2.4 Confirmar retirada (origem)

**Login:** `checklist@1` ou `CAROL`

- [ ] Coleta aparece em **Coletas Ativas**
- [ ] Botão **Confirmar Retirada** visível
- [ ] Submit com sucesso


| Conferir      | Esperado                                     |
| ------------- | -------------------------------------------- |
| Quem confirma | Usuário da **origem** (CHECK-LIST)           |
| Etapas        | `retirada_confirmada_faccao` → `em_transito` |
| Status coleta | `em_transito`                                |


---

### 2.5 Confirmar entrega (motorista)

**Login:** `motorista@2` ou `LEONARDO`

- [ ] Botão **Confirmar Entrega** visível
- [ ] Submit com sucesso


| Conferir          | Esperado                                |
| ----------------- | --------------------------------------- |
| Significado (ida) | Motorista declara entrega na **facção** |
| Etapa             | `entrega_confirmada_fabrica`            |
| Status coleta     | `entregue`                              |


---

### 2.6 Registrar check-in (destino)

**Login:** `mauricio@1` ou `MAURICIO`

- [ ] Coleta aparece em **Coletas Ativas**
- [ ] Botão **Registrar Check-in** visível
- [ ] Submit com sucesso


| Conferir      | Esperado                               |
| ------------- | -------------------------------------- |
| Quem registra | Usuário do **destino** (MAURÍCIO LIMA) |
| Etapa         | `check_in`                             |
| Status coleta | `entregue`                             |


---

### 2.7 Confirmar chegada final (destino)

**Login:** `mauricio@1` ou `MAURICIO`

- [ ] Botão **Confirmar Chegada Final** visível
- [ ] Submit com sucesso


| Conferir      | Esperado                             |
| ------------- | ------------------------------------ |
| Status coleta | `finalizado`                         |
| Handoff       | Produto em **RECEBIMENTO** na facção |
| Histórico     | Coleta ida no histórico              |


---

### 2.8 Conferência pós-IDA

**Login:** `admin@rotadomar.com` ou `Admin`

- [ ] Ficha do produto: linha na facção em **RECEBIMENTO**
- [ ] Quantidade confere com a ida
- [ ] Anotar se há **duas linhas** para a mesma facção (backlog conhecido)
- [ ] Coleta ida **finalizada** com badge **Ida**

---

## 3. Ponte — Produção na facção (até Acabamento)

> Objetivo: preparar o handoff para a **volta**. Pode avançar etapas pela ficha do produto ou planejamento.

**Login:** `mauricio@1` ou `MAURICIO` *(ou Admin para acelerar)*

- [ ] Produto em **RECEBIMENTO** na facção MAURÍCIO LIMA
- [ ] Avançar etapas de produção até **Acabamento**
- [ ] Na etapa Acabamento, botão de handoff para logística visível
- [ ] **Não** avançar ainda — só confirmar que está em Acabamento


| Conferir       | Esperado                 |
| -------------- | ------------------------ |
| Contexto etapa | `localizacao` (produção) |
| Etapa atual    | Acabamento               |


---

## 4. VOLTA — Facção → Fábrica (7 etapas logísticas)

### 4.1 Handoff produção → logística

**Login:** `mauricio@1` ou `MAURICIO`

- [ ] Na ficha / planejamento: avançar de **Acabamento** para **Agendamento**
- [ ] Etapa passa para contexto **logística**
- [ ] Facção **não** altera etapa manualmente depois do handoff


| Conferir      | Esperado      |
| ------------- | ------------- |
| Etapa         | `agendamento` |
| Origem coleta | MAURÍCIO LIMA |


---

### 4.2 Agendar coleta (volta)

**Login:** `motorista@2` ou `LEONARDO`

- [ ] `/logistica-coleta` → **Agendar**
- [ ] Motorista: **LEONARDO**
- [ ] Destino: **CHECK-LIST** (fábrica planejada na ficha)
- [ ] Coleta criada com sucesso


| Conferir      | Esperado       |
| ------------- | -------------- |
| Badge coleta  | **Volta**      |
| Status coleta | `agendado`     |
| Coleta #      | anotar: ______ |


---

### 4.3 Solicitar retirada

**Login:** `motorista@2` ou `LEONARDO`

- [ ] **Solicitar Retirada** → sucesso


| Conferir            | Esperado                           |
| ------------------- | ---------------------------------- |
| Significado (volta) | Motorista vai buscar na **facção** |
| Etapa               | `saida_fabrica_solicitar_retirada` |


---

### 4.4 Confirmar retirada (origem)

**Login:** `mauricio@1` ou `MAURICIO`

- [ ] **Confirmar Retirada** → sucesso


| Conferir      | Esperado                     |
| ------------- | ---------------------------- |
| Quem confirma | Usuário da **facção origem** |
| Status coleta | `em_transito`                |
| Etapa         | `em_transito`                |


---

### 4.5 Confirmar entrega (motorista)

**Login:** `motorista@2` ou `LEONARDO`

- [ ] **Confirmar Entrega** → sucesso


| Conferir            | Esperado                                 |
| ------------------- | ---------------------------------------- |
| Significado (volta) | Motorista declara entrega na **fábrica** |
| Etapa               | `entrega_confirmada_fabrica`             |
| Status coleta       | `entregue`                               |


---

### 4.6 Registrar check-in (destino)

**Login:** `checklist@1` ou `CAROL`

- [ ] Coleta visível em **Coletas Ativas**
- [ ] **Registrar Check-in** → sucesso


| Conferir      | Esperado                            |
| ------------- | ----------------------------------- |
| Quem registra | Usuário do **destino** (CHECK-LIST) |
| Etapa         | `check_in`                          |


---

### 4.7 Confirmar chegada final (destino)

**Login:** `checklist@1` ou `CAROL`

- [ ] **Confirmar Chegada Final** → sucesso


| Conferir      | Esperado                  |
| ------------- | ------------------------- |
| Status coleta | `finalizado`              |
| Etapa         | `chegada_produto_fabrica` |
| Circuito      | Ida + volta encerrados    |


---

### 4.8 Conferência pós-VOLTA

**Login:** `admin@rotadomar.com` ou `Admin`

- [ ] Ficha do produto: estado final coerente
- [ ] Coleta volta no **histórico** (badge **Volta**, status finalizado)
- [ ] Ambas coletas (ida e volta) registradas

---

## 5. Testes extras (opcional)

### 5.1 Cancelamento — IDA

Usar **outro** produto; repetir liberar + agendar, depois:

**Login:** `motorista@2` ou `LEONARDO`

- [ ] Com coleta em `agendado`, clicar **Cancelar**
- [ ] Produto volta para etapa **Agendamento**
- [ ] Coleta aparece como **cancelada** no histórico

### 5.2 Cancelamento — VOLTA

- [ ] Mesmo fluxo na volta, antes de solicitar retirada
- [ ] Cancelar → produto volta para **Agendamento**

### 5.3 Destino inválido no agendamento

**Login:** Admin ou motorista

- [ ] Tentar agendar com destino **fora** do planejamento da ficha
- [ ] Sistema bloqueia com mensagem de erro

---

## 6. Tabela rápida — quem loga em cada passo


| Passo                     | IDA               | VOLTA                             |
| ------------------------- | ----------------- | --------------------------------- |
| Liberar / handoff inicial | Admin             | MAURICIO (Acabamento→Agendamento) |
| Agendar                   | LEONARDO          | LEONARDO                          |
| Solicitar retirada        | LEONARDO          | LEONARDO                          |
| Confirmar retirada        | CAROL (fábrica)   | MAURICIO (facção)                 |
| Confirmar entrega         | LEONARDO          | LEONARDO                          |
| Check-in                  | MAURICIO (facção) | CAROL (fábrica)                   |
| Chegada final             | MAURICIO (facção) | CAROL (fábrica)                   |
| Conferir ficha            | Admin             | Admin                             |


**Senha sempre:** `@Senha123`

---

## 7. Registro final


| Item                               | OK? | Observação |
| ---------------------------------- | --- | ---------- |
| Auditoria etapas                   | ☐   |            |
| IDA completa (7 passos)            | ☐   |            |
| Produção até Acabamento            | ☐   |            |
| VOLTA completa (7 passos)          | ☐   |            |
| Cancelamento testado               | ☐   |            |
| Destino restrito ao planejamento   | ☐   |            |
| Duplicata de linhas na ficha (ida) | ☐   |            |


**Resultado geral:** ☐ Aprovado · ☐ Reprovado · ☐ Aprovado com ressalvas

**Ressalvas / bugs encontrados:**

```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

## 8. Problemas comuns


| Sintoma                                   | Solução                                                               |
| ----------------------------------------- | --------------------------------------------------------------------- |
| Botão não aparece                         | Conferir usuário, etapa atual e status da coleta                      |
| Coleta não lista para CAROL/MAURICIO      | Usuário deve ser da origem ou destino; recarregar `/logistica-coleta` |
| Destino vazio no modal                    | Cadastrar facção/fábrica na ficha do produto                          |
| “Produto não disponível para agendamento” | Etapa deve ser `agendamento`                                          |
| Etapas antigas no banco                   | `php artisan migrate`                                                 |
| Duas linhas mesma facção após ida         | Backlog — ver `FLUXO_LOGISTICA_IDA.md` §4                             |


---

## 9. Referências

- `docs/PLANO_TESTE_LOGISTICA_IDA.md` — detalhes da ida
- `docs/FLUXO_LOGISTICA_IDA.md` — implementação e backlog
- `docs/FLUXO_LOGISTICA_TESTE.md` — fluxo de volta
- URL: `/logistica-coleta`

