# Etapas de produção — contexto (facção vs logística)

## Campos novos (`etapas_producao`)

| Campo | Valores | Uso |
|-------|---------|-----|
| `contexto` | `localizacao` \| `logistica` | Onde a etapa aparece e pode ser usada |
| `inicia_logistica` | boolean | **Uma única etapa** — encerra produção na facção e abre o fluxo logístico |

## Fluxos

### Localização (facção / planejamento)

- Recebimento → … → Acabamento  
- Controladas em **Planejamento → Localização** e na ficha do produto (localizações).  
- Usuário de facção só manipula etapas deste contexto (avanço via transições; a última pode ir para logística).

### Logística (fluxo detalhado)

1. **Agendamento** *(inicia_logistica)* — entrada no fluxo logístico  
2. Saída da Fábrica / Solicitar Retirada  
3. Retirada Confirmada pela Facção  
4. Em Trânsito  
5. Entrega Confirmada na Fábrica  
6. Check-in  
7. Chegada do Produto na Fábrica  

Alteradas pela **Logística de Coleta**, API motorista (parcial) e admin.  
Ver checklist completo em [FLUXO_LOGISTICA_TESTE.md](./FLUXO_LOGISTICA_TESTE.md).

## Transições permitidas

1. Mesmo `contexto` (localizacao → localizacao, logistica → logistica)  
2. **Handoff:** `localizacao` → etapa logística com `inicia_logistica = true` (ex.: Acabamento → Agendamento)

## Deploy

```bash
cd windsurf
php artisan migrate
```

A migration `2026_05_28_120000_add_contexto_to_etapas_producao_table` classifica etapas existentes pelos slugs logísticos e marca **Aguardando Retirada** como `inicia_logistica`.

## Cadastro

**Cadastros → Etapas de Produção** — ao criar/editar, escolher **Onde é utilizada** e, em etapas logísticas, marcar **Encerra produção e inicia logística** quando for a primeira etapa logística.
