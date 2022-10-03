<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ffl_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ffl_id')->unsigned();
            $table->index('ffl_id');
            $table->foreign('ffl_id')->references('id')->on('ffl')->onDelete('cascade');
            $table->string('website')->nullable();
            $table->string('license_file')->nullable();
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
        Schema::dropIfExists('ffl_info');
    }
}
