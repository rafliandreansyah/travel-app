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
            $table->double('tax')->nullable()->default(0.0);
            $table->double('discount')->nullable()->default(0.0);
            $table->longText('description');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->boolean('active')->nullable()->default(true);
            $table->foreignUuid('brand_id')->constrained()->nullOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('cars_image_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image_url');
            $table->foreignUuid('car_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
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
