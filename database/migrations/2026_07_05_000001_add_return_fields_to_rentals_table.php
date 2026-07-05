<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->after('status');
            $table->string('condition_on_return')->nullable()->after('returned_at');
            $table->text('return_notes')->nullable()->after('condition_on_return');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'returned_at',
                'condition_on_return',
                'return_notes',
            ]);
        });
    }
};
