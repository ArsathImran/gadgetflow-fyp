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
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->unique()->after('gadget_id');
            $table->timestamp('handed_over_at')->nullable()->after('returned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn([
                'qr_token',
                'handed_over_at',
            ]);
        });
    }
};
