<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflCacheTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ffl_cache', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamp('date')->nullable();
      $table->string('zip_code')->nullable();
      $table->string('radius')->nullable();
      $table->json('data')->nullable();
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
    Schema::dropIfExists('ffl_cache');
  }
}
