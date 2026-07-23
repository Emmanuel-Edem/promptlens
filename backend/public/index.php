<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PromptLens\Controllers\UploadController;
use PromptLens\Helpers\Response;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

// Return JSON by default
header('Content-Type: application/json');

// Basic CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$route = $_GET['route'] ?? '';

try {

    switch ($route) {

        case 'upload':
            (new UploadController())->upload();
            break;

        default:
            Response::json([
                'success' => true,
                'application' => 'PromptLens',
                'version' => '1.0.0',
                'status' => 'API Running'
            ]);
    }

} catch (Throwable $e) {

    Response::json([
        'success' => false,
        'error' => $e->getMessage()
    ], 500);

}