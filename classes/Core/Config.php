<?php

namespace App\Core;

class Config
{

    public static function get(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }

    public static function require(string $key): string
    {
        if (!isset($_ENV[$key])) {
            throw new \RuntimeException("Config '{$key}' is required but not set.");
        }
        return $_ENV[$key];
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = $_ENV[$key] ?? null;
        if ($value === null) return $default;

        return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return isset($_ENV[$key]) ? (int)$_ENV[$key] : $default;
    }
}

