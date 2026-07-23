<?php

namespace PromptLens\Services\Providers;

use PromptLens\Services\Config;
use PromptLens\Services\HttpClient;
use PromptLens\Services\Providers\GeminiPrompt;

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

        if (!$apiKey) {
            throw new \Exception('Gemini API key not configured.');
        }

        if (!file_exists($imagePath)) {
            throw new \Exception('Image not found.');
        }

        $mime = mime_content_type($imagePath);

        $imageData = base64_encode(file_get_contents($imagePath));

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $prompt = GeminiPrompt::build();

        $response = $this->http->postJson(
            $url,
            [
                'Content-Type' => 'application/json'
            ],
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
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

        return [
            'provider' => 'Gemini',
            'response' => $response
        ];
    }
}
