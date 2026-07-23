<?php

namespace PromptLens\Services;

use PromptLens\Services\Metadata\PngTextExtractor;

class PromptMetadata
{
    protected PngTextExtractor $extractor;
    protected ImageEngine $engine;

    public function __construct()
    {
        $this->extractor = new PngTextExtractor();
        $this->engine = new ImageEngine();
    }

    public function analyze(string $imagePath): array
    {
        $metadata = $this->extractor->extract($imagePath);

        $engine = $this->engine->detect($metadata);

        return [
            'success' => true,
            'engine' => $engine['engine'],
            'confidence' => $engine['confidence'],
            'metadata' => $metadata,
            'metadata_count' => count($metadata)
        ];
    }
}
