<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Exception;
use GuzzleHttp\Exception\RequestException;

// Classe gerada com auxilio do ChatGPT

class OpenAIAPIController extends Controller
{
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:500000',
            ]);

            $file = $request->file('file');

            // Processar o arquivo PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            // Limpar o texto para evitar caracteres estranhos
            $cleanedText = $this->cleanText($text);

            // Limitar o texto para o máximo de 1000 tokens
            $limitedText = $this->limitTextToTokens($cleanedText, 1000);

            // Chamar a API da OpenAI para analisar o texto
            $data = $this->processTextWithAI($limitedText);

            session(['imported_data' => $data]);	// Salvar os dados extraídos na sessão

            return redirect()->back()->with('success', 'Texto analisado com sucesso!');

            // Retornar os dados extraídos como JSON
            // return response()->json($data);
        } catch (RequestException $e) {
            // Capturar e exibir detalhes adicionais do erro
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            return redirect()->back()->with('error', "Erro: $statusCode - $responseBody");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    private function cleanText($text)
    {
        try {
            // Remover ou substituir caracteres estranhos
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            // Remover caracteres de controle, exceto nova linha
            $text = preg_replace('/[^\P{C}\n]+/u', '', $text);
            // Substituir múltiplos espaços por um único espaço
            $text = preg_replace('/\s+/', ' ', $text);
            return $text;
        } catch (Exception $e) {
            throw new Exception("Erro na limpeza do texto: " . $e->getMessage());
        }
    }

    private function limitTextToTokens($text, $maxTokens)
    {
        try {
            $words = explode(' ', $text);
            $currentTokens = 0;
            $limitedText = '';

            foreach ($words as $word) {
                $currentTokens += strlen($word) / 4; // Aproximação: 1 token por 4 caracteres
                if ($currentTokens > $maxTokens) {
                    break;
                }
                $limitedText .= $word . ' ';
            }

            return trim($limitedText);
        } catch (Exception $e) {
            throw new Exception("Erro ao limitar o texto para tokens: " . $e->getMessage());
        }
    }

    private function processTextWithAI($text)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é um assistente que extrai informações específicas de textos.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Texto: $text. O texto apresentado é um edital de concurso público. Extraia para mim os seguintes dados em formato de lista:
                        - Título do edital
                        - Instituição organizadora
                        - Qual a quantidade exata de provas marcadas como Objetivas? Responda apenas um número."
                    ]
                ],
                'max_tokens' => 200, // Limite de tokens na resposta
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $analysis = $result['choices'][0]['message']['content'];

        // Extrair os dados específicos da resposta
        $titulo = '';
        $instituicao = '';
        $quantidade_provas = '';

        if (preg_match('/- Título do edital\s*:\s*(.*)/', $analysis, $matches)) {
            $titulo = $matches[1];
        }
        if (preg_match('/- Instituição organizadora\s*:\s*(.*)/', $analysis, $matches)) {
            $instituicao = $matches[1];
        }
        if (preg_match('/- Quantidade exata de provas marcadas como Objetivas\s*:\s*(\d+)/', $analysis, $matches)) {
            $quantidade_provas = $matches[1];
        }

        return [
            'titulo' => $titulo,
            'instituicao' => $instituicao,
            'quantidade_provas' => $quantidade_provas,
        ];
    }
}
