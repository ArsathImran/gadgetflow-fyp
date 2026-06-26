<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->decimal('hourly_rental_price', 10, 2)->nullable()->after('daily_rental_price');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('rental_type', ['hour', 'day'])->default('day')->after('gadget_id');
            $table->integer('rental_hours')->nullable()->after('rental_type');
            $table->enum('pickup_type', ['walk_in', 'delivery'])->default('walk_in')->after('rental_hours');
            $table->text('delivery_address')->nullable()->after('pickup_type');
            $table->string('phone_number')->nullable()->after('delivery_address');
            $table->string('ic_number')->nullable()->after('phone_number');
            $table->boolean('agreement_accepted')->default(false)->after('ic_number');
            $table->string('payment_proof')->nullable()->after('agreement_accepted');
            $table->enum('payment_status', ['not_required', 'pending', 'verified', 'rejected'])->default('not_required')->after('payment_proof');
            $table->enum('shipping_status', ['not_applicable', 'waiting_for_shipping', 'shipped', 'delivered'])->default('not_applicable')->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'rental_type',
                'rental_hours',
                'pickup_type',
                'delivery_address',
                'phone_number',
                'ic_number',
                'agreement_accepted',
                'payment_proof',
                'payment_status',
                'shipping_status',
            ]);
        });

        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropColumn('hourly_rental_price');
        });
    }
};
