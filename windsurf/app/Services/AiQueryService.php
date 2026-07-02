<?php

namespace App\Services;

use App\Models\AiChatHistorico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Prism;

class AiQueryService
{
    /**
     * Lista de provedores de IA em ordem de prioridade.
     * O sistema tenta o primeiro; se falhar, vai para o próximo.
     */
    protected array $providers = [
        ['provider' => 'gemini',   'model' => 'gemini-2.5-flash',           'env_key' => 'GEMINI_API_KEY'],
        ['provider' => 'groq',     'model' => 'llama-3.3-70b-versatile',    'env_key' => 'GROQ_API_KEY'],
        ['provider' => 'deepseek', 'model' => 'deepseek-chat',              'env_key' => 'DEEPSEEK_API_KEY'],
        ['provider' => 'openai',   'model' => 'gpt-4o-mini',               'env_key' => 'OPENAI_API_KEY'],
    ];

    /**
     * Chama a IA com fallback automático entre provedores.
     * Tenta cada provedor configurado em ordem até obter resposta.
     */
    protected function callAI(string $prompt): string
    {
        $errors = [];

        foreach ($this->providers as $config) {
            // Pular provedores sem chave configurada
            if (empty(env($config['env_key']))) {
                continue;
            }

            try {
                $resposta = Prism::text()
                    ->using($config['provider'], $config['model'])
                    ->withPrompt($prompt)
                    ->generate();

                return $resposta->text;
            } catch (\Exception $e) {
                $errors[] = "{$config['provider']}: {$e->getMessage()}";
                Log::warning("AiChat fallback: {$config['provider']}/{$config['model']} falhou", [
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }

        throw new \RuntimeException(
            'Todos os provedores de IA falharam. Erros: ' . implode(' | ', $errors)
        );
    }
    /**
     * Schema do banco de dados para contexto da IA.
     */
    public function getSchemaContext(): string
    {
        return "## Schema do Banco de Dados (MySQL)\n\n"
            . "### produtos\n"
            . "id (INT PK), "
            . "referencia (VARCHAR - código identificador do produto, ex: '900080', NÃO é quantidade), "
            . "descricao (VARCHAR - nome do produto), "
            . "marca_id (FK marcas), estilista_id (FK estilistas), grupo_id (FK grupos), "
            . "status_id (FK status), direcionamento_comercial_id (FK direcionamentos_comerciais), "
            . "localizacao_id (FK localizacoes), "
            . "quantidade (INT - quantidade de peças a produzir), "
            . "preco_atacado (DECIMAL), preco_varejo (DECIMAL), "
            . "data_cadastro (DATE), data_prevista_producao (DATE), "
            . "produto_original_id (INT - se reprogramado), numero_reprogramacao (INT), deleted_at\n\n"

            . "### tecidos\n"
            . "id, referencia, descricao, quantidade_estoque, ativo (0/1), ultima_consulta_estoque, deleted_at\n\n"

            . "### produto_tecido (pivot produtos↔tecidos)\n"
            . "produto_id, tecido_id, consumo (decimal - metros por unidade)\n\n"

            . "### movimentacoes\n"
            . "id, produto_id, localizacao_id, tipo_id, situacao_id, data_entrada, data_saida, data_devolucao, "
            . "comprometido, concluido (0/1), observacao, deleted_at\n\n"

            . "### produto_localizacao (planejamento/produção)\n"
            . "id, produto_id, localizacao_id, quantidade, etapa_id, etapa_anterior_id, "
            . "data_prevista, data_envio_faccao, data_retorno_faccao, data_entrega, ordem_producao\n\n"

            . "### produto_localizacao_historico_etapas\n"
            . "id, produto_localizacao_id, etapa_id, user_id, observacao, created_at\n\n"

            . "### marcas\n"
            . "id, nome_marca (VARCHAR - nome da marca), logo_path, cor_fundo, deleted_at\n\n"

            . "### estilistas\n"
            . "id, nome_estilista (VARCHAR - nome do estilista), ativo (0/1), marca_id (FK marcas), deleted_at\n\n"

            . "### grupos\n"
            . "id, descricao (VARCHAR - nome do grupo), ativo (0/1), deleted_at\n\n"

            . "### status\n"
            . "id, descricao (VARCHAR - nome do status), ativo (0/1), calc_necessidade (0/1), deleted_at\n\n"

            . "### tipos (tipos de movimentação)\n"
            . "id, descricao (VARCHAR - nome do tipo), ativo (0/1), deleted_at\n\n"

            . "### situacoes (situações de movimentação)\n"
            . "id, descricao (VARCHAR - nome da situação), ativo (0/1), deleted_at\n\n"

            . "### direcionamentos_comerciais\n"
            . "id, descricao (VARCHAR - nome do direcionamento), ativo (0/1), deleted_at\n\n"

            . "### localizacoes\n"
            . "id, nome_localizacao (VARCHAR - nome completo), nome_reduzido (VARCHAR), prazo (INT dias), "
            . "capacidade (INT), faz_movimentacao (0/1), ativo (0/1), deleted_at\n\n"

            . "### etapas_producao\n"
            . "id, nome (VARCHAR - nome da etapa), slug (VARCHAR), cor, icone, ordem, localizacao_id, deleted_at\n\n"

            . "### sugestoes\n"
            . "id, user_id, localizacao_id, assunto (VARCHAR), mensagem (TEXT), "
            . "status (nao_lida/lida/em_analise/aceito/negado), created_at\n\n"

            . "### coletas_logisticas\n"
            . "id, produto_localizacao_id, motorista_user_id, veiculo_id, localizacao_origem_id, localizacao_destino_id, "
            . "status (agendado/em_transito/finalizado/cancelado), data_agendamento, data_saida, data_chegada, created_at\n\n"

            . "### users\n"
            . "id, name (VARCHAR - nome do usuário), email, is_admin (0/1), localizacao_id\n\n"

            . "### tecido_cores\n"
            . "id, tecido_id, nome (VARCHAR - nome da cor), codigo (VARCHAR), deleted_at\n\n"

            . "### produto_cor\n"
            . "id, produto_id, cor (VARCHAR), codigo_cor (VARCHAR), deleted_at\n\n"

            . "### produto_redistribuicao_historico\n"
            . "id, produto_id, localizacao_origem_id, localizacao_destino_id, quantidade, user_id, created_at\n\n"

            . "**IMPORTANTE**: Todas as tabelas com deleted_at usam Soft Delete. Sempre adicione `WHERE tabela.deleted_at IS NULL` para registros ativos.\n";
    }

    /**
     * Passo 1: Pede à IA para gerar uma query SQL SELECT baseada na pergunta.
     * Retorna null se a pergunta não precisar de dados do banco.
     */
    public function generateQuery(string $question): ?string
    {
        // Carregar exemplos do histórico para few-shot learning
        $exemplosPositivos = AiChatHistorico::exemplosPosistivos(4);
        $exemplosNegativos = AiChatHistorico::exemplosNegativos(2);

        $fewShot = '';
        if ($exemplosPositivos->isNotEmpty()) {
            $fewShot .= "\n## Exemplos de Queries Bem-sucedidas (USE como referência)\n";
            foreach ($exemplosPositivos as $ex) {
                $fewShot .= "Pergunta: {$ex->pergunta}\nSQL: {$ex->sql_gerado}\n\n";
            }
        }
        if ($exemplosNegativos->isNotEmpty()) {
            $fewShot .= "\n## Exemplos de Queries com Problema (EVITE esses padrões)\n";
            foreach ($exemplosNegativos as $ex) {
                $fewShot .= "Pergunta: {$ex->pergunta}\nSQL com problema: {$ex->sql_gerado}\n\n";
            }
        }

        $prompt = "Você é um gerador de SQL para um sistema de gestão têxtil/moda chamado 'Rota do Mar'.\n\n"
            . $this->getSchemaContext()
            . $fewShot
            . "\n## Instrução\n"
            . "Analise a pergunta do usuário abaixo e decida:\n"
            . "- Se a pergunta precisa de dados do banco: responda APENAS com o SQL SELECT (sem explicação, sem markdown, sem ```).\n"
            . "- Se NÃO precisa de dados do banco (é uma dúvida conceitual, de uso do sistema, de código, etc.): responda exatamente com a palavra: NO_QUERY\n\n"
            . "Regras para o SQL:\n"
            . "- Use apenas SELECT (NUNCA INSERT, UPDATE, DELETE, DROP, TRUNCATE, etc.)\n"
            . "- Sempre filtre deleted_at IS NULL nas tabelas que possuem esse campo\n"
            . "- Limite os resultados a no máximo 50 linhas com LIMIT 50\n"
            . "- Use aliases legíveis (ex: t.descricao AS tecido, COUNT(*) AS total_produtos)\n"
            . "- Para 'mais usado', 'mais frequente', 'maior quantidade': use COUNT, SUM ou GROUP BY com ORDER BY DESC\n"
            . "- ATENÇÃO: na tabela produtos, 'referencia' é um código VARCHAR (ex: '900080'), NÃO é a quantidade. "
            . "  A quantidade de peças a produzir está em 'quantidade' (INT). NUNCA confunda os dois campos.\n"
            . "- Para tecido mais utilizado: use COUNT(pt.produto_id) ou SUM(p.quantidade * pt.consumo) agrupado por t.descricao\n\n"
            . "Pergunta: " . $question;

        $sql = trim($this->callAI($prompt));

        // Se a IA disse que não precisa de query
        if (strtoupper($sql) === 'NO_QUERY' || str_contains(strtoupper($sql), 'NO_QUERY')) {
            return null;
        }

        // Limpar blocos markdown que a IA possa ter incluído
        $sql = preg_replace('/```sql\s*/i', '', $sql);
        $sql = preg_replace('/```\s*/i', '', $sql);
        $sql = trim($sql);

        return $sql;
    }

    /**
     * Passo 2: Executa a query de forma segura (somente SELECT).
     * Lança exceção se não for um SELECT válido.
     */
    public function executeQuery(string $sql): array
    {
        $sqlUpper = strtoupper(trim($sql));

        // Validação de segurança: apenas SELECT permitido
        if (!str_starts_with($sqlUpper, 'SELECT')) {
            throw new \RuntimeException('Apenas queries SELECT são permitidas.');
        }

        // Bloquear palavras perigosas (word boundary para evitar falsos positivos como deleted_at)
        $forbidden = ['INSERT', 'UPDATE', 'DELETE', 'DROP', 'TRUNCATE', 'ALTER', 'CREATE', 'EXEC', 'EXECUTE', 'CALL'];
        foreach ($forbidden as $word) {
            if (preg_match('/\b' . $word . '\b/', $sqlUpper)) {
                throw new \RuntimeException("Operação não permitida: {$word}");
            }
        }

        // Garantir LIMIT para evitar queries pesadas
        if (!str_contains($sqlUpper, 'LIMIT')) {
            $sql = rtrim($sql, ';') . ' LIMIT 50';
        }

        $results = DB::select($sql);

        return array_map(fn($row) => (array) $row, $results);
    }

    /**
     * Passo 3: Formata a resposta final com os dados do banco.
     */
    public function formatAnswer(string $question, array $results, string $systemContext): string
    {
        $resultsJson = count($results) > 0
            ? json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : '(nenhum registro encontrado)';

        $prompt = $systemContext
            . "\n\n## Dados Reais do Banco de Dados\n"
            . "A consulta retornou os seguintes dados:\n```\n{$resultsJson}\n```\n\n"
            . "## Instrução\n"
            . "Com base nos dados acima, responda de forma clara e objetiva à pergunta do usuário em português do Brasil. "
            . "Apresente os dados de forma organizada (use listas, negrito, tabelas textuais se útil). "
            . "Se os dados estiverem vazios, informe que não foram encontrados registros.\n\n"
            . "Pergunta: " . $question;

        return $this->callAI($prompt);
    }

    /**
     * Chamada direta à IA (perguntas conceituais sem SQL).
     */
    public function askDirect(string $prompt): string
    {
        return $this->callAI($prompt);
    }
}
