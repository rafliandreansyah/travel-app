<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->decimal('total_price', 12, 2);
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_email');
            $table->foreignUuid('car_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('car_company_id')->nullable()->constrained('companies', 'id')->nullOnDelete();
            $table->string('car_name');
            $table->string('car_brand');
            $table->string('car_image_url', 2048);
            $table->string('car_year');
            $table->decimal('car_price_per_day', 8, 2);
            $table->double('car_tax');
            $table->double('car_discount');
            $table->boolean('driver');
            $table->foreignUuid('user_approved_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->string('user_name_approved')->nullable();
            $table->string('user_email_approved')->nullable();
            $table->enum('status_payment', ['waiting_payment', 'waiting_approve', 'reject', 'paid']);
            $table->enum('method_payment', ['transfer', 'cash', 'auto_payment']);
            $table->string('payment_image', 2048)->nullable();
            $table->longText('reason_rejected')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default NOW()
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan auto update
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
