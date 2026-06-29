<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->json('payment_proofs')->nullable()->after('payment_proof');
            $table->text('payment_note')->nullable()->after('payment_proofs');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proofs',
                'payment_note',
            ]);
        });
    }
};
