<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestrictionStatesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('restriction_states', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('pid')->unsigned();
      $table->index('pid');
      $table->foreign('pid')->references('id')->on('products')->onDelete('cascade');
      $table->string('state');
      $table->string('municipality')->nullable();
      $table->string('type')->nullable();
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
    Schema::dropIfExists('restriction_states');
  }
}
