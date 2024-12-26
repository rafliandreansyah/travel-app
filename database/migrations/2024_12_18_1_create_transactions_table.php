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
            $table->foreignUuid('user_id')->constrained()->nullOnDelete();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_email');
            $table->foreignUuid('car_id')->constrained()->nullOnDelete();
            $table->string('car_name');
            $table->string('car_brand');
            $table->string('car_image_url', 2048);
            $table->string('car_year');
            $table->double('car_price_per_day');
            $table->double('car_tax');
            $table->double('car_discount');
            $table->foreignUuid('user_approved_id')->constrained('users', 'id')->nullOnDelete();
            $table->string('user_name_approved')->nullable();
            $table->string('user_email_approved')->nullable();
            $table->enum('status_payment', ['waiting', 'reject', 'paid']);
            $table->enum('method_payment', ['transfer', 'cash', 'auto_payment']);
            $table->string('payment_image', 2048)->nullable();
            $table->longText('reason_rejected')->nullable();
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
