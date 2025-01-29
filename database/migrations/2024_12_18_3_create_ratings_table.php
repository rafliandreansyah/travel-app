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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('car_id')->constrained();
            $table->integer('rating');
            $table->longText('comment');
            $table->string('image_url');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default NOW()
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan auto update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
