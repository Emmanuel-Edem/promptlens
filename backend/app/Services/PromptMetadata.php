<?php

namespace PromptLens\Services;

use PromptLens\Services\Metadata\PngTextExtractor;

class PromptMetadata
{
    protected PngTextExtractor $extractor;
    protected ImageEngine $engine;
    protected PromptParser $parser;

    public function __construct()
    {
        $this->extractor = new PngTextExtractor();
        $this->engine = new ImageEngine();
        $this->parser = new PromptParser();
    }

    public function analyze(string $imagePath): array
    {
        $metadata = $this->extractor->extract($imagePath);

        $engine = $this->engine->detect($metadata);

        $parsed = $this->parser->parse($metadata);

        return [
            'success' => true,

            'engine' => [
                'name' => $engine['engine'],
                'confidence' => $engine['confidence']
            ],

            'prompt' => $parsed['prompt'],

            'negative_prompt' => $parsed['negative_prompt'],

            'parameters' => $parsed['parameters'],

            'metadata_count' => count($metadata),

            'metadata' => $metadata
        ];
    }
}
