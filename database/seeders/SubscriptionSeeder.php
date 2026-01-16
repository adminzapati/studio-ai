<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Feature Modules
        $modules = [
            [
                'name' => 'Products Virtual',
                'slug' => 'products_virtual',
                'description' => 'AI Virtual Try-On and Product Photography',
                'route_name' => 'features.products-virtual.index',
                'icon_path' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Batch Processor',
                'slug' => 'batch',
                'description' => 'Process multiple images at once',
                'route_name' => 'features.batch.index',
                'icon_path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Beautifier',
                'slug' => 'beautifier',
                'description' => 'Enhance image quality and lighting',
                'route_name' => 'features.beautifier.index',
                'icon_path' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Virtual Model',
                'slug' => 'virtual_model',
                'description' => 'Generate realistic virtual models',
                'route_name' => 'features.virtual-model.index',
                'icon_path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Prompts Library',
                'slug' => 'prompts',
                'description' => 'Manage and reuse AI prompts',
                'route_name' => 'storage.prompts.index',
                'icon_path' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Images Library',
                'slug' => 'images',
                'description' => 'Store and organize generated images',
                'route_name' => 'storage.images.index',
                'icon_path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                'is_enabled' => true,
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Activity logs and usage history',
                'route_name' => 'history.index',
                'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'is_enabled' => true,
            ],
            [
                'name' => 'Model Presets',
                'slug' => 'model_presets',
                'description' => 'Manage reuseable model configurations',
                'route_name' => 'storage.model-presets.index',
                'icon_path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'is_enabled' => true,
            ],
        ];

        foreach ($modules as $module) {
            \App\Models\FeatureModule::updateOrCreate(
                ['slug' => $module['slug']],
                $module
            );
        }

        // 2. Create Subscription Plans
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0.00,
                'credits_monthly' => 10,
                'modules' => ['products_virtual', 'prompts', 'images', 'history'],
                'features' => [
                    '10 Credits / Month',
                    'Access to Products Virtual',
                    'Basic Storage',
                    'Standard Support'
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 5.00,
                'credits_monthly' => 100,
                'modules' => ['products_virtual', 'batch', 'prompts', 'images', 'history'],
                'features' => [
                    '100 Credits / Month',
                    'Batch Processor Access',
                    'Standard Storage',
                    'Email Support'
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 15.00,
                'credits_monthly' => 400,
                'modules' => ['products_virtual', 'batch', 'beautifier', 'virtual_model', 'prompts', 'model_presets', 'images', 'history'],
                'features' => [
                    '400 Credits / Month',
                    'All Modules Unlocked',
                    'Enhanced Storage',
                    'Priority Email Support'
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 50.00,
                'credits_monthly' => 2000,
                'modules' => ['products_virtual', 'batch', 'beautifier', 'virtual_model', 'prompts', 'model_presets', 'images', 'history'],
                'features' => [
                    '2000 Credits / Month',
                    'All Modules + Priority',
                    'Unlimited Storage',
                    '24/7 Dedicated Support'
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
