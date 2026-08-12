# Guia de treinamento - Logística

> Uso diário do fluxo de ida (origem -> facção), produção e volta (facção -> destino).
> Atualizado em 12/08/2026.

---

## 1. Regra principal

Há duas informações diferentes e elas não devem ser confundidas:

| Informação | Onde é definida | Significado |
|---|---|---|
| **Origem logística** | Cadastro de Localizações e modal de Liberação | Onde o material está fisicamente antes de sair. Ex.: fábrica ou centro de distribuição. |
| **Destino de produção** | Planejamento na ficha do produto | Facção/localização que receberá e produzirá o produto. |

O planejamento **não é origem**. Cada lançamento planejado representa um destino e sua quantidade. Se o mesmo produto for enviado para duas facções, devem existir dois lançamentos de planejamento, um para cada destino.

---

## 2. Pré-requisitos do administrador

Faça esta conferência antes do primeiro treinamento ou antes de liberar um produto.

### 2.1 Localização de origem

Em **Cadastros > Localizações**, abra a fábrica, centro de distribuição ou outro ponto de saída e confirme:

- [ ] A localização está **Ativa**.
- [ ] Está marcada como **Pode ser origem logística**.
- [ ] Se for o ponto mais usado, está marcada como **Origem logística padrão**.

`Pode ser origem logística` faz a localização aparecer no select de origem da liberação. Uma facção comum não deve receber essa marcação.

`Origem logística padrão` apenas deixa uma origem pré-selecionada para agilizar o preenchimento. Ela não impede a escolha de outra origem habilitada. Mantenha somente uma localização como padrão.

### 2.2 Produto e planejamento

- [ ] O produto está com status **DESENVOLVIMENTO FINALIZADO**.
- [ ] Existe pelo menos um lançamento planejado na ficha do produto para a facção de destino.
- [ ] A quantidade de cada destino foi revisada.
- [ ] Não existe coleta ativa para a mesma linha logística.

Não cadastre a fábrica como destino somente para fazê-la aparecer na liberação. A origem vem do cadastro de Localizações; o destino vem do planejamento.

### 2.3 Usuários, acessos e veículo

| Ação | Necessário |
|---|---|
| Consultar a ficha do produto | Permissão `produtos: leitura`. |
| Liberar para produção | `produtos: leitura` e permissão específica `liberar_producao`. |
| Abrir e operar a tela de coletas | Permissão `logistica`. |
| Agendar | Acesso à localização de destino, motorista cadastrado e veículo ativo. |
| Solicitar retirada, confirmar entrega ou cancelar | Usuário definido como motorista daquela coleta (ou administrador). |
| Confirmar retirada na origem | Usuário vinculado à localização de origem (ou administrador). |
| Check-in e chegada final | Usuário vinculado à localização de destino (ou administrador). |

Antes de testar, confirme que cada pessoa possui uma localização vinculada no cadastro de usuário. Sem isso, ela pode ver a tela, mas não conseguirá confirmar a ação da origem ou do destino.

---

## 3. Fluxo de ida: origem -> facção

### Passo 1 - Liberar para produção

**Quem faz:** usuário com `liberar_producao`.

1. Abra a ficha do produto.
2. Clique em **Liberar para Produção**.
3. Escolha a **origem física**. A origem padrão, se configurada, já vem selecionada.
4. Escolha o **destino planejado** que receberá esta liberação.
5. Confira a quantidade planejada, exibida somente para leitura, e inclua observação se necessário.
6. Confirme.

Resultado esperado:

- [ ] O produto entra na etapa **Agendamento** da logística de ida.
- [ ] É criada uma linha logística ligada à origem e ao destino planejado escolhidos.
- [ ] A quantidade da coleta é a quantidade daquele lançamento planejado.

Se a origem não aparecer, ela está inativa, não foi marcada como `Pode ser origem logística` ou também foi usada como destino planejado desse produto.

Se houver duas facções planejadas, faça uma liberação para cada destino. Cada uma gera sua própria linha em Agendamento, coleta e histórico.

