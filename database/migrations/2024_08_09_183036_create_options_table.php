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
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('type_id')
                ->constrained('types')
                ->cascadeOnDelete();
            $table->foreignId('addition_id')
                ->constrained('additions')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['product_id','type_id','addition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
