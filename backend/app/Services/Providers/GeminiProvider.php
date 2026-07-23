<?php

namespace PromptLens\Services\Providers;

class GeminiProvider implements AIProviderInterface
{
    public function analyze(string $imagePath): array
    {
        // Gemini API integration will be implemented next.

        return [
            'provider' => 'Gemini',
            'status' => 'Not yet implemented',
            'image' => basename($imagePath)
        ];
    }
}
