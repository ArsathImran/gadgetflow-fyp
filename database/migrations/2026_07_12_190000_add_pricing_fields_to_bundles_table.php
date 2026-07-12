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
        Schema::table('bundles', function (Blueprint $table) {
            $table->decimal('daily_rental_price', 10, 2)->nullable()->after('description');
            $table->decimal('hourly_rental_price', 10, 2)->nullable()->after('daily_rental_price');
            $table->decimal('deposit_amount', 10, 2)->nullable()->default(0)->after('hourly_rental_price');
            $table->decimal('late_fee_per_day', 10, 2)->nullable()->default(0)->after('deposit_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn([
                'daily_rental_price',
                'hourly_rental_price',
                'deposit_amount',
                'late_fee_per_day',
            ]);
        });
    }
};
