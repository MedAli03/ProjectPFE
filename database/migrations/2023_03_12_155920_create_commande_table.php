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
    Schema::create('commandes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id');
        $table->unsignedBigInteger('pressing_id');
        $table->unsignedBigInteger('tarif_id');
        $table->string('status')->default('pending');
        $table->integer('quantity');
        $table->timestamps();

        $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('pressing_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('tarif_id')->references('id')->on('tarifs')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};
