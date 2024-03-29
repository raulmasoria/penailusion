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
        if(!Schema::hasTable('childrens_antiquities')){
            Schema::create('childrens_antiquities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('children_id')->constrained()->onDelete('cascade');
                $table->integer('year');
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
        Schema::dropIfExists('childrens_antiquities');
    }
};
