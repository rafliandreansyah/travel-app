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
            $table->decimal('price_per_day');
            $table->double('tax')->nullable()->default(0.0);
            $table->double('discount')->nullable()->default(0.0);
            $table->longText('description');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->boolean('active')->nullable()->default(true);
            $table->foreignUuid('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default NOW()
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan auto update
        });

        Schema::create('cars_image_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image_url');
            $table->foreignUuid('car_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default NOW()
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan auto update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars_image_details');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('brands');
    }
};
