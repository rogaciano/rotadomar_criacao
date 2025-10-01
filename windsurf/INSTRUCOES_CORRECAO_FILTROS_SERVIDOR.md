# Correção do Problema de Filtros no Servidor

## Problema Identificado
Os filtros estão sendo salvos corretamente no ambiente de desenvolvimento, mas não no servidor de produção.

## Possíveis Causas e Soluções

### 1. Verificar se a Migração foi Executada
```bash
php artisan migrate:status
```
Verifique se a migração `2025_09_03_170000_create_user_filters_table.php` está marcada como executada.
Se não estiver, execute:
```bash
php artisan migrate
```

### 2. Verificar a Versão do MySQL
```bash
mysql --version
```
Se a versão for anterior à 5.7, pode haver problemas com o tipo de dados JSON.
Solução: Modifique a migração para usar TEXT em vez de JSON e faça a serialização/deserialização manualmente.

### 3. Verificar os Arquivos de Modelo
Certifique-se de que os seguintes arquivos no servidor estão atualizados:
- `app/Models/User.php`
- `app/Models/UserFilter.php`

### 4. Verificar Permissões do Banco de Dados
O usuário do banco de dados deve ter permissões para inserir/atualizar registros na tabela `user_filters`.

### 5. Verificar Logs de Erro
```bash
tail -n 100 storage/logs/laravel.log
```

### 6. Testar com Comando Artisan
Faça upload do arquivo `app/Console/Commands/TestUserFilters.php` para o servidor e execute:
```bash
php artisan test:filters 1  # Substitua 1 pelo ID de um usuário existente
```

### 7. Verificar se o JSON está sendo Serializado Corretamente
Se o problema persistir, pode ser necessário modificar o modelo `UserFilter.php` para forçar a serialização/deserialização do JSON:

```php
// Em app/Models/UserFilter.php

public function setFiltersAttribute($value)
{
    $this->attributes['filters'] = is_array($value) ? json_encode($value) : $value;
}

public function getFiltersAttribute($value)
{
    return json_decode($value, true) ?? [];
}
```

### 8. Limpar o Cache
Execute o script `limpar_cache.sh` no servidor:
```bash
bash limpar_cache.sh
```

### 9. Verificar a Tabela no Banco de Dados
```sql
DESCRIBE user_filters;
SELECT * FROM user_filters LIMIT 10;
```

### 10. Testar Manualmente
Tente salvar um filtro manualmente no console do Laravel:
```bash
php artisan tinker
```
```php
$user = App\Models\User::find(1); // Substitua 1 pelo ID de um usuário existente
$user->saveFilters('test', ['key' => 'value']);
$user->getFilters('test'); // Deve retornar ['key' => 'value']
```
