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
        Schema::create('car_renteds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('car_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('transaction_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default NOW()
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan auto update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_renteds');
    }
};
