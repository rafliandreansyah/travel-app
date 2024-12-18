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
        Schema::create('car_renteds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('car_id')->nullable()->constrained();
            $table->foreignUuid('transaction_id')->nullable()->constrained();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
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
