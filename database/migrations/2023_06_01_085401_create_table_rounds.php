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
        if(!Schema::hasTable('round_days')){
            Schema::create('round_days', function (Blueprint $table) {
                $table->id();
                $table->string('tipo');
                $table->timestamp('day');
                $table->time('hour');
                $table->string('description')->nullable();
                $table->boolean('march')->default(0);
                $table->boolean('active')->default(1);
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
        Schema::dropIfExists('round_days');
    }
};
