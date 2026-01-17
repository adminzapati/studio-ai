<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products_virtual_jobs', function (Blueprint $table) {
            $table->string('model_image_path', 500)->nullable()->after('model_image_fal_url');
            $table->json('product_images_paths')->nullable()->after('product_images_fal_urls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_virtual_jobs', function (Blueprint $table) {
            $table->dropColumn(['model_image_path', 'product_images_paths']);
        });
    }
};
