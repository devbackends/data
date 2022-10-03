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
    Schema::create('cards', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('uid')->unsigned();
      $table->index('uid');
      $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
      $table->string('token');
      $table->char('exp',4);
      $table->rememberToken();
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
    Schema::dropIfExists('cards');
  }
};
