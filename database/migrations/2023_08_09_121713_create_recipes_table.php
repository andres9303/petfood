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
        Schema::create('recipes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('ref_id')->constrained('products');
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('cant', 22, 4)->default(0);
            $table->text('text')->nullable();
            $table->timestamps();

            $table->primary(['product_id', 'ref_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
