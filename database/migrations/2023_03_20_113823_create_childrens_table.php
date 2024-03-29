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
        if(!Schema::hasTable('childrens')){
            Schema::create('childrens', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('lastname');
                $table->date('birthdate')->nullable();
                $table->string('responsible')->nullable();
                $table->string('phone_responsible')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('childrens');
    }
};
