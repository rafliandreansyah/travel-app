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

        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('image_url');
            $table->integer('year');
            $table->integer('capacity');
            $table->integer('luggage')->nullable();
            $table->integer('cc');
            $table->double('price_per_day');
            $table->double('tax', 0.0);
            $table->double('discount', 0.0);
            $table->longText('description');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->boolean('active', true);
            $table->foreignUuid('brand_id')->constrained();
            $table->timestamps();
        });

        Schema::create('cars_image_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image_url');
            $table->foreignUuid('car_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
