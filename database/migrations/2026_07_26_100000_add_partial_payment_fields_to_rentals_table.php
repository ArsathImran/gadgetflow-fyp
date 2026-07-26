<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('rental_amount_received', 10, 2)->nullable()->after('payment_status');
            $table->decimal('deposit_amount_received', 10, 2)->nullable()->after('rental_amount_received');
            $table->text('payment_shortfall_notes')->nullable()->after('deposit_amount_received');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'rental_amount_received',
                'deposit_amount_received',
                'payment_shortfall_notes',
            ]);
        });
    }
};
