# Módulo Criação — Especificação Técnica

> **Status:** Planejado, não implementado
> **Pré-requisito:** Sidebar lateral já instalada
> **Objetivo:** Gerenciar produtos em fase de criação/desenvolvimento antes de entrarem no fluxo de produção

---

## ⚠ Observação sobre estado do código

Esta spec foi escrita com base em análise automática. Alguns models (`DirecionamentoComercial`, `EtapaProducao`) foram referenciados em conversas mas **não foram confirmados** no código. Antes de iniciar a implementação, verifique no seu ambiente local:

```powershell
cd E:\projetos\RotaDoMar\windsurf
php artisan route:list | Select-String "direcionamento|etapa-producao|criacao"
ls app/Models/ | Select-String "Direcionamento|Etapa"
```

Se os arquivos já existem, adapte o escopo. Se não existem, crie-os conforme seção 4 abaixo.

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
- **BEL (Criação/Produção):** define `direcionamento_comercial_id`, `etapa_producao_id`, transfere para Localização (facção)
- **Admin:** acesso total

---

## 2. Modelo de dados

### Decisão: adicionar colunas à tabela `produtos` existente

**Justificativa:** produto é o mesmo entity, apenas ganha atributos de criação. Evita JOIN desnecessário e matches o padrão atual.

### Novos campos em `produtos`

| Campo | Tipo | Null | Default | Descrição |
|---|---|---|---|---|
| `data_entrada_processo` | `date` | sim | null | Data manual de entrada no processo de criação |
| `obs_designer` | `text` | sim | null | Observações editáveis **apenas pelo estilista dono** |
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
                 ->whereNull('etapa_producao_id');  // regra de negócio a confirmar
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

    // Só o estilista dono do produto
    return $user->estilista_id === $produto->estilista_id;
}
```

Registrar em `AuthServiceProvider`:
```php
protected $policies = [
    Produto::class => ProdutoPolicy::class,
];
```

### Seeder de permissões

`database/seeders/CriacaoPermissionSeeder.php`:
```php
Permission::firstOrCreate(['name' => 'criacao'], [
    'descricao' => 'Módulo de Criação'
]);
```

---

## 4. Models dependentes (se não existirem)

### DirecionamentoComercial

Migration + Model + Controller + CRUD simples (seguindo padrão de `Marca` ou `Estilista`):

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

### EtapaProducao

```php
Schema::create('etapas_producao', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 100);
    $table->integer('ordem')->default(0);  // para ordenar no Kanban
    $table->string('cor_hex', 7)->nullable();  // visual no Kanban
    $table->boolean('ativo')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

Ambos os CRUDs já existem nos menus do sidebar (`direcionamentos-comerciais.index`, `etapas-producao.index`) — verifique se estão implementados.

---

## 5. Rotas

**Arquivo:** `routes/web.php` — adicionar dentro do grupo `auth`:

