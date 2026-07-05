<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->decimal('late_fee_per_day', 10, 2)->nullable()->default(0)->after('deposit_amount');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('late_fee_amount', 10, 2)->nullable()->default(0)->after('deposit_deduction_reason');
            $table->boolean('late_fee_waived')->default(false)->after('late_fee_amount');
        });
    }

    public function down(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropColumn('late_fee_per_day');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'late_fee_amount',
                'late_fee_waived',
            ]);
        });
    }
};
