# Módulo Criação — Especificação Técnica

> **Status:** Implementação inicial em andamento
> **Pré-requisito:** Sidebar lateral já instalada
> **Objetivo:** Gerenciar produtos em fase de criação/desenvolvimento antes de entrarem no fluxo de produção

---

## ⚠ Observação sobre estado do código

Esta spec foi ajustada após validação do projeto. `DirecionamentoComercial` e `EtapaProducao` já existem no código e devem ser reaproveitados.

```powershell
cd E:\projetos\RotaDoMar\windsurf
php artisan route:list | Select-String "direcionamento|etapa-producao|criacao"
ls app/Models/ | Select-String "Direcionamento|Etapa"
```

O módulo `criacao` já possui implementação inicial de migration, policy, controller, request, rotas e views base. Use esta spec como referência para as próximas ondas.

---

## 1. Contexto de negócio

**Fluxo desejado:**

```
[Criação]           →  [BEL]              →  [Facção / Produção]
 estilistas             equipe de triagem     localizacoes físicas
 cria referência        valida e direciona    produz
 obs_designer           define direcionamento  movimentações
 data entrada           define etapa
```

**Papéis:**
- **Estilista:** cria produto, preenche `obs_designer`, `data_entrada_processo`
- **BEL (Criação/Produção):** nome da visão operacional da Criação; define `direcionamento_comercial_id` e `etapa_producao_id`
- **Admin:** acesso total

---

## 2. Modelo de dados

### Decisão: adicionar colunas à tabela `produtos` existente

**Justificativa:** produto é o mesmo entity, apenas ganha atributos de criação. Evita JOIN desnecessário e matches o padrão atual.

### Novos campos em `produtos`

| Campo | Tipo | Null | Default | Descrição |
|---|---|---|---|---|
| `data_entrada_processo` | `date` | sim | null | Data manual de entrada no processo de criação |
| `obs_designer` | `text` | sim | null | Observações editáveis por admin e pelo usuário vinculado ao estilista do produto |
| `direcionamento_comercial_id` | `bigint FK` | sim | null | FK → `direcionamentos_comerciais` |
| `etapa_producao_id` | `bigint FK` | sim | null | FK → `etapas_producao` |

### Migration

**Arquivo:** `database/migrations/2026_04_23_XXXXXX_add_criacao_fields_to_produtos_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->date('data_entrada_processo')->nullable()->after('data_cadastro');
            $table->text('obs_designer')->nullable()->after('descricao');
            $table->foreignId('direcionamento_comercial_id')
                  ->nullable()
                  ->constrained('direcionamentos_comerciais')
                  ->nullOnDelete();
            $table->foreignId('etapa_producao_id')
                  ->nullable()
                  ->constrained('etapas_producao')
                  ->nullOnDelete();
            $table->index(['data_entrada_processo', 'etapa_producao_id'], 'idx_criacao_fluxo');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign(['direcionamento_comercial_id']);
            $table->dropForeign(['etapa_producao_id']);
            $table->dropIndex('idx_criacao_fluxo');
            $table->dropColumn([
                'data_entrada_processo',
                'obs_designer',
                'direcionamento_comercial_id',
                'etapa_producao_id',
            ]);
        });
    }
};
```

### Atualização no Model `Produto`

```php
// app/Models/Produto.php
protected $fillable = [
    // ... campos existentes,
    'data_entrada_processo',
    'obs_designer',
    'direcionamento_comercial_id',
    'etapa_producao_id',
];

protected $casts = [
    'data_entrada_processo' => 'date',
    // ... outros casts
];

public function direcionamentoComercial()
{
    return $this->belongsTo(DirecionamentoComercial::class);
}

public function etapaProducao()
{
    return $this->belongsTo(EtapaProducao::class);
}

public function scopeEmCriacao($query)
{
    return $query->whereNotNull('data_entrada_processo')
                 ->whereNull('etapa_producao_id');
}
```

---

## 3. Permissões

### Nova permissão: `criacao`

Ações: `read`, `create`, `update`, `delete`

### Autorização para `obs_designer`

Criar **Policy** `app/Policies/ProdutoPolicy.php`:

```php
public function editObsDesigner(User $user, Produto $produto): bool
{
    // Admin sempre pode
    if ($user->isAdmin()) return true;

    return (int) ($user->estilista?->id ?? 0) === (int) $produto->estilista_id;
}
```

Registrar em `AppServiceProvider`:

```php
Gate::policy(Produto::class, ProdutoPolicy::class);
```

### Seeder de permissões

`database/seeders/PermissionSeeder.php`:

```php
Permission::firstOrCreate(['name' => 'criacao'], [
    'descricao' => 'Módulo de Criação'
]);
```

---

## 4. Models dependentes

### DirecionamentoComercial

Já existe no projeto.

