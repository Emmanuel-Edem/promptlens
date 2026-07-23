<?php

namespace PromptLens\Controllers;

use PromptLens\Services\UploadService;
use PromptLens\Helpers\Response;

class UploadController
{
    private UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    public function upload(): void
    {
        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::json([
                    'success' => false,
                    'message' => 'Only POST requests are allowed.'
                ], 405);
            }

            if (!isset($_FILES['image'])) {
                Response::json([
                    'success' => false,
                    'message' => 'No image uploaded.'
                ], 400);
            }

            $result = $this->uploadService->upload($_FILES['image']);

            Response::json($result);

        } catch (\Throwable $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}
