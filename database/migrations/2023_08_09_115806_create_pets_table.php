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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date')->nullable();
            $table->decimal('weight', 16, 2)->nullable();
            $table->string('living')->nullable();
            $table->boolean('sib')->nullable();
            $table->string('diet')->nullable();
            $table->string('exercise')->nullable();
            $table->string('allergy')->nullable();
            $table->string('vaccine')->nullable();
            $table->string('deworming')->nullable();
            $table->string('health')->nullable();
            $table->string('reproductive')->nullable();
            $table->string('text')->nullable();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('race_id');
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
