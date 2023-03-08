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
        $table->unsignedBigInteger('article_id');
        $table->unsignedBigInteger('service_id');
        $table->string('status')->default('pending');
        $table->integer('quantity');
        $table->decimal('total_price');
        $table->timestamps();

        $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('pressing_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
