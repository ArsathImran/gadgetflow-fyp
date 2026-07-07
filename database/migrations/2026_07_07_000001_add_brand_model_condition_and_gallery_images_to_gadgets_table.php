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
        Schema::table('gadgets', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('category_id');
            $table->string('model')->nullable()->after('brand');
            $table->string('condition')->default('good')->after('status');
            $table->json('gallery_images')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'model',
                'condition',
                'gallery_images',
            ]);
        });
    }
};
