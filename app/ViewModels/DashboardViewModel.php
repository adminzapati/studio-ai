<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;

/**
 * Dashboard ViewModel
 * 
 * Prepares data for the dashboard view.
 * Part of the Presentation layer in Clean Architecture.
 */
class DashboardViewModel
{
    public function __construct(
        public readonly int $totalPrompts,
        public readonly int $totalImages,
        public readonly int $totalUsers,
        public readonly Collection $recentPrompts,
        public readonly array $systemStatus,
    ) {}

    /**
     * Create ViewModel from raw data
     */
    public static function create(
        int $totalPrompts,
        int $totalImages,
        int $totalUsers,
        Collection $recentPrompts
    ): self {
        return new self(
            totalPrompts: $totalPrompts,
            totalImages: $totalImages,
            totalUsers: $totalUsers,
            recentPrompts: $recentPrompts,
            systemStatus: self::getSystemStatus(),
        );
    }

    /**
     * Get system status for AI services
     */
    private static function getSystemStatus(): array
    {
        return [
            'gemini' => [
                'name' => 'Gemini AI',
                'status' => 'operational',
            ],
            'fal' => [
                'name' => 'Fal.ai Flux',
                'status' => 'operational',
            ],
            'storage' => [
                'name' => 'Storage',
                'status' => 'healthy',
            ],
        ];
    }
}
