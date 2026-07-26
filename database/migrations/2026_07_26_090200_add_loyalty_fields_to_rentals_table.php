<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('points_redeemed')->default(0)->after('total_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('points_redeemed');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['points_redeemed', 'discount_amount']);
        });
    }
};
