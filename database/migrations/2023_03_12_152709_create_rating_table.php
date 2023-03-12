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
    Schema::create('ratings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id');
        $table->unsignedBigInteger('pressing_id');
        $table->integer('value');
        $table->timestamps();

        $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('pressing_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