### Passo 2 - Agendar a coleta

**Quem faz:** usuário com acesso a `logistica`.

1. Abra **Logística de Coleta**.
2. Localize a linha em **Agendamento** e clique em **Agendar**.
3. Confira a **Origem** exibida em modo somente leitura.
4. Escolha motorista, veículo e período.
5. Escolha o destino entre as localizações planejadas na ficha do produto.
6. Confirme.

Regras do modal:

- A origem não pode ser trocada no agendamento.
- O servidor recusa origem diferente da linha logística selecionada.
- Só aparecem destinos planejados para o produto e permitidos ao usuário que agenda.
- Para enviar para outro destino, cadastre outro lançamento de planejamento antes de agendar.

Resultado esperado:

- [ ] A coleta aparece em **Coletas Ativas** com tipo **IDA**.
- [ ] A origem e o destino exibidos correspondem ao que foi configurado.
- [ ] Não há conflito de motorista ou veículo no período.

### Passo 3 - Retirada, trânsito e entrega

| Ordem | Quem faz | Ação | Resultado esperado |
|---|---|---|---|
| 1 | Motorista | **Solicitar Retirada** | A origem é avisada de que o motorista irá buscar o material. |
| 2 | Responsável da origem | **Confirmar Retirada** | A coleta entra em trânsito. |
| 3 | Motorista | **Confirmar Entrega** | O motorista registra a entrega no destino. |
| 4 | Responsável do destino | **Registrar Check-in** | A facção confirma que recebeu fisicamente a carga. |
| 5 | Responsável do destino | **Confirmar Chegada Final** | A ida é concluída e o produto segue para produção no destino. |

Após a chegada final, confira na ficha do produto se a localização de destino está na etapa de produção esperada, iniciando em **Recebimento**.

---

## 4. Produção e volta: facção -> destino

1. Na ficha do produto, o responsável pela facção avança as etapas de produção até **Acabamento**.
2. Encaminhe a linha de **Acabamento** para **Agendamento** da logística.
3. Em **Logística de Coleta**, agende a viagem de volta.
4. Selecione o destino operacional definido para o retorno.
5. Repita a sequência: solicitar retirada, confirmar retirada na origem, confirmar entrega pelo motorista, check-in e chegada final no destino.

Na volta, a origem é a facção que concluiu a produção. Os mesmos controles de motorista, veículo, origem e destino continuam válidos.

---

## 5. Problemas frequentes

| Situação | Verificar |
|---|---|
| Botão **Liberar para Produção** não aparece | Status do produto, `produtos: leitura`, `liberar_producao` e existência de destino planejado. |
| Origem não aparece no select | Localização ativa, marcada como **Pode ser origem logística**, não planejada como destino para aquele produto. |
| Origem padrão não vem selecionada | Confirme que ela também está habilitada como origem e que não existe outra marcada como padrão. |
| Destino não aparece no agendamento | Cadastre a localização na ficha como planejamento e confira o acesso do usuário que agenda a esse destino. |
| Usuário vê a coleta, mas não confirma | Confira se o usuário está vinculado à localização correspondente à origem ou ao destino. |
| Origem diferente foi enviada pelo navegador | O servidor recusa a solicitação; abra novamente o agendamento pela linha correta. |
| Coleta já existe | Há uma coleta ativa para a mesma linha logística; conclua ou cancele a coleta existente antes de criar outra. |

---

## 6. Checklist de treinamento

| Verificação | Sim | Não |
|---|---|---|
| A origem escolhida apareceu porque está habilitada como origem logística | [ ] | [ ] |
| A origem padrão veio pré-selecionada corretamente | [ ] | [ ] |
| O destino exibido no agendamento era um planejamento do produto | [ ] | [ ] |
| A origem permaneceu somente leitura no agendamento | [ ] | [ ] |
| A ida foi concluída até a chegada final no destino | [ ] | [ ] |
| A produção foi avançada até Acabamento | [ ] | [ ] |
| A volta foi concluída até a chegada final | [ ] | [ ] |

**Observações do treinamento:**

```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```
