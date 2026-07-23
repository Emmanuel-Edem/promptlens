<?php

namespace PromptLens\Services;

class UploadService
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = dirname(__DIR__, 2) . '/storage/uploads/';
    }

    public function upload(array $file): array
    {
        if (!isset($file['tmp_name'])) {
            throw new \Exception('No file uploaded.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload failed.');
        }

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $mime = mime_content_type($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            throw new \Exception('Unsupported image type.');
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $filename = uniqid('promptlens_', true) . '.' . $extension;

        $destination = $this->uploadPath . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception('Unable to save uploaded image.');
        }

        [$width, $height] = getimagesize($destination);

        return [
            'filename' => $filename,
            'width' => $width,
            'height' => $height,
            'mime' => $mime
        ];
    }
}