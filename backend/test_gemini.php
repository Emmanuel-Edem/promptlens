<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use PromptLens\Services\Config;
use PromptLens\Services\Providers\GeminiProvider;

Config::load();

$image = __DIR__ . '/storage/uploads/test.png';

header('Content-Type: application/json');

try {

    $provider = new GeminiProvider();

    echo json_encode(
        $provider->analyze($image),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);

}
