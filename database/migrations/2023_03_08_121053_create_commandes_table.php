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
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('pressing_id')->nullable();
            $table->string('status')->default('new');
            $table->decimal('total_amount', 8, 2);
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pressing_id')->references('id')->on('users')->onDelete('set null');
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
