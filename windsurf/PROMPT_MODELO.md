# 🚀 PROMPT INICIAL - PROJETO [NOME_DO_PROJETO]

## 📋 CONTEXTO
Criar um sistema ERP modular para gestão de [processo/negócio específico], inspirado no modelo de sucesso do Rota do Mar (sistema de gestão de produção têxtil).

---

## 🏗️ STACK TECNOLÓGICO OBRIGATÓRIO

### Backend
- **PHP 8.2+** com tipagem estrita
- **Laravel 12.x** (framework principal)
- **Laravel Breeze 2.x** (autenticação e scaffolding)
- **Spatie Laravel Activity Log** (auditoria de ações)
- **Barryvdh Laravel DOMPDF** (relatórios PDF)
- **SQLite/MySQL** (banco de dados)

### Frontend
- **Vite 6.x** (build tool)
- **Tailwind CSS 3.x** com modo escuro (`darkMode: 'class'`)
- **Alpine.js 3.x** (interatividade leve)
- **jQuery 3.6+** (para componentes complexos como Select2)
- **Select2** (dropdowns avançados)
- **Chart.js 4.x** (gráficos e dashboards)
- **Blade** (templating engine)

### Tema Visual
```javascript
// tailwind.config.js - Paleta de cores personalizada
colors: {
    primary: {
        50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
        300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9',
        600: '#0284c7', 700: '#0369a1', 800: '#075985',
        900: '#0c4a6e', 950: '#082f49',
    },
    secondary: {
        50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
        300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b',
        600: '#475569', 700: '#334155', 800: '#1e293b',
        900: '#0f172a', 950: '#020617',
    }
}
```

---

## 🏛️ ARQUITETURA DO SISTEMA

### 1. ESTRUTURA DE DIRETÓRIOS
```
app/
├── Http/
│   ├── Controllers/        # CRUDs organizados por domínio
│   └── Middleware/         # Autenticação, permissões, logs
├── Models/                 # Eloquent com casts, scopes, accessors
├── Services/               # [Opcional] Lógica de negócio complexa
database/
├── migrations/             # Ordenadas por data de criação
├── seeders/                # Dados iniciais e de teste
└── factories/              # Factories para testes
resources/
├── views/
│   ├── layouts/            # app.blade.php com dark mode
│   ├── components/         # Componentes Blade reutilizáveis
│   └── [modulos]/          # CRUDs organizados por módulo
├── css/
│   └── app.css             # Imports Tailwind + custom CSS
└── js/
    └── app.js              # Alpine.js + Vite
routes/
├── web.php                 # Rotas principais (auth:sanctum)
└── api.php                 # [Se necessário] API REST
```

### 2. PADRÕES DE CÓDIGO

