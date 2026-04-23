# Análise de Backend — Rota do Mar

> **Base:** análise automática em `/home/user/rotadoamar/windsurf/` (versão servidor, pode diferir da branch `feature/ui-ux-improvements` local)
> **Objetivo:** Lista priorizada de melhorias de segurança, qualidade e performance

---

## Prioridade CRÍTICA (corrigir antes de ir a produção)

### 1. Senha padrão hardcoded no seeder
**Arquivo:** `database/seeders/AdminUserSeeder.php:17-21`

```php
// PROBLEMA
User::create([
    'email' => 'admin@rotadomar.com',
    'password' => Hash::make('admin123'),  // ← hardcoded fraco
    'is_admin' => true,
]);
```

**Fix:**
```php
$password = env('ADMIN_SEED_PASSWORD') ?: Str::random(20);
User::create([
    'email' => env('ADMIN_SEED_EMAIL', 'admin@rotadomar.com'),
    'password' => Hash::make($password),
    'is_admin' => true,
]);
if (!env('ADMIN_SEED_PASSWORD')) {
    $this->command->warn("Admin password gerado: {$password}");
}
```

Adicionar em `.env.example`:
```
ADMIN_SEED_EMAIL=
ADMIN_SEED_PASSWORD=
```

**Esforço:** 15 minutos

---

### 2. Ausência de Policies (autorização granular)

**Situação:** `app/Policies/` não existe. Toda autorização é feita por `hasPermission()` no User, que é binário por módulo. Não há autorização por recurso (ex: "posso editar ESTE produto?").

**Risco:** usuários com permissão `produtos` podem editar qualquer produto, incluindo os de outros estilistas.

**Fix:** Criar Policies para pelo menos:
- `ProdutoPolicy` — edit/delete/editObsDesigner
- `MovimentacaoPolicy` — delete (só quem criou ou admin)
- `SugestaoPolicy` — edit/delete (só autor)

**Esforço:** 2-4h dependendo da granularidade

---

### 3. Seeder de permissões ausente

**Situação:** Permissões são criadas **manualmente via UI**. Deploy em novo ambiente quebra até alguém criar permissões.

**Fix:** Criar `database/seeders/PermissionSeeder.php`:
```php
$permissions = [
    ['name' => 'produtos', 'descricao' => 'Produtos'],
    ['name' => 'cadastros', 'descricao' => 'Cadastros gerais'],
    ['name' => 'consultas', 'descricao' => 'Consultas'],
    ['name' => 'movimentacoes', 'descricao' => 'Movimentações'],
    ['name' => 'kanban', 'descricao' => 'Kanban'],
    ['name' => 'planejamento', 'descricao' => 'Planejamento de capacidade'],
    ['name' => 'sugestoes', 'descricao' => 'Sugestões'],
    ['name' => 'logistica', 'descricao' => 'Logística'],
    ['name' => 'criacao', 'descricao' => 'Criação'],  // futura
];
foreach ($permissions as $p) {
    Permission::firstOrCreate(['name' => $p['name']], $p);
}
```

**Esforço:** 30 minutos

---

## Prioridade ALTA (próximos sprints)

### 4. Rate limiting em rotas de API
**Situação:** Rotas `api.notificacoes.nao-visualizadas` e `api.sugestoes.nao-lidas-count` são chamadas a cada 30s por todos os usuários logados simultaneamente (via polling no sidebar). Sem throttle.

**Fix:** Em `routes/web.php` ou `routes/api.php`:
```php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/api/notificacoes/nao-visualizadas', ...);
    Route::get('/api/sugestoes/nao-lidas-count', ...);
});
```

Ou melhor: **trocar polling por WebSocket/Laravel Echo** (ver item 11).

**Esforço:** 30 min (throttle) / 1-2 dias (websocket)

---

### 5. Validação de upload de arquivos
**Situação:** Produto tem `anexo_ficha_producao` e `anexo_catalogo_vendas`. Verificar no ProdutoController se há validação de MIME type e tamanho.

**Fix esperado:**
```php
'anexo_ficha_producao' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
```

**Esforço:** 1h para revisar todos os uploads

---

