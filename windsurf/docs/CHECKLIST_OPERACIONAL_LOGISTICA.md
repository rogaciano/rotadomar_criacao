# Guia prático — Testar a Logística no sistema

> Este roteiro é para **quem vai usar o sistema no dia a dia**: criação, fábrica, facção, motorista e logística.  
> Marque ✅ conforme for avançando.  
> **Senha de teste (ambiente local):** `@Senha123` para todos os usuários abaixo.

---

## Antes de começar

### O que você vai simular

Imagine um produto que nasce na **Criação**, vai para a **facção** costurar e depois **volta para a fábrica** quando estiver pronto:

```
1. IDA      → levar o material da FÁBRICA até a FACÇÃO
2. PRODUÇÃO → a facção recebe e produz (até Acabamento)
3. VOLTA    → trazer o produto pronto da FACÇÃO de volta para a FÁBRICA
```

Cada deslocamento passa por **7 momentos** na tela **Logística de Coleta** — sempre envolvendo **motorista**, **quem está na origem** e **quem está no destino**.

### Quem participa do teste

| Papel | Nome para login | O que representa |
|-------|-----------------|------------------|
| Administrador | **Admin** | Libera o produto para ir à facção |
| Fábrica (CHECK-LIST) | **CAROL** | Confirma saída na ida; recebe na volta |
| Facção (Maurício Lima) | **MAURICIO** | Recebe na ida; confirma saída na volta |
| Motorista | **LEONARDO** | Agenda viagem, busca e entrega o material |

> Dica: use **outra aba anônima** ou **saia e entre** a cada troca de personagem — assim fica claro quem está fazendo o quê.

### Produto sugerido para o teste

Anote aqui o produto que você escolheu:

| | |
|---|---|
| **Referência do produto** | __________________ |
| **Quantidade** | __________________ |

O produto precisa:

- [ ] Estar com status **Desenvolvimento Finalizado**
- [ ] Ter na ficha a **fábrica (CHECK-LIST)** e a **facção (Maurício Lima)** cadastradas
- [ ] Ainda **não** ter viagem em andamento

---

## Parte 1 — IDA (fábrica → facção)

> **Objetivo:** o material sai da fábrica e chega na facção para começar a produção.

---

### Passo 1 — Liberar o produto para produção

**Entre como:** Admin

1. [ ] Abra o menu e localize o **produto** pela referência
2. [ ] Na ficha do produto, clique em **Liberar para Produção**
3. [ ] Escolha a origem **CHECK-LIST** e confirme a quantidade
4. [ ] Clique em confirmar

**Você deve ver:**

- [ ] Mensagem de sucesso
- [ ] O produto aparece aguardando logística, com etapa **Agendamento**

**O que aprendeu:** a Criação/administrador autoriza o envio do material para a facção. Só depois disso a logística pode agendar a viagem.

---

### Passo 2 — Agendar a viagem de ida

**Entre como:** LEONARDO (motorista)

1. [ ] Abra **Logística de Coleta** no menu
2. [ ] Localize o produto na lista de agendamento
3. [ ] Clique em **Agendar**
4. [ ] Selecione o motorista **LEONARDO**, o **veículo** e a data/hora
5. [ ] No destino, escolha **Confecção - Maurício Lima** (só devem aparecer facções já planejadas no produto)
6. [ ] Confirme

**Você deve ver:**

- [ ] A coleta na lista **Coletas Ativas** com etiqueta **IDA**
- [ ] Origem: CHECK-LIST → Destino: Maurício Lima
- [ ] Status: **Aguardando Responsável**

**O que aprendeu:** agendar não significa que o caminhão já saiu — apenas reserva a viagem e o responsável.

---

### Passo 3 — Motorista avisa que vai buscar na fábrica

**Entre como:** LEONARDO

1. [ ] Em **Coletas Ativas**, encontre a coleta **IDA** do produto
2. [ ] Clique em **Solicitar Retirada**
3. [ ] Confirme (observação opcional)

**Você deve ver:**

- [ ] Mensagem de sucesso
- [ ] Etapa muda para algo como **Saída da Fábrica / Solicitar Retirada**
- [ ] Agora a **fábrica** precisa confirmar que liberou o material

