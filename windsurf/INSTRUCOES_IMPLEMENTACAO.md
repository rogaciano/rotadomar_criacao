# Instruções para Implementação do Filtro por Status de Dias

Foram criadas várias alternativas para implementar o filtro por status de dias (Todos, Atrasados, Em Dia) nas movimentações. Escolha a opção que melhor se adapta às suas necessidades:

## Opção 1: Editar diretamente o MovimentacaoController

1. Abra o arquivo `app/Http/Controllers/MovimentacaoController.php`
2. Adicione o seguinte import no topo do arquivo:
   ```php
   use App\Http\Controllers\MovimentacaoFilterController;
   ```

3. Localize o trecho de código no método `index` (aproximadamente linha 130):
   ```php
   // Adicionar filtro para o campo concluido
   if ($request->filled('concluido')) {
       $query->where('concluido', $request->concluido);
   }

   // Ordenação
   ```

4. Adicione o seguinte código entre o filtro de concluido e a ordenação:
   ```php
   // Filtro por status de dias (Atrasados, Em Dia)
   if ($request->filled('status_dias')) {
       $query = MovimentacaoFilterController::applyStatusDiasFilter($query, $request->status_dias);
   }
   ```

5. Repita o mesmo processo no método `generateListPdf` (aproximadamente linha 488).

## Opção 2: Usar o MovimentacaoFiltroController (Nova Rota)

1. Adicione o arquivo de rotas ao seu aplicativo:
   - Abra o arquivo `app/Providers/RouteServiceProvider.php`
   - No método `boot()`, adicione:
     ```php
     Route::middleware('web')
          ->group(base_path('routes/movimentacao_filtro.php'));
     ```

2. Atualize os links na view para usar a nova rota:
   - Abra o arquivo `resources/views/layouts/navigation.blade.php` (ou onde estiver o link para movimentações)
   - Altere o link para movimentações para usar a nova rota:
     ```php
     <x-nav-link :href="route('movimentacoes.filtro')" :active="request()->routeIs('movimentacoes.filtro')">
         {{ __('Movimentações') }}
     </x-nav-link>
     ```

3. Atualize o formulário de filtro na view:
   - Abra o arquivo `resources/views/movimentacoes/index.blade.php`
   - Altere a action do formulário:
     ```php
     <form action="{{ route('movimentacoes.filtro') }}" method="GET" id="filters-form" autocomplete="off" class="grid grid-cols-1 md:grid-cols-4 gap-4">
     ```

## Opção 3: Usar o SQL Diretamente

1. Execute o script SQL `filtro_status_dias.sql` no seu banco de dados.
2. Use a view `vw_movimentacoes_status_dias` ou a stored procedure `sp_filtrar_movimentacoes_por_status_dias` para consultar os dados.

## Opção 4: Usar o MovimentacaoHelper

1. Crie um novo método no MovimentacaoController que usa o MovimentacaoHelper:
   ```php
   public function filtrarPorStatusDias(Request $request)
   {
       // Verificar permissões
       if (!auth()->user() || !auth()->user()->canRead('movimentacoes')) {
           abort(403, 'Acesso negado.');
       }
       
       // Obter a query com os filtros aplicados
       $query = \App\Helpers\MovimentacaoHelper::getMovimentacoesComFiltro($request->all());
       
       // Paginar os resultados
       $movimentacoes = $query->paginate(15)->withQueryString();
       
       // Carregar os mesmos dados para os selects que o método index carrega
       // ...
       
       return view('movimentacoes.index', compact('movimentacoes', 'produtos', 'situacoes', 'tipos', 'localizacoes', 'status', 'marcas', 'tecidos'));
   }
   ```

2. Adicione uma rota para este novo método:
   ```php
   Route::get('/movimentacoes/filtrar', [MovimentacaoController::class, 'filtrarPorStatusDias'])->name('movimentacoes.filtrar');
   ```

## Testando a Implementação

1. Certifique-se de que o campo de filtro está presente na view.
2. Selecione uma opção no filtro de Status de Dias (Atrasados ou Em Dia).
3. Clique no botão "Filtrar".
4. Verifique se os resultados estão sendo filtrados corretamente.

## Solução de Problemas

Se você encontrar problemas com a implementação:

1. Verifique os logs de erro em `storage/logs/laravel.log`.
2. Certifique-se de que todos os arquivos necessários foram criados e estão no local correto.
3. Limpe o cache da aplicação:
   ```
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```
4. Reinicie o servidor de desenvolvimento:
   ```
   php artisan serve
   ```
