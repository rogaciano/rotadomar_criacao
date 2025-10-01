# Correção do Problema de Persistência de Filtros

## Problema Identificado

O novo filtro por status de dias (Atrasados, Em Dia) não estava sendo armazenado nas preferências do usuário, fazendo com que os filtros fossem perdidos ao editar ou visualizar um registro.

## Solução Implementada

1. **Modificamos o MovimentacaoFilterController** para:
   - Salvar os filtros no cadastro do usuário usando `auth()->user()->saveFilters()`
   - Recuperar os filtros salvos usando `auth()->user()->getFilters()`
   - Redirecionar para a rota correta após limpar os filtros

2. **Atualizamos os redirecionamentos no MovimentacaoController** para:
   - Usar a nova rota `movimentacoes.filtro.status-dias` em vez de `movimentacoes.index`
   - Manter a consistência em todos os redirecionamentos (após criar, editar, excluir, etc.)

## Como Testar

1. Aplique um filtro (por exemplo, selecione "Atrasados" no filtro de status de dias)
2. Clique em um registro para visualizá-lo ou editá-lo
3. Volte para a lista de movimentações
4. Verifique se o filtro foi mantido

## Se o Problema Persistir no Servidor

Se após aplicar essas alterações o problema persistir no servidor, pode ser necessário limpar o cache:

1. Faça upload do arquivo `limpar_cache.sh` para o servidor
2. Execute-o com o comando:
   ```
   bash limpar_cache.sh
   ```

Ou execute manualmente os seguintes comandos no servidor:

```
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

Se mesmo assim o problema persistir, pode ser necessário reiniciar o servidor web (Apache/Nginx) ou o PHP-FPM.
