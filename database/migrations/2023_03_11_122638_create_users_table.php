
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->enum('role', ['admin', 'client', 'pressing'])->default('client');
            $table->boolean('is_active')->default(true);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('pressing_name')->nullable();
            $table->float('average_rating')->nullable();
            $table->integer('number_of_raters')->nullable();
            $table->integer('cin')->nullable();
            $table->string('tva')->nullable();
            $table->boolean('is_validated')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
