<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromptOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // Step 1: Core Info
            ['step' => 1, 'category' => 'Type', 'label' => 'E-Commerce', 'value' => 'professional e-commerce product photography', 'icon' => 'shopping-bag'],
            ['step' => 1, 'category' => 'Type', 'label' => 'Fashion Editorial', 'value' => 'high fashion editorial photography', 'icon' => 'camera'],
            ['step' => 1, 'category' => 'Product Material', 'label' => 'Silk', 'value' => 'made of high quality silk fabric', 'icon' => 'sparkles'],
            ['step' => 1, 'category' => 'Product Material', 'label' => 'Leather', 'value' => 'premium leather texture', 'icon' => 'briefcase'],
            ['step' => 1, 'category' => 'Product Material', 'label' => 'Cotton', 'value' => 'organic cotton texture', 'icon' => 'sun'],

            // Step 2: Presenting
            ['step' => 2, 'category' => 'Style', 'label' => 'Flat Lay', 'value' => 'flat lay composition, organized arrangement', 'icon' => 'layers'],
            ['step' => 2, 'category' => 'Style', 'label' => 'On Model', 'value' => 'worn by a professional model', 'icon' => 'user'],
            ['step' => 2, 'category' => 'Style', 'label' => 'Ghost Mannequin', 'value' => 'invisible ghost mannequin effect', 'icon' => 'user-minus'],
            ['step' => 2, 'category' => 'Background', 'label' => 'Pure White', 'value' => 'on pure white background', 'icon' => 'square'],
            ['step' => 2, 'category' => 'Background', 'label' => 'Lifestyle Urban', 'value' => 'blurred urban city street background', 'icon' => 'building'],
            ['step' => 2, 'category' => 'Background', 'label' => 'Studio Grey', 'value' => 'neutral grey studio background', 'icon' => 'square'],

            // Step 3: Technical
            ['step' => 3, 'category' => 'Lighting', 'label' => 'Soft Studio', 'value' => 'soft studio lighting, diffused light', 'icon' => 'sun'],
            ['step' => 3, 'category' => 'Lighting', 'label' => 'Dramatic', 'value' => 'dramatic high contrast lighting, chiaroscuro', 'icon' => 'moon'],
            ['step' => 3, 'category' => 'Lighting', 'label' => 'Natural', 'value' => 'natural daylight, golden hour', 'icon' => 'cloud'],
            ['step' => 3, 'category' => 'Camera Angle', 'label' => 'Front View', 'value' => 'front view, eye level', 'icon' => 'eye'],
            ['step' => 3, 'category' => 'Camera Angle', 'label' => 'Low Angle', 'value' => 'low angle shot, heroic view', 'icon' => 'arrow-up'],
            ['step' => 3, 'category' => 'Camera Shot', 'label' => 'Full Body', 'value' => 'full body shot, wide shot', 'icon' => 'maximize'],
            ['step' => 3, 'category' => 'Camera Shot', 'label' => 'Detail Macro', 'value' => 'macro close-up detail shot', 'icon' => 'zoom-in'],

            // Step 4: Polish
            ['step' => 4, 'category' => 'Quality', 'label' => '8K Ultra HD', 'value' => '8k, ultra hd, super detailed, masterpiece', 'icon' => 'monitor'],
            ['step' => 4, 'category' => 'Mood', 'label' => 'Minimalist', 'value' => 'minimalist aesthetic, clean lines', 'icon' => 'minus'],
            ['step' => 4, 'category' => 'Mood', 'label' => 'Luxury', 'value' => 'luxury vibe, posh, expensive look', 'icon' => 'diamond'],
        ];

        foreach ($options as $option) {
            \App\Models\PromptOption::create($option);
        }
    }
}