**O que aprendeu:** na **ida**, “retirada” é na **fábrica** — o motorista avisa que está indo buscar lá.

---

### Passo 4 — Fábrica confirma que o material saiu

**Entre como:** CAROL (CHECK-LIST)

1. [ ] Abra **Logística de Coleta**
2. [ ] Na mesma coleta, clique em **Confirmar Retirada**
3. [ ] Confirme

**Você deve ver:**

- [ ] Status **Em Trânsito**
- [ ] Etapa **Em Trânsito**
- [ ] O produto “saiu” da fábrica no fluxo

**O que aprendeu:** a origem (fábrica) atesta que entregou o material ao motorista. Só então a viagem começa de fato.

---

### Passo 5 — Motorista confirma entrega na facção

**Entre como:** LEONARDO

1. [ ] Em **Coletas Ativas**, clique em **Confirmar Entrega**
2. [ ] Confirme

**Você deve ver:**

- [ ] Status **Entregue** (ou equivalente na tela)
- [ ] Etapa de entrega confirmada pelo motorista

**O que aprendeu:** isto é a **declaração do motorista** — “deixei na facção”. O recebimento oficial ainda depende da facção (próximos passos).

---

### Passo 6 — Facção registra o check-in

**Entre como:** MAURICIO (facção)

1. [ ] Abra **Logística de Coleta**
2. [ ] Clique em **Registrar Check-in**
3. [ ] Confirme

**Você deve ver:**

- [ ] Etapa **Check-in**
- [ ] A facção reconhece que o material chegou

**O que aprendeu:** check-in é o primeiro registro **oficial** da facção de que recebeu a carga.

---

### Passo 7 — Facção confirma chegada final (fim da ida)

**Entre como:** MAURICIO

1. [ ] Na mesma coleta, clique em **Confirmar Chegada Final**
2. [ ] Confirme

**Você deve ver:**

- [ ] Coleta **finalizada** no histórico
- [ ] Na ficha do produto: linha na facção **Maurício Lima** em etapa **Recebimento**

**O que aprendeu:** a ida termina aqui. O produto entra no fluxo de **produção na facção**.

---

### Conferência rápida — IDA concluída?

**Entre como:** Admin (opcional)

- [ ] Coleta **IDA** aparece no histórico como finalizada
- [ ] Produto na facção em **Recebimento**
- [ ] Anote qualquer coisa estranha na ficha (ex.: duas linhas para a mesma facção) para reportar à TI

---

## Parte 2 — Produção na facção (até Acabamento)

> **Objetivo:** simular que a facção produziu o pedido e está pronta para devolver à fábrica.  
> Este trecho usa a **ficha do produto / planejamento**, não a tela de logística.

**Entre como:** MAURICIO (ou Admin, se precisar agilizar)

1. [ ] Confirme que o produto está em **Recebimento** na facção Maurício Lima
2. [ ] Avance as etapas de produção uma a uma (Corte, Costura, etc.) até **Acabamento**
3. [ ] Pare em **Acabamento** — ainda **não** envie para logística

**Você deve ver:**

- [ ] Produto em **Acabamento** na facção
- [ ] Opção para encaminhar à logística (handoff)

**O que aprendeu:** a **volta** só começa depois que a produção na facção termina (Acabamento).

---

## Parte 3 — VOLTA (facção → fábrica)

> **Objetivo:** trazer o produto pronto da facção de volta para a fábrica.

---

### Passo 8 — Facção encaminha para logística

**Entre como:** MAURICIO

1. [ ] Na ficha do produto, avance de **Acabamento** para **Agendamento** (logística)
2. [ ] Confirme a ação

**Você deve ver:**

- [ ] Etapa **Agendamento** (contexto logístico)
- [ ] Produto disponível em **Logística de Coleta**

**O que aprendeu:** igual à ida, a volta também começa no **Agendamento** — mas agora a origem é a **facção**.

---

### Passo 9 — Agendar a viagem de volta

**Entre como:** LEONARDO

1. [ ] **Logística de Coleta** → **Agendar**
2. [ ] Motorista, veículo, datas
3. [ ] Destino: **CHECK-LIST** (fábrica)
4. [ ] Confirme

**Você deve ver:**

- [ ] Coleta com etiqueta **VOLTA**
- [ ] Origem: Maurício Lima → Destino: CHECK-LIST

