<?php

namespace PromptLens\Controllers;

use PromptLens\Services\ImageEngine;
use PromptLens\Helpers\Response;

class UploadController
{
    public function upload(): void
    {
        try {

            $engine = new ImageEngine();

            $result = $engine->analyze($_FILES['image']);

            Response::json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        }
    }
}