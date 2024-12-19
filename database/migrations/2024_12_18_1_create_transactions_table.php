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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_invoice');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration_day');
            $table->double('total_price');
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('car_id')->constrained();
            $table->string('user_approved')->nullable();
            $table->enum('status', ['waiting_payment', 'down_payment', 'paid']);
            $table->enum('method', ['transfer', 'cash', 'auto_payment']);
            $table->double('down_payment')->nullable();
            $table->string('payment_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
