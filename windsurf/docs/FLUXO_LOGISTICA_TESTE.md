# Fluxo logístico — verificação e teste manual

## Pré-requisitos

1. MySQL rodando (`127.0.0.1:3306`)
2. Migrations aplicadas:

```bash
cd windsurf
php artisan migrate
```

Migrations relevantes:
- `2026_05_28_120000_add_contexto_to_etapas_producao_table`
- `2026_06_01_150000_reorganize_logistica_etapas_flow`
- `2026_06_11_140000_ensure_handoff_transition_acabamento_logistica`

## Fluxo esperado (código atual)

```
PRODUÇÃO (contexto: localizacao)
  Recebimento → … → Acabamento
                        │
                        ▼ handoff (inicia_logistica)
LOGÍSTICA (contexto: logistica)
  1. Agendamento                    ← inicia_logistica = true
  2. Saída da Fábrica / Solicitar Retirada   (motorista)
  3. Retirada Confirmada pela Facção           (facção origem)
  4. Em Trânsito                               (automático após conf. facção)
  5. Entrega Confirmada na Fábrica             (motorista)
  6. Check-in                                  (destino)
  7. Chegada do Produto na Fábrica              (destino — fim)
```

## Quem faz cada passo

| Passo | Etapa (slug) | Quem | Onde |
|-------|----------------|------|------|
| Handoff | `agendamento` | Facção (botão em Acabamento) | Planejamento / Produto |
| Agendar coleta | permanece em `agendamento` | Motorista | Logística / App |
| Solicitar retirada | `saida_fabrica_solicitar_retirada` | Motorista | Logística / App |
| Confirmar retirada | `retirada_confirmada_faccao` + `em_transito` | Facção origem | Logística |
| Confirmar entrega | `entrega_confirmada_fabrica` | Motorista | Logística / App |
| Check-in | `check_in` | Destino | Logística |
| Chegada final | `chegada_produto_fabrica` | Destino | Logística |

## Checklist de teste

### A. Cadastro de etapas

- [ ] **Cadastros → Etapas de Produção** — filtrar contexto **Logística**
- [ ] Existe **Agendamento** com **→ LOG** (inicia_logistica)
- [ ] **Visualizar Fluxo** com `?contexto=logistica` mostra as 7 etapas encadeadas
- [ ] Em **Acabamento** (produção), transição para **Agendamento** existe

### B. Handoff produção → logística

- [ ] Produto em **Acabamento** na facção
- [ ] Botão avança para **Agendamento**
- [ ] Etapa atual vira logística; facção **não** consegue mais alterar manualmente

### C. Tela Logística (`/logistica-coleta`)

- [ ] Produto em **Agendamento** aparece na lista “disponíveis”
- [ ] Motorista **agenda** coleta → coleta criada, produto continua em Agendamento
- [ ] Motorista **solicita retirada** → etapa `saida_fabrica_solicitar_retirada`
- [ ] Facção **confirma retirada** → `em_transito` + coleta em trânsito
- [ ] Motorista **confirma entrega** → `entrega_confirmada_fabrica`
- [ ] Destino **check-in** → `check_in`
- [ ] Destino **confirma chegada** → `chegada_produto_fabrica` + coleta finalizada

### D. Cancelamento

- [ ] Cancelar coleta (status agendado) → produto volta para **Agendamento**

## Comando de auditoria rápida

```bash
cd windsurf
php artisan etapas:auditar-logistica
```

(Se o comando não existir, use a listagem em Cadastros + fluxo visual.)

## Problemas comuns

| Sintoma | Causa provável | Correção |
|---------|----------------|----------|
| Logística vazia | Migration `2026_06_01` não rodou | `php artisan migrate` |
| Sem botão Acabamento → logística | Transição handoff ausente | Migration `2026_06_11` ou criar transição manual |
| “Produto não disponível para agendamento” | Produto não está em **Agendamento** | Avançar de Acabamento primeiro |
| Etapas antigas (Aguardando Retirada) | Banco não migrado | Rodar `reorganize_logistica_etapas_flow` |
