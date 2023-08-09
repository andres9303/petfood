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
        Schema::create('mvtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained();
            $table->decimal('cant', 22, 4)->default(0);
            $table->decimal('factor', 22, 4)->default(0);
            $table->decimal('saldo', 22, 4)->default(0);
            $table->decimal('valueu', 22, 4)->default(0);
            $table->decimal('iva', 22, 4)->default(0);
            $table->decimal('valuet', 22, 4)->default(0);
            $table->string('text', 5000)->nullable();
            $table->integer('state')->default(1);
            $table->foreignId('product2_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('unit2_id')->nullable()->constrained('units');
            $table->decimal('cant2', 22, 4)->nullable();
            $table->decimal('saldo2', 22, 4)->nullable();
            $table->decimal('valueu2', 22, 4)->nullable();
            $table->decimal('iva2', 22, 4)->nullable();
            $table->decimal('valuet2', 22, 4)->nullable();
            $table->string('text2', 5000)->nullable();
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('set null');
            $table->foreignId('mvto_id')->nullable()->constrained('mvtos')->onDelete('set null');
            $table->integer('concept')->nullable();
            $table->integer('ref')->nullable();
            $table->decimal('costu', 22, 4)->nullable();
            $table->decimal('costt', 22, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mvtos');
    }
};
