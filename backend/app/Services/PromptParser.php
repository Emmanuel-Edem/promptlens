<?php

namespace PromptLens\Services;

class PromptParser
{
    public function parse(array $metadata): array
    {
        $text = implode("\n", array_values($metadata));

        return [
            'prompt' => $this->extractPrompt($text),
            'negative_prompt' => $this->extractNegativePrompt($text),
            'parameters' => $this->extractParameters($text)
        ];
    }

    private function extractPrompt(string $text): ?string
    {
        if (preg_match('/^(.*?)Negative prompt:/is', $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }

    private function extractNegativePrompt(string $text): ?string
    {
        if (preg_match('/Negative prompt:(.*?)Steps:/is', $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }

    private function extractParameters(string $text): array
    {
        $params = [];

        preg_match_all('/([A-Za-z ]+):\s*([^,\n]+)/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $params[trim($match[1])] = trim($match[2]);
        }

        return $params;
    }
}
