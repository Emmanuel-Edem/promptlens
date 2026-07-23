<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PromptLens\Controllers\UploadController;
use PromptLens\Helpers\Response;

$route = $_GET['route'] ?? '';

switch ($route) {

    case 'upload':

        (new UploadController())->upload();

        break;

    default:

        Response::json([
            'success' => true,
            'message' => 'PromptLens API is running',
            'version' => '1.0.0'
        ]);

}