<?php

namespace PromptLens\Services;

use Dotenv\Dotenv;

class Config
{
    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        $root = dirname(__DIR__, 2);

        if (file_exists($root . '/.env')) {
            Dotenv::createImmutable($root)->safeLoad();
        }

        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}
