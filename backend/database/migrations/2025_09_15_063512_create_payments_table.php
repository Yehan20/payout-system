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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->decimal('amount_usd', 15, 2)->nullable(); // after conversion
            $table->string('reference_no')->unique();
            $table->dateTime('date_time');
            $table->boolean('processed')->default(false); // for Part 2
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
