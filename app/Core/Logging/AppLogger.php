<?php

namespace App\Core\Logging;

use Illuminate\Support\Facades\Log;

/**
 * Application Logger
 * 
 * Centralized logging service for the application.
 * Part of the Core layer in Clean Architecture.
 */
class AppLogger
{
    /**
     * Log an info message
     */
    public static function info(string $message, array $context = []): void
    {
        Log::info("[StudioAI] {$message}", $context);
    }

    /**
     * Log an error message
     */
    public static function error(string $message, array $context = []): void
    {
        Log::error("[StudioAI] {$message}", $context);
    }

    /**
     * Log a debug message
     */
    public static function debug(string $message, array $context = []): void
    {
        Log::debug("[StudioAI] {$message}", $context);
    }

    /**
     * Log AI service calls
     */
    public static function aiCall(string $service, string $action, array $params = []): void
    {
        Log::info("[StudioAI][AI:{$service}] {$action}", $params);
    }
}
