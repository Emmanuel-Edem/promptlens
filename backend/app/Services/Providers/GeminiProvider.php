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

        if (!$apiKey) {
            throw new \Exception('Gemini API key not configured.');
        }

        return [
            'provider' => 'Gemini',
            'status' => 'ready',
            'api_key_loaded' => true,
            'image' => basename($imagePath)
        ];
    }
}
