<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('godfathers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_godfather_1')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_godfather_2')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_new')->constrained('users')->onDelete('cascade');
            $table->year('year_godfather');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('godfathers');
    }
};
