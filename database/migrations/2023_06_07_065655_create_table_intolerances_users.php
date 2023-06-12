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
        if(!Schema::hasTable('intolerances_users')){
            Schema::create('intolerances_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_intolerance');
                $table->timestamps();
                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('id_intolerance')->references('id')->on('intolerances')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intolerances_users');
    }
};