### 6. DB::raw — auditar bindings
**Ocorrências encontradas:**
- `app/Models/Marca.php:66, 79`
- `app/Http/Controllers/ProdutoController.php:117, 133`
- `app/Http/Controllers/DashboardController.php`

Análise automática indicou **uso seguro** (agregações `MAX(id)`, `count(*)` sem interpolação). **Revisar manualmente** para confirmar antes de deploy.

**Esforço:** 30 minutos

---

### 7. Mass assignment em Controllers
**Situação:** Models têm `$fillable` definido, mas controllers podem usar `$request->all()` que aceita qualquer campo que esteja no fillable.

**Fix:** Trocar por `$request->validated()` sempre, com Form Requests:
```php
// Ruim
Produto::create($request->all());

// Bom
Produto::create($request->validated());
```

**Esforço:** 2h para percorrer todos os controllers

---

## Prioridade MÉDIA

### 8. Logs de atividade cobrem operações críticas?
**Situação:** Spatie ActivityLog está instalado. Verificar se está configurado nos models de mudanças críticas:

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Produto extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['referencia', 'status_id', 'preco_atacado', 'etapa_producao_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

Aplicar em: `Produto`, `Movimentacao`, `User` (alterações de permissão), `Tecido`.

**Esforço:** 1h

---

### 9. Soft deletes — revisar queries
**Situação:** Models usam soft delete. Verificar se queries sem `withTrashed()` não vazam dados deletados acidentalmente.

**Ação:** grep por `->withTrashed()` e `->onlyTrashed()` para ver onde está sendo usado conscientemente; revisar se há exposição indevida.

**Esforço:** 1h

---

### 10. N+1 queries
**Situação:** Sem ferramenta instalada para detectar. Relacionamentos complexos (Produto → tecidos → pivot → consumo) podem gerar N+1.

**Fix:** Instalar `barryvdh/laravel-debugbar` em dev e revisar páginas pesadas:
- Dashboard
- Kanban (produtos + movimentações + etapas)
- Consulta "Produtos ativos por localização"

Usar `->with(['marca', 'estilista', 'grupoProduto', 'status'])` onde aplicável.

**Esforço:** 2-4h dependendo do que aparecer

---

## Prioridade BAIXA (nice-to-have)

### 11. Polling de notificações → WebSocket
**Situação:** Todos os usuários batem na API a cada 30s. Com 20 usuários = 40 requests/min só para badge.

**Fix:** Laravel Reverb (incluído no Laravel 11+) ou Pusher + Laravel Echo.

**Esforço:** 1-2 dias

---

### 12. Tests
**Situação:** Pasta `tests/` existe mas cobertura desconhecida. Regressões são caçadas manualmente.

**Fix:** Cobertura mínima para fluxos críticos:
- Feature test de criação de produto
- Feature test de movimentação entre localizações
- Unit test de cálculos (dias de atraso, consumo de tecido)

**Esforço:** ongoing — começar pelos fluxos mais usados

---

### 13. Queue para jobs pesados
**Situação:** Gerações de PDF/Excel e envio de emails provavelmente rodam síncrono.

**Fix:** Implementar queue (Redis ou database driver) para:
- Exports de relatório
- Envio de notificações
- Geração de PDFs

**Esforço:** 1 dia de setup + ongoing para cada job

---

### 14. Observabilidade
**Situação:** Sem integração com serviço de monitoramento.

**Fix:** Laravel Telescope (dev) + Sentry/Bugsnag (produção).

**Esforço:** 2-4h

---

## Ordem sugerida de ataque

1. **Sprint 1 (esta semana):** Itens 1, 2, 3 — fundamentos de segurança (~1 dia)
2. **Sprint 2:** Itens 4, 5, 6, 7 — hardening (~2 dias)
3. **Paralelo:** implementação do [Módulo Criação](./MODULO_CRIACAO_SPEC.md)
4. **Sprint 3:** Itens 8, 9, 10 — qualidade e performance (~3 dias)
5. **Backlog:** Itens 11, 12, 13, 14

---

## Referências

- Spec do Módulo Criação: [`MODULO_CRIACAO_SPEC.md`](./MODULO_CRIACAO_SPEC.md)
- Status geral do projeto: [`STATUS_E_PROXIMOS_PASSOS.md`](./STATUS_E_PROXIMOS_PASSOS.md)