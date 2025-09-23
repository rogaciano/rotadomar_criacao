# Instruções para Implementar o Filtro por Status de Dias

Implementamos o filtro por status de dias (Todos, Atrasados, Em Dia) para as movimentações. Aqui estão as instruções para usar e testar a funcionalidade:

## Arquivos Criados/Modificados

1. **MovimentacaoFilterController.php**: Implementa a lógica de filtro por status de dias
2. **Rotas**: Adicionamos uma nova rota `movimentacoes/filtro/status-dias`
3. **View**: Atualizamos o formulário para usar a nova rota

## Como Testar

1. Acesse a página de movimentações
2. Use o filtro "Status de Dias" e selecione uma opção (Atrasados ou Em Dia)
3. Clique em "Filtrar"
4. Verifique se apenas as movimentações correspondentes ao filtro são exibidas

## Se o Filtro Não Funcionar

Se o filtro não funcionar corretamente, você pode:

### Opção 1: Executar o Script PHP

Execute o script PHP que criamos para adicionar o filtro ao MovimentacaoController:

```
php adicionar_filtro.php
```

### Opção 2: Editar Manualmente o MovimentacaoController

1. Abra o arquivo `app/Http/Controllers/MovimentacaoController.php`
2. Localize o trecho de código no método `index` (aproximadamente linha 130):
   ```php
   // Adicionar filtro para o campo concluido
   if ($request->filled('concluido')) {
       $query->where('concluido', $request->concluido);
   }

   // Ordenação
   ```

3. Adicione o seguinte código entre o filtro de concluido e a ordenação:
   ```php
   // Filtro por status de dias (Atrasados, Em Dia)
   if ($request->filled('status_dias')) {
       $query = MovimentacaoFilterController::applyStatusDiasFilter($query, $request->status_dias);
   }
   ```

4. Repita o mesmo processo no método `generateListPdf` (aproximadamente linha 488).

### Opção 3: Usar Diretamente a Rota de Filtro

Você pode acessar diretamente a URL da rota de filtro:

```
http://seu-site.com/movimentacoes/filtro/status-dias?status_dias=atrasados
```

ou

```
http://seu-site.com/movimentacoes/filtro/status-dias?status_dias=em_dia
```

## Explicação da Lógica de Filtro

- **Atrasados**: Movimentações sem data de saída (não concluídas) onde o número de dias desde a entrada é maior que o prazo definido para a localização
- **Em Dia**: Movimentações que são:
  - Já concluídas (com data de saída)
  - Ou não concluídas mas dentro do prazo da localização
  - Ou em localizações sem prazo definido

## Solução de Problemas

Se você encontrar algum problema, verifique:

1. Se o MovimentacaoFilterController está presente e correto
2. Se as rotas estão registradas corretamente
3. Se o formulário na view está apontando para a rota correta
4. Se o MovimentacaoController está importando o MovimentacaoFilterController
