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
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->string('cod')->nullable();
            $table->integer('num')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('date2')->nullable();
            $table->decimal('subtotal', 20, 2)->default(0);
            $table->decimal('iva', 20, 2)->default(0);
            $table->decimal('total', 20, 2)->default(0);
            $table->integer('state')->default(1);
            $table->text('text')->nullable();
            $table->integer('concept')->nullable();
            $table->integer('ref')->nullable();
            $table->decimal('cant', 20, 2)->nullable();
            $table->decimal('saldo', 20, 2)->nullable();
            $table->decimal('value', 20, 2)->nullable();
            $table->unsignedBigInteger('person2_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('doc_id')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('set null');
            $table->foreign('person2_id')->references('id')->on('people')->onDelete('set null');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('doc_id')->references('id')->on('docs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
