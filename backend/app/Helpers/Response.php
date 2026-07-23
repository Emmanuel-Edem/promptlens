<?php

namespace PromptLens\Helpers;

class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);

        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode(
            $data,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    public static function success(array $data = [], string $message = 'Success'): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error(string $message, int $status = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message
        ], $status);
    }
}