```php
// Migration
Schema::create('direcionamentos_comerciais', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 100);
    $table->boolean('ativo')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

Ambos os CRUDs já existem e devem ser reaproveitados.

---

## 5. Rotas

**Arquivo:** `routes/web.php` — adicionar dentro do grupo `auth`:

```php
Route::prefix('criacao')->name('criacao.')->middleware('permission:criacao')->group(function () {
    Route::get('/', [CriacaoController::class, 'index'])->name('index');
    Route::get('/kanban', [CriacaoController::class, 'kanban'])->name('kanban');
    Route::get('/bel', [CriacaoController::class, 'bel'])->name('bel');
    Route::get('/create', [CriacaoController::class, 'create'])->name('create');
    Route::post('/', [CriacaoController::class, 'store'])->name('store');
    Route::get('/{produto}/edit', [CriacaoController::class, 'edit'])->name('edit');
    Route::put('/{produto}', [CriacaoController::class, 'update'])->name('update');
    Route::patch('/{produto}/mover-etapa', [CriacaoController::class, 'moverEtapa'])->name('mover-etapa');
});
```

---

## 6. Controller

**Arquivo:** `app/Http/Controllers/CriacaoController.php`

Responsabilidades principais:

| Método | Descrição | Autorização |
|---|---|---|
| `index` | Lista paginada de produtos em criação | `permission:criacao` |
| `kanban` | Vista Kanban agrupada por `etapa_producao_id` | `permission:criacao` |
| `bel` | Filtro/listagem especializada da visão BEL | `permission:criacao` |
| `create` | Form de novo produto em criação | `permission:criacao,create` |
| `store` | Cria produto com `data_entrada_processo` preenchida | Request validation |
| `edit` | Form de edição | `permission:criacao,update` |
| `update` | Atualiza; bloqueia `obs_designer` se não for estilista dono | **Policy** `editObsDesigner` |
| `moverEtapa` | BEL muda `etapa_producao_id` | `permission:criacao,update` |

Regra funcional confirmada:
- ao definir etapa, o produto sai apenas da listagem da Criação;
- ele continua visível em `produtos`;
- o status passa para `AGUARDANDO DESENVOLVIMENTO`, já existente no banco.

### Form Request

`app/Http/Requests/CriacaoRequest.php`:

```php
public function rules(): array
{
    return [
        'referencia' => ['required', 'string', 'max:50'],
        'descricao' => ['required', 'string', 'max:255'],
        'estilista_id' => ['required', 'exists:estilistas,id'],
        'marca_id' => ['required', 'exists:marcas,id'],
        'data_entrada_processo' => ['nullable', 'date'],
        'obs_designer' => ['nullable', 'string'],
        'direcionamento_comercial_id' => ['nullable', 'exists:direcionamentos_comerciais,id'],
        'etapa_producao_id' => ['nullable', 'exists:etapas_producao,id'],
    ];
}
```

### Views

Pasta: `resources/views/criacao/`

| Arquivo | Descrição |
|---|---|
| `index.blade.php` | Tabela listagem com filtros (estilista, etapa, direcionamento, data) |
| `kanban.blade.php` | Colunas/listas por etapa para visualização rápida |
| `bel.blade.php` | Similar à index, representando a visão BEL dentro da própria Criação |
| `create.blade.php` | Form de criação |
| `edit.blade.php` | Form de edição com `obs_designer` desabilitado conforme Policy |
| `_form.blade.php` | Partial com campos compartilhados create/edit |

---

## 7. Atualizar Sidebar

**Arquivo:** `resources/views/components/sidebar.blade.php` — apontar para a rota real:

```blade
{{-- ANTES --}}
<x-sidebar-item href="#" :active="request()->routeIs('criacao.*')">

{{-- DEPOIS --}}
<x-sidebar-item :href="route('criacao.index')" :active="request()->routeIs('criacao.*')">
```

## 8. Checklist de entrega

- [ ] Migration criada e rodada
- [ ] Models `DirecionamentoComercial` e `EtapaProducao` verificados/criados
- [ ] `Produto` atualizado (fillable, casts, relationships, scope)
- [ ] Policy `ProdutoPolicy` + registro
- [ ] Permissão `criacao` no seeder
- [ ] `CriacaoController` + `CriacaoRequest`
- [ ] Rotas no `web.php`
- [ ] views iniciais em `resources/views/criacao/`
- [ ] Sidebar: `href="#"` → `route('criacao.index')`
- [ ] Teste manual: criar produto → editar como estilista → mover etapa como BEL
- [ ] Commit e push

---

## 11. Decisões confirmadas

1. **Estilista dono = `produto.estilista_id`**.
2. **Usuário ↔ Estilista** será resolvido por `estilistas.user_id`, opcional na v1.
3. **Visão BEL** é a própria visão operacional da Criação.
4. **Saída da Criação** ocorre ao definir `etapa_producao_id`.
5. **Após a saída**, o produto continua em `produtos` com status `AGUARDANDO DESENVOLVIMENTO`.
