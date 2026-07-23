<?php

namespace PromptLens\Services\Providers;

interface AIProviderInterface
{
    /**
     * Analyze an image and return structured information.
     */
    public function analyze(string $imagePath): array;
}