```php
Route::prefix('criacao')->name('criacao.')->middleware('permission:criacao')->group(function () {
    Route::get('/', [CriacaoController::class, 'index'])->name('index');
    Route::get('/kanban', [CriacaoController::class, 'kanban'])->name('kanban');
    Route::get('/bel', [CriacaoController::class, 'visaoBel'])->name('bel');
    Route::get('/criar', [CriacaoController::class, 'create'])->name('create');
    Route::post('/', [CriacaoController::class, 'store'])->name('store');
    Route::get('/{produto}/editar', [CriacaoController::class, 'edit'])->name('edit');
    Route::put('/{produto}', [CriacaoController::class, 'update'])->name('update');
    Route::patch('/{produto}/mover-etapa', [CriacaoController::class, 'moverEtapa'])->name('mover-etapa');
    Route::patch('/{produto}/transferir-faccao', [CriacaoController::class, 'transferirFaccao'])->name('transferir-faccao');
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
| `visaoBel` | Filtro especial para equipe BEL | `permission:criacao` + role BEL |
| `create` | Form de novo produto em criação | `permission:criacao,create` |
| `store` | Cria produto com `data_entrada_processo` preenchida | Request validation |
| `edit` | Form de edição | `permission:criacao,update` |
| `update` | Atualiza; bloqueia `obs_designer` se não for estilista dono | **Policy** `editObsDesigner` |
| `moverEtapa` | BEL muda `etapa_producao_id` | `permission:criacao,update` |
| `transferirFaccao` | BEL cria `Movimentacao` para uma `Localizacao` | `permission:criacao,update` |

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

---

## 7. Views

Pasta: `resources/views/criacao/`

| Arquivo | Descrição |
|---|---|
| `index.blade.php` | Tabela listagem com filtros (estilista, etapa, direcionamento, data) |
| `kanban.blade.php` | Colunas = etapas de produção; cards arrastáveis (Alpine.js Sortable) |
| `bel.blade.php` | Similar à index mas com ações de mover etapa / transferir facção |
| `create.blade.php` | Form de criação |
| `edit.blade.php` | Form de edição com `obs_designer` desabilitado conforme Policy |
| `_form.blade.php` | Partial com campos compartilhados create/edit |
| `_card.blade.php` | Card do produto para o Kanban |

### Detalhes da UI

- Cabeçalho com botão "Nova Criação"
- Tabs: **Lista** | **Kanban** | **Visão BEL**
- Filtros em Alpine.js reativo
- `obs_designer` renderizado como `<textarea disabled>` se `!$user->can('editObsDesigner', $produto)`
- Badge colorido no status da etapa (usar `etapa.cor_hex`)

---

## 8. Atualizar Sidebar

**Arquivo:** `resources/views/layouts/navigation.blade.php` — trocar o placeholder:

```blade
{{-- ANTES --}}
<x-sidebar-item href="#" :active="request()->routeIs('criacao.*')">

{{-- DEPOIS --}}
<x-sidebar-item href="{{ route('criacao.index') }}" :active="request()->routeIs('criacao.*')">
```

---

## 9. Logs (Spatie ActivityLog)

No `CriacaoController@moverEtapa` e `@transferirFaccao`:

```php
activity('criacao')
    ->performedOn($produto)
    ->causedBy(auth()->user())
    ->withProperties([
        'de' => $etapaAnterior,
        'para' => $etapaNova,
    ])
    ->log('etapa_movida');
```

---

## 10. Checklist de entrega

- [ ] Migration criada e rodada
- [ ] Models `DirecionamentoComercial` e `EtapaProducao` verificados/criados
- [ ] `Produto` atualizado (fillable, casts, relationships, scope)
- [ ] Policy `ProdutoPolicy` + registro
- [ ] Permissão `criacao` no seeder
- [ ] `CriacaoController` + `CriacaoRequest`
- [ ] Rotas no `web.php`
- [ ] 7 views em `resources/views/criacao/`
- [ ] Sidebar: `href="#"` → `route('criacao.index')`
- [ ] Logs via Spatie no `moverEtapa` e `transferirFaccao`
- [ ] Teste manual: criar produto → editar como estilista → mover etapa como BEL → transferir facção
- [ ] Commit e push

---

## 11. Decisões a confirmar antes de começar

1. **Estilista dono = `produto.estilista_id`**, correto? Ou há outra regra (ex: criador do registro)?
2. **Usuário ↔ Estilista:** `users` tem FK para `estilistas`? (visto `localizacao_id` em users, mas não `estilista_id`)
3. **Etapa inicial:** quando cria produto em Criação, qual é a etapa default? A primeira `ordem` ativa?
4. **Regra para "em criação":** quando um produto sai do módulo? Quando `etapa_producao_id IS NULL` e vira `NOT NULL`? Ou quando transfere para facção (cria Movimentação)?
5. **Campos obrigatórios na criação:** `referencia`, `descricao`, `estilista_id`, `marca_id`? Ou mais permissivo?