---

### Passo 10 — Motorista avisa que vai buscar na facção

**Entre como:** LEONARDO

1. [ ] **Solicitar Retirada** → confirmar

**Você deve ver:**

- [ ] Etapa de solicitação de retirada
- [ ] Aguardando confirmação da **facção** (origem)

**O que aprendeu:** na **volta**, “retirada” é na **facção** — o motorista busca o produto pronto lá.

---

### Passo 11 — Facção confirma que o produto saiu

**Entre como:** MAURICIO

1. [ ] **Confirmar Retirada** → confirmar

**Você deve ver:**

- [ ] Status **Em Trânsito**

---

### Passo 12 — Motorista confirma entrega na fábrica

**Entre como:** LEONARDO

1. [ ] **Confirmar Entrega** → confirmar

**Você deve ver:**

- [ ] Entrega registrada pelo motorista na fábrica

---

### Passo 13 — Fábrica registra check-in

**Entre como:** CAROL

1. [ ] **Registrar Check-in** → confirmar

**Você deve ver:**

- [ ] Check-in registrado na CHECK-LIST

---

### Passo 14 — Fábrica confirma chegada final (fim da volta)

**Entre como:** CAROL

1. [ ] **Confirmar Chegada Final** → confirmar

**Você deve ver:**

- [ ] Coleta **VOLTA** finalizada no histórico
- [ ] Circuito completo encerrado

**O que aprendeu:** o produto voltou à fábrica. Ida + produção + volta concluídas.

---

## Resumo — quem faz o quê

### IDA (fábrica → facção)

| Ordem | Quem entra | O que clica |
|-------|------------|-------------|
| 1 | Admin | Liberar para Produção |
| 2 | LEONARDO | Agendar |
| 3 | LEONARDO | Solicitar Retirada |
| 4 | CAROL | Confirmar Retirada |
| 5 | LEONARDO | Confirmar Entrega |
| 6 | MAURICIO | Registrar Check-in |
| 7 | MAURICIO | Confirmar Chegada Final |

### VOLTA (facção → fábrica)

| Ordem | Quem entra | O que clica |
|-------|------------|-------------|
| 8 | MAURICIO | Acabamento → Agendamento (ficha) |
| 9 | LEONARDO | Agendar |
| 10 | LEONARDO | Solicitar Retirada |
| 11 | MAURICIO | Confirmar Retirada |
| 12 | LEONARDO | Confirmar Entrega |
| 13 | CAROL | Registrar Check-in |
| 14 | CAROL | Confirmar Chegada Final |

---

## Dúvidas frequentes

**Por que “Solicitar Retirada” se na ida o material está na fábrica?**  
O nome do botão é o mesmo nos dois sentidos. Na **ida**, retirada = buscar na **fábrica**. Na **volta**, retirada = buscar na **facção**.

**“Confirmar Entrega” do motorista é o recebimento final?**  
Não. É só o motorista dizendo que entregou. Quem **recebe de verdade** faz o **Check-in** e depois a **Chegada Final**.

**Não aparece botão para mim.**  
Cada botão só aparece para o **papel certo** na **etapa certa**. Troque de usuário conforme a tabela acima.

**O destino no agendamento está vazio ou errado.**  
O destino precisa estar **planejado na ficha do produto** antes. Peça à Criação/administrador para cadastrar a facção ou fábrica correta.

**Posso cancelar uma viagem?**  
Sim. O **motorista** pode **Cancelar** enquanto a coleta ainda está aguardando (antes de solicitar retirada ou logo no início). O produto volta para **Agendamento**.

---

## Registro do seu teste

| Pergunta | Sim | Não |
|----------|-----|-----|
| Consegui fazer a ida completa? | ☐ | ☐ |
| Consegui avançar a produção até Acabamento? | ☐ | ☐ |
| Consegui fazer a volta completa? | ☐ | ☐ |
| Entendi a diferença entre motorista, origem e destino? | ☐ | ☐ |

**Observações ou dificuldades:**

```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

## Para a equipe de TI

Checklist técnico (slugs, status, auditoria de banco):  
[`CHECKLIST_TESTE_LOGISTICA_COMPLETO.md`](CHECKLIST_TESTE_LOGISTICA_COMPLETO.md)
