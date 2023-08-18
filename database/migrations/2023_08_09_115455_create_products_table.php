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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable();
            $table->string('name', 100);
            $table->decimal('factor', 22, 4)->default(0);
            $table->decimal('valueu', 22, 4)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('state')->default(1);
            $table->boolean('isinventory')->default(false);
            $table->integer('class')->nullable();
            $table->integer('type')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
