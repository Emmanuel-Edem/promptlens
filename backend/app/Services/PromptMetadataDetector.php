<?php

namespace PromptLens\Services;

class PromptMetadataDetector
{
    public function detect(string $imagePath): array
    {
        $result = [
            'has_embedded_prompt' => false,
            'source' => null,
            'prompt' => null,
            'raw_text' => []
        ];

        // Only PNG files usually contain Stable Diffusion text chunks
        if (strtolower(pathinfo($imagePath, PATHINFO_EXTENSION)) !== 'png') {
            return $result;
        }

        $contents = @file_get_contents($imagePath);

        if (!$contents) {
            return $result;
        }

        $keywords = [
            'parameters',
            'Negative prompt',
            'Steps:',
            'Sampler:',
            'CFG scale:',
            'ComfyUI',
            'Fooocus'
        ];

        foreach ($keywords as $keyword) {
            if (strpos($contents, $keyword) !== false) {
                $result['has_embedded_prompt'] = true;
                $result['source'] = 'Unknown AI Generator';
                break;
            }
        }

        return $result;
    }
}