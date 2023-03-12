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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('tarif_id');
            $table->decimal('total_price', 8, 2);
            $table->timestamps();

            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('tarif_id')->references('id')->on('tarifs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
