<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_service');
            $table->unsignedBigInteger('id_article');
            $table->unsignedBigInteger('id_pressing');
            $table->decimal('price');
            $table->timestamps();

            $table->foreign('id_service')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('id_article')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('id_pressing')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
