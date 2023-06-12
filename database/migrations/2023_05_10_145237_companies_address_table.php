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
        if(!Schema::hasTable('companies_address')){
            Schema::create('companies_address', function (Blueprint $table) {
                $table->id();
                $table->foreignId('companie_id')->constrained()->onDelete('cascade');
                $table->string('via')->nullable();
                $table->string('direccion')->nullable();
                $table->string('piso')->nullable();
                $table->integer('cp')->nullable();
                $table->string('ciudad')->nullable();
                $table->string('provincia')->nullable();
                $table->string('coord-x')->nullable();
                $table->string('coord-y')->nullable();
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
        Schema::dropIfExists('companies_address');
    }
};
