<?php

namespace App\Jobs;

use App\Models\Tecido;
use App\Models\TecidoCorEstoque;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncEstoquesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function handle(): void
    {
        Log::info('SyncEstoquesJob: Iniciando sincronização de estoques', ['user_id' => $this->userId]);

        $tecidos = Tecido::whereNotNull('referencia')->get();

        if ($tecidos->isEmpty()) {
            Log::info('SyncEstoquesJob: Nenhum tecido com referência encontrado.');
            return;
        }

        $response = Http::timeout(120)->get(config('estoque.api_url'), [
            'empresa' => config('estoque.empresa'),
            'token' => config('estoque.token'),
            'armazenador' => config('estoque.armazenador'),
        ]);

        if (!$response->successful()) {
            Log::error('SyncEstoquesJob: Falha ao obter dados da API de estoque', [
                'status' => $response->status(),
            ]);
            return;
        }

        $todosEstoques = $response->json();
        $atualizados = 0;
        $dataConsulta = now();

        foreach ($tecidos as $tecido) {
            if (empty($tecido->referencia)) {
                continue;
            }

            $estoqueDoTecido = array_filter($todosEstoques, function ($item) use ($tecido) {
                return isset($item['Referencia']) && $item['Referencia'] === $tecido->referencia;
            });

            if (!empty($estoqueDoTecido)) {
                $quantidadeTotal = 0;
                $estoquePorCor = [];

                foreach ($estoqueDoTecido as $item) {
                    if (isset($item['Estoque'])) {
                        $quantidade = (float) $item['Estoque'];
                        $quantidadeTotal += $quantidade;

                        $cor = $item['Cor'] ?? 'Não especificada';
                        $codigoCor = $item['CodigoCor'] ?? null;

                        if (!isset($estoquePorCor[$cor])) {
                            $estoquePorCor[$cor] = [
                                'quantidade' => 0,
                                'codigo_cor' => $codigoCor,
                            ];
                        }

                        $estoquePorCor[$cor]['quantidade'] += $quantidade;
                    }
                }

                $tecido->update([
                    'quantidade_estoque' => $quantidadeTotal,
                    'ultima_consulta_estoque' => $dataConsulta,
                ]);

                TecidoCorEstoque::where('tecido_id', $tecido->id)->delete();

                foreach ($estoquePorCor as $cor => $dados) {
                    TecidoCorEstoque::create([
                        'tecido_id' => $tecido->id,
                        'cor' => $cor,
                        'codigo_cor' => $dados['codigo_cor'],
                        'quantidade' => $dados['quantidade'],
                        'data_atualizacao' => $dataConsulta,
                    ]);
                }

                $atualizados++;
            }
        }

        Log::info("SyncEstoquesJob: Estoque atualizado para {$atualizados} tecidos.", [
            'user_id' => $this->userId,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SyncEstoquesJob: Falha na sincronização de estoques', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
        ]);
    }
}
