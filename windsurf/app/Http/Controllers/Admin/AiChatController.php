<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prism\Prism\Facades\Prism;

class AiChatController extends Controller
{
    /**
     * Exibe a interface de chat.
     */
    public function index()
    {
        return view('admin.ai-chat');
    }

    /**
     * Processa a mensagem do chat usando o Gemini via Prism.
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            // Criamos um contexto inicial para o Gemini parar de ser "genérico"
            $systemContext = "Instrução de Sistema: Você é um assistente técnico especialista em desenvolvimento web, com foco total na stack do projeto 'Rota do Mar'. "
                           . "A stack do projeto é: PHP 8.3, Laravel 12, Blade Templates, Alpine.js (para interatividade no frontend), Tailwind CSS (para estilização), Vite (para bundling e compilação de assets), e MySQL como banco de dados. "
                           . "O projeto usa Spatie ActivityLog para auditoria, Laravel Sanctum para autenticação de API, e o pacote Prism para integração com IA (Gemini). "
                           . "Você deve ajudar o administrador/desenvolvedor com dúvidas, boas práticas, snippets de código, debugging e otimizações específicas para essa stack. "
                           . "As suas respostas devem ser sempre em português do Brasil, técnicas, precisas e com exemplos de código quando necessário. "
                           . "Quando fornecer código, use blocos formatados. Prefira soluções idiomáticas do Laravel/Alpine/Tailwind. \n\n"
                           . "Pergunta do desenvolvedor:\n";

            $finalPrompt = $systemContext . $request->message;

            $resposta = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($finalPrompt)
                ->generate();

            // Converter quebras de linha Markdown ou simples em br ou deixar que o frontend lide.
            // O frontend vai usar Tailwind `whitespace-pre-wrap` para manter as quebras originais.

            return response()->json([
                'success' => true,
                'reply' => $resposta->text
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao comunicar com a inteligência artificial do sistema. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }
}
