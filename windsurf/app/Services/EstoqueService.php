<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EstoqueService
{
    protected $apiUrl;
    protected $empresa;
    protected $token;
    protected $armazenador;

    public function __construct()
    {
        $this->apiUrl = config('estoque.api_url');
        $this->empresa = config('estoque.empresa');
        $this->token = config('estoque.token');
        $this->armazenador = config('estoque.armazenador');
        
        // Log das configurações para diagnóstico
        Log::info('EstoqueService inicializado com as seguintes configurações:', [
            'apiUrl' => $this->apiUrl,
            'empresa' => $this->empresa,
            'token' => $this->token ? 'Presente (não exibido por segurança)' : 'Ausente',
            'armazenador' => $this->armazenador
        ]);
    }

    /**
     * Consulta o estoque de um tecido específico pela referência
     *
     * @param string $referencia
     * @return array|null
     */
    public function consultarEstoqueTecido(string $referencia)
    {
        try {
            $response = Http::get($this->apiUrl, [
                'empresa' => $this->empresa,
                'token' => $this->token,
                'armazenador' => $this->armazenador
                // Removido parâmetro referencia, vamos filtrar nos resultados
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Filtrar os resultados pela referência
                $filteredData = array_filter($data, function($item) use ($referencia) {
                    return isset($item['Referencia']) && $item['Referencia'] === $referencia;
                });
                
                return $this->processarResposta($filteredData);
            }

            Log::error('Erro ao consultar estoque de tecido', [
                'referencia' => $referencia,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exceção ao consultar estoque de tecido', [
                'referencia' => $referencia,
                'exception' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Consulta o estoque de todos os tecidos
     *
     * @return array
     */
    public function consultarTodosEstoques()
    {
        try {
            $response = Http::get($this->apiUrl, [
                'empresa' => $this->empresa,
                'token' => $this->token,
                'armazenador' => $this->armazenador
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->processarResposta($data);
            }

            Log::error('Erro ao consultar todos os estoques', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exceção ao consultar todos os estoques', [
                'exception' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Processa a resposta da API
     *
     * @param array $data
     * @return array
     */
    protected function processarResposta($data)
    {
        // Se não for um array, converte para array
        if (!is_array($data)) {
            return [];
        }

        $resultado = [];
        $detalhes = [];
        
        // Processa o formato de resposta da API
        foreach ($data as $item) {
            if (isset($item['Referencia']) && isset($item['Estoque'])) {
                $referencia = $item['Referencia'];
                $estoque = (float) $item['Estoque'];
                $cor = $item['Cor'] ?? 'Não especificada';
                $tamanho = $item['Tamanho'] ?? 'Não especificado';
                
                // Armazena detalhes por cor e tamanho
                if (!isset($detalhes[$referencia])) {
                    $detalhes[$referencia] = [];
                }
                
                if (!isset($detalhes[$referencia][$cor])) {
                    $detalhes[$referencia][$cor] = [];
                }
                
                $detalhes[$referencia][$cor][$tamanho] = $estoque;
                
                // Se a referência já existe, soma o estoque
                if (isset($resultado[$referencia])) {
                    $resultado[$referencia]['quantidade'] += $estoque;
                } else {
                    // Caso contrário, cria uma nova entrada
                    $resultado[$referencia] = [
                        'quantidade' => $estoque,
                        'data_consulta' => now(),
                        'detalhes' => []
                    ];
                }
            }
        }
        
        // Adiciona os detalhes ao resultado final
        foreach ($detalhes as $referencia => $cores) {
            if (isset($resultado[$referencia])) {
                $resultado[$referencia]['detalhes'] = $cores;
            }
        }

        return $resultado;
    }
}
