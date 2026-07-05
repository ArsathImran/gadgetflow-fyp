<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('total_amount');
            $table->string('deposit_status')->default('held')->after('deposit_amount');
            $table->decimal('deposit_refund_amount', 10, 2)->nullable()->after('deposit_status');
            $table->text('deposit_deduction_reason')->nullable()->after('deposit_refund_amount');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'deposit_amount',
                'deposit_status',
                'deposit_refund_amount',
                'deposit_deduction_reason',
            ]);
        });
    }
};
