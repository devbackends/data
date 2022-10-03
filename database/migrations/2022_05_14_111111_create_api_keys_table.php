<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiKeysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('api_keys', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('uid')->unsigned();
      $table->index('uid');
      $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
      $table->string('name');
      $table->string('type');
      $table->string('api_key')->unique();
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
    Schema::dropIfExists('api_keys');
  }
}
