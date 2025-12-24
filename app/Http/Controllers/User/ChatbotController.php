<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Chatbot/Index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'array',
        ]);

        // Construir mensajes para Groq
        $messages = [];
        
        // Mensaje del sistema (prompt fijo)
        $messages[] = [
            'role' => 'system',
            'content' => $this->getSystemPrompt()
        ];
        
        // Agregar historial previo
        if (!empty($request->history)) {
            foreach ($request->history as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content']
                ];
            }
        }

        // Agregar mensaje actual
        $messages[] = [
            'role' => 'user',
            'content' => $request->message
        ];

        try {
            // Llamar a Groq API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer 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',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1024,
                    'top_p' => 0.9,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['choices'][0]['message']['content'])) {
                    $respuesta = trim($data['choices'][0]['message']['content']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => $respuesta,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Respuesta inesperada del asistente.',
                    ], 500);
                }
            } else {
                $statusCode = $response->status();
                $errorMsg = 'Error al comunicarse con el asistente.';
                
                if ($statusCode === 401) {
                    $errorMsg = 'API Key inválida.';
                } else if ($statusCode === 429) {
                    $errorMsg = 'Límite de peticiones alcanzado. Intenta en un minuto.';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getSystemPrompt()
    {
        return "Eres un asistente técnico experto en mantenimiento de sistemas diesel, especializado en inyectores y bombas. Trabajas para Energy Control, un sistema de gestión de mantenimiento diesel.

TUS CAPACIDADES:
1. Explicar procedimientos de mantenimiento de inyectores y bombas diesel
2. Diagnosticar problemas comunes basado en síntomas descritos
3. Recomendar acciones preventivas y correctivas
4. Proporcionar información técnica sobre sistemas diesel
5. Explicar conceptos de calibración, presión, caudal, etc.

INSTRUCCIONES:
- Responde SIEMPRE en español
- Sé técnico pero claro y accesible
- Divide procedimientos complejos en pasos numerados
- Mantén respuestas concisas (200-400 palabras máximo)
- Si no sabes algo, admítelo honestamente
- Usa ejemplos prácticos cuando sea útil
- Enfócate en mantenimiento diesel: inyectores Common Rail, bombas de alta presión, etc.

EJEMPLOS DE PREGUNTAS QUE PUEDES RESPONDER:
- ¿Cómo calibrar un inyector Common Rail?
- ¿Qué presión debe tener una bomba diesel?
- ¿Cuáles son los síntomas de un inyector dañado?
- ¿Cada cuánto hacer mantenimiento preventivo?
- ¿Cómo limpiar un inyector sin dañarlo?

LÍMITES:
- No tienes acceso a información específica del sistema del usuario
- No puedes realizar acciones o consultas en bases de datos
- No des consejos médicos o legales
- Enfócate solo en temas técnicos de diesel";
    }
}