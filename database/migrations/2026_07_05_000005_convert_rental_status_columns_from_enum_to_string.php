<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE rentals
            MODIFY payment_status VARCHAR(255) NOT NULL DEFAULT 'not_required'
        ");

        DB::statement("
            ALTER TABLE rentals
            MODIFY status VARCHAR(255) NOT NULL DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE rentals
            MODIFY shipping_status VARCHAR(255) NOT NULL DEFAULT 'not_applicable'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE rentals
            MODIFY payment_status ENUM('not_required', 'pending', 'verified', 'rejected') NOT NULL DEFAULT 'not_required'
        ");

        DB::statement("
            ALTER TABLE rentals
            MODIFY status ENUM('pending', 'approved', 'rejected', 'returned', 'completed') NOT NULL DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE rentals
            MODIFY shipping_status ENUM('not_applicable', 'waiting_for_shipping', 'shipped', 'delivered') NOT NULL DEFAULT 'not_applicable'
        ");
    }
};
