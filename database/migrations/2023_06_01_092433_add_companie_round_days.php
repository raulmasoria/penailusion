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
        Schema::table('round_days', function (Blueprint $table) {
            $table->unsignedBigInteger('id_companie');
            $table->foreign('id_companie')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('round_days', function (Blueprint $table) {
            $table->dropColumn('id_companie');
        });
    }
};
