<?php

namespace PromptLens\Services;

class ImageEngine
{
    private UploadService $uploadService;
    private MetadataExtractor $metadataExtractor;
    private PromptMetadataDetector $promptDetector;

    public function __construct()
    {
        $this->uploadService = new UploadService();
        $this->metadataExtractor = new MetadataExtractor();
        $this->promptDetector = new PromptMetadataDetector();
    }

    public function analyze(array $file): array
    {
        // Upload image
        $upload = $this->uploadService->upload($file);

        // Full path
        $path = dirname(__DIR__, 2) . '/storage/uploads/' . $upload['filename'];

        // Metadata
        $metadata = $this->metadataExtractor->extract($path);

        // Embedded prompt detection
        $prompt = $this->promptDetector->detect($path);

        return [
            'upload' => $upload,
            'metadata' => $metadata,
            'prompt_detection' => $prompt
        ];
    }
}