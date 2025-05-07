# Wind Surf Tool Management System - Modelos e Migrações

## Migrações

Aqui estão as migrações necessárias para criar todas as tabelas do sistema. Execute os comandos abaixo para gerar os arquivos de migração:

```bash
# Criar migrações para todas as tabelas
php artisan make:migration create_tecidos_table
php artisan make:migration create_estilistas_table
php artisan make:migration create_marcas_table
php artisan make:migration create_grupo_produtos_table
php artisan make:migration create_localizacoes_table
php artisan make:migration create_tipos_table
php artisan make:migration create_situacoes_table
php artisan make:migration create_statuses_table
php artisan make:migration create_produtos_table
php artisan make:migration create_movimentacoes_table
```

### Conteúdo das Migrações

#### 1. create_tecidos_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tecidos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->date('data_cadastro');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tecidos');
    }
};
```

#### 2. create_estilistas_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estilistas', function (Blueprint $table) {
            $table->id();
            $table->string('estilista');
            $table->boolean('ativo')->default(true);
            $table->date('data_cadastro');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estilistas');
    }
};
```

#### 3. create_marcas_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->boolean('ativo')->default(true);
            $table->date('data_cadastro');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marcas');
    }
};
```

#### 4. create_grupo_produtos_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupo_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('grupo_produto');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupo_produtos');
    }
};
```

#### 5. create_localizacoes_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('localizacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_localizacao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('localizacoes');
    }
};
```

#### 6. create_tipos_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos');
    }
};
```

#### 7. create_situacoes_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('situacoes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('situacoes');
    }
};
```

#### 8. create_statuses_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
```

#### 9. create_produtos_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('referencia')->unique();
            $table->string('descricao');
            $table->date('data_cadastro');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->integer('quantidade')->default(0);
            $table->foreignId('tecido_id')->constrained('tecidos');
            $table->foreignId('estilista_id')->constrained('estilistas');
            $table->foreignId('grupo_produto_id')->constrained('grupo_produtos');
            $table->decimal('preco_atacado', 10, 2);
            $table->decimal('preco_varejo', 10, 2);
            $table->foreignId('status_id')->constrained('statuses');
            $table->string('ficha_producao')->nullable();
            $table->string('catalogo_vendas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtos');
    }
};
```

#### 10. create_movimentacoes_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('comprometido')->default(0);
            $table->foreignId('localizacao_id')->constrained('localizacoes');
            $table->date('data_entrada');
            $table->date('data_saida')->nullable();
            $table->foreignId('tipo_id')->constrained('tipos');
            $table->foreignId('situacao_id')->constrained('situacoes');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimentacoes');
    }
};
```

## Modelos

Crie os seguintes modelos usando o comando Artisan:

```bash
php artisan make:model Tecido
php artisan make:model Estilista
php artisan make:model Marca
php artisan make:model GrupoProduto
php artisan make:model Localizacao
php artisan make:model Tipo
php artisan make:model Situacao
php artisan make:model Status
php artisan make:model Produto
php artisan make:model Movimentacao
```

### Conteúdo dos Modelos

#### 1. Tecido.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tecido extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tecidos';
    
    protected $fillable = [
        'descricao',
        'data_cadastro',
        'ativo',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
```

#### 2. Estilista.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estilista extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estilistas';
    
    protected $fillable = [
        'estilista',
        'ativo',
        'data_cadastro',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
```

#### 3. Marca.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'marcas';
    
    protected $fillable = [
        'marca',
        'ativo',
        'data_cadastro',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
```

#### 4. GrupoProduto.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoProduto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grupo_produtos';
    
    protected $fillable = [
        'grupo_produto',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
```

#### 5. Localizacao.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localizacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'localizacoes';
    
    protected $fillable = [
        'nome_localizacao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
```

#### 6. Tipo.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos';
    
    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
```

#### 7. Situacao.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Situacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'situacoes';
    
    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
```

#### 8. Status.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'statuses';
    
    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
```

#### 9. Produto.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produtos';
    
    protected $fillable = [
        'referencia',
        'descricao',
        'data_cadastro',
        'marca_id',
        'quantidade',
        'tecido_id',
        'estilista_id',
        'grupo_produto_id',
        'preco_atacado',
        'preco_varejo',
        'status_id',
        'ficha_producao',
        'catalogo_vendas',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'preco_atacado' => 'decimal:2',
        'preco_varejo' => 'decimal:2',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function tecido()
    {
        return $this->belongsTo(Tecido::class);
    }

    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    public function grupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
```

#### 10. Movimentacao.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimentacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimentacoes';
    
    protected $fillable = [
        'produto_id',
        'comprometido',
        'localizacao_id',
        'data_entrada',
        'data_saida',
        'tipo_id',
        'situacao_id',
        'observacao',
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function situacao()
    {
        return $this->belongsTo(Situacao::class);
    }
}
```

## Factories e Seeders

Crie factories e seeders para popular o banco de dados com dados de teste:

```bash
php artisan make:factory TecidoFactory
php artisan make:factory EstilistaFactory
php artisan make:factory MarcaFactory
php artisan make:factory GrupoProdutoFactory
php artisan make:factory LocalizacaoFactory
php artisan make:factory TipoFactory
php artisan make:factory SituacaoFactory
php artisan make:factory StatusFactory
php artisan make:factory ProdutoFactory
php artisan make:factory MovimentacaoFactory

php artisan make:seeder TecidoSeeder
php artisan make:seeder EstilistaSeeder
php artisan make:seeder MarcaSeeder
php artisan make:seeder GrupoProdutoSeeder
php artisan make:seeder LocalizacaoSeeder
php artisan make:seeder TipoSeeder
php artisan make:seeder SituacaoSeeder
php artisan make:seeder StatusSeeder
php artisan make:seeder ProdutoSeeder
php artisan make:seeder MovimentacaoSeeder
php artisan make:seeder DatabaseSeeder
```

### Exemplo de Seeder (TipoSeeder.php)

```php
<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['descricao' => 'Criação', 'ativo' => true],
            ['descricao' => 'Peça Piloto', 'ativo' => true],
            ['descricao' => 'Monstruário', 'ativo' => true],
        ];

        foreach ($tipos as $tipo) {
            Tipo::create($tipo);
        }
    }
}
```

### Exemplo de Seeder (SituacaoSeeder.php)

```php
<?php

namespace Database\Seeders;

use App\Models\Situacao;
use Illuminate\Database\Seeder;

class SituacaoSeeder extends Seeder
{
    public function run()
    {
        $situacoes = [
            ['descricao' => 'Ativo', 'ativo' => true],
            ['descricao' => 'Produção', 'ativo' => true],
            ['descricao' => 'Compras', 'ativo' => true],
            ['descricao' => 'Cancelado', 'ativo' => true],
        ];

        foreach ($situacoes as $situacao) {
            Situacao::create($situacao);
        }
    }
}
```

### Exemplo de Seeder (StatusSeeder.php)

```php
<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['descricao' => 'Em processo', 'ativo' => true],
            ['descricao' => 'Cancelado', 'ativo' => true],
            ['descricao' => 'Ativo', 'ativo' => true],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
```

### DatabaseSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TecidoSeeder::class,
            EstilistaSeeder::class,
            MarcaSeeder::class,
            GrupoProdutoSeeder::class,
            LocalizacaoSeeder::class,
            TipoSeeder::class,
            SituacaoSeeder::class,
            StatusSeeder::class,
            ProdutoSeeder::class,
            MovimentacaoSeeder::class,
        ]);
    }
}
```
