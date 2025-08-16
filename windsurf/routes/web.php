<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TecidoController;
use App\Http\Controllers\EstilistaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\GrupoProdutoController;
use App\Http\Controllers\LocalizacaoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\SituacaoController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProdutoAnexoController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/dados-por-ano', [DashboardController::class, 'getDadosPorAno'])->name('dashboard.dados-por-ano');
    Route::get('/dashboard/produtos-por-estilista', [DashboardController::class, 'produtosPorEstilista'])->name('dashboard.produtos-por-estilista');
    
    // Rota para servir arquivos de rede
    Route::get('/arquivo/rede', [\App\Http\Controllers\ArquivoController::class, 'servirArquivoRede'])->name('arquivo.rede');

    // Rotas para gerenciamento de usuários (apenas para administradores)
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);

        // Rotas para gerenciamento de grupos de usuários
        Route::get('user-groups', [\App\Http\Controllers\UserGroupController::class, 'index'])->name('user-groups.index');
        Route::get('user-groups/{user}/edit', [\App\Http\Controllers\UserGroupController::class, 'edit'])->name('user-groups.edit');
        Route::put('user-groups/{user}', [\App\Http\Controllers\UserGroupController::class, 'update'])->name('user-groups.update');
        Route::get('user-groups/{user}/permissions', [\App\Http\Controllers\UserGroupController::class, 'showPermissions'])->name('user-groups.permissions');

        // Rotas para gerenciamento de permissões
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    });

    // Routes para Tecidos
    Route::resource('tecidos', TecidoController::class);
    Route::get('tecidos/{tecido}/atualizar-estoque', [TecidoController::class, 'atualizarEstoque'])->name('tecidos.atualizar-estoque');
    Route::get('tecidos-atualizar-todos-estoques', [TecidoController::class, 'atualizarTodosEstoques'])->name('tecidos.atualizar-todos-estoques');
    Route::get('tecidos/{tecido}/estoque-por-cor', [TecidoController::class, 'estoquePorCor'])->name('tecidos.estoque-por-cor');
    Route::post('tecidos/{tecido}/salvar-quantidades', [TecidoController::class, 'salvarQuantidades'])->name('tecidos.salvar-quantidades');
    Route::get('tecidos-importar-estoque', [TecidoController::class, 'importarEstoqueForm'])->name('tecidos.importar-estoque-form');
    Route::post('tecidos-importar-estoque', [TecidoController::class, 'importarEstoque'])->name('tecidos.importar-estoque');
    Route::get('debug-estoque/{referencia}', [TecidoController::class, 'debugEstoque'])->name('tecidos.debug-estoque');

    // Routes para Estilistas
    Route::resource('estilistas', EstilistaController::class);

    // Routes para Marcas
    Route::resource('marcas', MarcaController::class);

    // Routes para Grupo de Produtos
    Route::resource('grupo_produtos', GrupoProdutoController::class);

    // Routes para Localização
    Route::resource('localizacoes', LocalizacaoController::class);

    // Routes para Tipos
    Route::resource('tipos', TipoController::class);
    Route::post('tipos/{tipo}/restore', [TipoController::class, 'restore'])->name('tipos.restore');
    Route::delete('tipos/{tipo}/force-delete', [TipoController::class, 'forceDelete'])->name('tipos.force-delete');

    // Routes para Situações
    Route::resource('situacoes', SituacaoController::class);
    Route::post('situacoes/{situacao}/restore', [SituacaoController::class, 'restore'])->name('situacoes.restore');
    Route::delete('situacoes/{situacao}/force-delete', [SituacaoController::class, 'forceDelete'])->name('situacoes.force-delete');

    // Routes para Status
    Route::resource('status', StatusController::class);

    // Routes para Produtos
    Route::resource('produtos', ProdutoController::class);
    Route::get('produtos/{id}/pdf', [ProdutoController::class, 'generatePdf'])->name('produtos.pdf');
    Route::post('produtos/get-available-colors', [ProdutoController::class, 'getAvailableColors'])->name('produtos.get-available-colors');
    Route::get('produtos-inconsistencias', [ProdutoController::class, 'inconsistencias'])->name('produtos.inconsistencias');
    Route::get('produtos-lista-pdf', [ProdutoController::class, 'generateListPdf'])->name('produtos.lista.pdf');
    
    // Routes para Anexos de Produtos
    Route::post('produtos/{produto}/anexos', [ProdutoAnexoController::class, 'store'])->name('produtos.anexos.store');
    Route::delete('produtos/anexos/{anexo}', [ProdutoAnexoController::class, 'destroy'])->name('produtos.anexos.destroy');

    // Routes para Movimentações
    Route::resource('movimentacoes', MovimentacaoController::class)->parameters([
        'movimentacoes' => 'movimentacao'
    ]);
    Route::delete('movimentacoes/{movimentacao}/remover-anexo', [MovimentacaoController::class, 'removerAnexo'])->name('movimentacoes.remover-anexo');
    Route::get('movimentacoes/{movimentacao}/pdf', [MovimentacaoController::class, 'generatePdf'])->name('movimentacoes.pdf');
    Route::get('movimentacoes-lista-pdf', [MovimentacaoController::class, 'generateListPdf'])->name('movimentacoes.lista.pdf');

    // Routes para Consultas
    Route::get('consultas/produtos-ativos-por-localizacao', [\App\Http\Controllers\ConsultaController::class, 'produtosAtivosPorLocalizacao'])->name('consultas.produtos-ativos-por-localizacao');
    Route::get('consultas/media-dias-atraso', [DashboardController::class, 'mediaDiasAtraso'])->name('consultas.media-dias-atraso');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
