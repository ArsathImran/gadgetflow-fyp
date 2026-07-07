<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->timestamp('payment_collected_at')->nullable()->after('payment_note');
            $table->foreignId('payment_collected_by')->nullable()->after('payment_collected_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_collected_by');
            $table->dropColumn('payment_collected_at');
        });
    }
};