#### Models (Exemplo: Produto)
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Produto extends Model
{
    use LogsActivity;

    protected $fillable = [
        'referencia',
        'descricao',
        'marca_id',
        'estilista_id',
        'status_id',
        // ... campos específicos
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data_entrega_faccao' => 'date',
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->useLogName('produto');
    }

    // Relacionamentos
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function tecidos(): BelongsToMany
    {
        return $this->belongsToMany(Tecido::class, 'produto_tecido')
            ->withPivot('consumo');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Accessors
    public function getCustoTotalAttribute(): float
    {
        return $this->preco_custo * $this->quantidade;
    }
}
```

#### Controllers (Resource Controller Pattern)
```php
<?php
namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        $query = Modelo::with(['relacionamentos'])
            ->orderBy('created_at', 'desc');

        // Filtros dinâmicos
        if ($request->filled('busca')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('codigo', 'like', "%{$request->busca}%");
            });
        }

        $itens = $query->paginate(20)->withQueryString();

        return view('modelos.index', compact('itens'));
    }

    public function create()
    {
        $relacionamentos = Relacionado::orderBy('nome')->get();
        return view('modelos.create', compact('relacionamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'relacionado_id' => 'required|exists:relacionados,id',
            // ... validações específicas
        ]);

        DB::beginTransaction();
        try {
            $item = Modelo::create($validated);

            // Relacionamentos many-to-many
            if ($request->has('relacionados')) {
                $item->relacionados()->sync($request->relacionados);
            }

            DB::commit();
            return redirect()->route('modelos.index')
                ->with('success', 'Item criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar item: ' . $e->getMessage());
            return back()->with('error', 'Erro ao criar item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Modelo $modelo)
    {
        $relacionamentos = Relacionado::orderBy('nome')->get();
        return view('modelos.edit', compact('modelo', 'relacionamentos'));
    }

    public function update(Request $request, Modelo $modelo)
    {
        // Similar ao store...
    }

    public function destroy(Modelo $modelo)
    {
        try {
            $modelo->delete();
            return redirect()->route('modelos.index')
                ->with('success', 'Item excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }
}
```

### 3. COMPONENTES BLADE REUTILIZÁVEIS

#### Layout Base (`layouts/app.blade.php`)
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Dark Mode Script -->
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || 
            (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-slate-900 dark:text-slate-100 
             bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- Loading Overlay -->
    <div id="global-loading-overlay" class="fixed inset-0 bg-slate-900/60 
         backdrop-blur-sm flex items-center justify-center z-[100]" style="display: none;">
        <!-- Spinner... -->
    </div>

    <div class="min-h-screen">
        @include('layouts.navigation')
        
        @isset($header)
            <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md 
                          border-b border-slate-200 dark:border-slate-800 
                          sticky top-16 z-30 transition-all duration-300">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="glass border-l-4 border-green-500 bg-green-50/50 
                            dark:bg-green-900/20 text-green-700 dark:text-green-400 
                            p-4 rounded-xl mb-4 relative overflow-hidden" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @stack('scripts')
</body>
</html>
```

#### Cards de Dashboard
```blade
<!-- Componente: card-stat.blade.php -->
<div class="glass dark:glass-dark rounded-2xl p-6 shadow-lg 
            hover:shadow-xl transition-all duration-300 group">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ $label }}
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                {{ $value }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 
                    flex items-center justify-center group-hover:scale-110 transition-transform">
            {{ $icon }}
        </div>
    </div>
</div>
```

### 4. FUNCIONALIDADES PADRÃO

#### Sistema de Filtros (UserFilter)
```php
// Model UserFilter
class UserFilter extends Model
{
    protected $fillable = [
        'user_id',
        'filter_name',
        'filter_type',
        'filters', // JSON
        'is_default'
    ];

    protected $casts = [
        'filters' => 'array',
        'is_default' => 'boolean'
    ];
}
```

#### Dashboard com Gráficos
- Cards de estatísticas com cores dinâmicas
- Gráficos Chart.js interativos
- Indicadores de tendência (subiu/desceu)
- Tabela de atividades recentes

#### Kanban Board (opcional)
- Drag & drop entre colunas
- Cards com informações resumidas
- Filtros por status/responsável

---

## 📝 REQUISITOS FUNCIONAIS

### Módulos Obrigatórios:
1. **Autenticação** (Laravel Breeze + perfis de usuário)
2. **Cadastros Básicos** (CRUDs com soft deletes)
3. **Relacionamentos** (Many-to-many com pivot tables)
4. **Anexos/Arquivos** (upload com validação de tipos)
5. **Auditoria** (Activity Log em todas as ações)
6. **Relatórios** (PDF exportável)
7. **Dashboard** (visão geral com métricas)

### Funcionalidades UX:
- [ ] Modo escuro/claro persistente
- [ ] Loading states em ações demoradas
- [ ] Toasts/notificações de sucesso/erro
- [ ] Confirmação antes de exclusões
- [ ] Filtros salvos por usuário
- [ ] Paginação com lazy loading
- [ ] Responsivo (mobile-first)

---

## 🎨 GUIA DE ESTILOS

### Botões
```blade
<!-- Primário -->
<button class="btn-primary">Salvar</button>
<!-- CSS: bg-primary-600 hover:bg-primary-700 text-white rounded-lg px-4 py-2 -->

<!-- Fantasma -->
<button class="btn-ghost-primary">
    <svg class="h-4 w-4 mr-1">...</svg>
    Adicionar
</button>
<!-- CSS: text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 -->
```

### Formulários
```blade
<div class="form-group">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Nome <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nome" required
           class="block w-full border-gray-300 dark:border-slate-600 
                  bg-white dark:bg-slate-800 text-gray-900 dark:text-white 
                  focus:border-primary-500 focus:ring-primary-500 
                  rounded-md shadow-sm">
    @error('nome')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### Tabelas
```blade
<div class="glass dark:glass-dark rounded-xl overflow-hidden shadow-lg">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
        <thead class="bg-gray-50 dark:bg-slate-900/50">
            <!-- headers -->
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            <!-- rows com hover:bg-gray-50 dark:hover:bg-slate-700/50 -->
        </tbody>
    </table>
</div>
```

---

## 🔧 COMANDOS DE SETUP

```bash
# 1. Criar projeto Laravel
composer create-project laravel/laravel [nome-projeto] --prefer-dist

# 2. Instalar Laravel Breeze (Blade)
composer require laravel/breeze --dev
php artisan breeze:install blade

# 3. Instalar dependências backend
composer require spatie/laravel-activitylog
composer require barryvdh/laravel-dompdf

# 4. Instalar dependências frontend
npm install
npm install -D @tailwindcss/forms alpinejs chart.js

# 5. Publicar configs
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

# 6. Rodar migrations
php artisan migrate

# 7. Build assets
npm run build

# 8. Iniciar servidor
php artisan serve --port=9000
```

---

## ✅ CRITÉRIOS DE ACEITAÇÃO

Antes de entregar cada funcionalidade, verificar:

- [ ] Funciona no modo escuro e claro
- [ ] Responsivo (testar em 320px, 768px, 1920px)
- [ ] Mensagens de erro amigáveis
- [ ] Logging de atividades (auditoria)
- [ ] Validação de formulários no frontend e backend
- [ ] Proteção contra CSRF em todos os forms
- [ ] Ícones consistentes (Lucide/Heroicons)
- [ ] Código sem warnings do PHP/Laravel Pint

---

## 📚 REFERÊNCIAS

- [Documentação Laravel](https://laravel.com/docs/12.x)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [Laravel Activity Log](https://spatie.be/docs/laravel-activitylog)
- [Laravel Breeze](https://laravel.com/docs/12.x/starter-kits#laravel-breeze)

---

**Crie o sistema seguindo essas diretrizes. Priorize código limpo, consistente e bem documentado.**
