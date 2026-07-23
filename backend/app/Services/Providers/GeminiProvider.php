<?php

namespace PromptLens\Services\Providers;

use PromptLens\Services\Config;
use PromptLens\Services\HttpClient;

class GeminiProvider implements AIProviderInterface
{
    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    public function analyze(string $imagePath): array
    {
        $apiKey = Config::get('GEMINI_API_KEY');
        $model = Config::get('GEMINI_MODEL', 'gemini-2.5-flash-lite');

        if (empty($apiKey)) {
            throw new \Exception('Gemini API key not configured.');
        }

        if (!file_exists($imagePath)) {
            throw new \Exception('Image not found.');
        }

        $mime = mime_content_type($imagePath);

        if (!$mime) {
            throw new \Exception('Unable to determine image MIME type.');
        }

        $imageData = base64_encode(file_get_contents($imagePath));

        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $model,
            $apiKey
        );

        $response = $this->http->postJson(
            $url,
            [
                'Content-Type' => 'application/json'
            ],
            [
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ],
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => GeminiPrompt::build()
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $mime,
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            return [
                'provider' => 'Gemini',
                'success' => false,
                'message' => 'Gemini returned an empty response.',
                'raw' => $response
            ];
        }

        $analysis = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'provider' => 'Gemini',
                'success' => false,
                'message' => 'Gemini did not return valid JSON.',
                'raw_text' => $text,
                'raw' => $response
            ];
        }

        return [
            'provider' => 'Gemini',
            'success' => true,
            'analysis' => $analysis,
            'raw' => $response
        ];
    }
}
