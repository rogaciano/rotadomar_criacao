<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatHistorico;
use App\Services\AiQueryService;
use Illuminate\Http\Request;
use Prism\Prism\Facades\Prism;

class AiChatController extends Controller
{
    public function __construct(protected AiQueryService $queryService) {}

    /**
     * Exibe a interface de chat.
     */
    public function index()
    {
        return view('admin.ai-chat');
    }

    /**
     * Processa a mensagem do chat usando o Gemini via Prism.
     * Fluxo: (1) gera SQL se necessário → (2) executa query → (3) responde com dados reais.
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $question = $request->message;

        $systemContext = "Você é o assistente IA do sistema 'Rota do Mar', uma plataforma interna de gestão de produção têxtil/moda. "
                       . "Responda sempre em português do Brasil, de forma clara e objetiva. Você conhece profundamente o sistema e deve ajudar os usuários (administradores, gerentes de produção, estilistas, operadores de setor e motoristas) com dúvidas operacionais, relatórios, boas práticas e orientações de uso.\n\n"

                       . "## Módulos do Sistema\n"
                       . "1. **Produtos**: referencia, descricao, marca, estilista, grupo, status, direcionamento_comercial, quantidade, precos (atacado/varejo), data_cadastro, data_prevista_producao.\n"
                       . "2. **Movimentações**: produto, localização, tipo, situação, data_entrada, data_saida, concluido.\n"
                       . "3. **Planejamento (ProdutoLocalizacao)**: pivot produto↔localização com quantidade, datas de facção, etapa de produção.\n"
                       . "4. **Tecidos**: referencia, descricao, consumo por produto (pivot produto_tecido.consumo), estoque, necessidade total.\n"
                       . "5. **Logística**: coletas com motorista, veículo, origem, destino, status.\n"
                       . "6. **Sugestões**: comunicação interna com status de acompanhamento.\n\n"

                       . "## Cadastros\n"
                       . "Marcas, Estilistas, Grupos, Status (com flag calc_necessidade), Tipos de Movimentação, Situações, Direcionamentos Comerciais, Localizações (setores/facções), Etapas de Produção.\n\n"

                       . "## Orientações\n"
                       . "- Responda com dados reais quando disponíveis.\n"
                       . "- Para dúvidas operacionais: explique passo a passo.\n"
                       . "- Para código/desenvolvimento: use PHP 8.3, Laravel 12, Eloquent.\n"
                       . "- Nunca invente dados ou funcionalidades inexistentes.\n";

        try {
            // Passo 1: tentar gerar SQL para buscar dados reais
            $sql = $this->queryService->generateQuery($question);

            if ($sql) {
                // Passo 2: executar a query com segurança
                $results = $this->queryService->executeQuery($sql);

                // Passo 3: formatar resposta com os dados reais
                $reply = $this->queryService->formatAnswer($question, $results, $systemContext);
            } else {
                // Pergunta conceitual/operacional — resposta direta
                $finalPrompt = $systemContext . "\n\nPergunta do usuário:\n" . $question;

                $resposta = Prism::text()
                    ->using('gemini', 'gemini-2.5-flash')
                    ->withPrompt($finalPrompt)
                    ->generate();

                $reply = $resposta->text;
            }

            // Salvar interação no histórico
            $historico = AiChatHistorico::create([
                'user_id'    => auth()->id(),
                'pergunta'   => $question,
                'sql_gerado' => $sql ?? null,
                'resposta'   => $reply,
                'util'       => null,
            ]);

            return response()->json([
                'success'      => true,
                'reply'        => $reply,
                'historico_id' => $historico->id,
            ]);

        } catch (\Exception $e) {
            $msg = $e->getMessage();

            // Traduzir erros conhecidos para português
            if (str_contains($msg, 'rate limit')) {
                $erro = 'O serviço de IA está temporariamente sobrecarregado. Aguarde alguns segundos e tente novamente.';
            } elseif (str_contains($msg, 'Could not resolve host') || str_contains($msg, 'Connection refused')) {
                $erro = 'Não foi possível conectar ao serviço de IA. Verifique a conexão com a internet.';
            } elseif (str_contains($msg, 'API key') || str_contains($msg, 'authentication') || str_contains($msg, 'Unauthenticated')) {
                $erro = 'Chave de API inválida ou não configurada. Contacte o administrador do sistema.';
            } elseif (str_contains($msg, 'SQLSTATE')) {
                $erro = 'Erro ao consultar o banco de dados. A consulta gerada pela IA pode estar incorreta. Tente reformular sua pergunta.';
            } elseif (str_contains($msg, 'Apenas queries SELECT')) {
                $erro = 'Por segurança, apenas consultas de leitura são permitidas.';
            } elseif (str_contains($msg, 'Operação não permitida')) {
                $erro = 'A consulta gerada contém operações não permitidas. Tente reformular sua pergunta.';
            } else {
                $erro = 'Ocorreu um erro inesperado. Tente novamente em alguns instantes.';
            }

            \Log::error('AiChat erro: ' . $msg);

            return response()->json([
                'success' => false,
                'error'   => $erro,
            ], 500);
        }
    }

    /**
     * Registra se a resposta foi útil ou não.
     */
    public function feedback(Request $request, AiChatHistorico $historico)
    {
        $request->validate(['util' => 'required|boolean']);

        if ($historico->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        $historico->update(['util' => $request->util]);

        return response()->json(['success' => true]);
    }
}
