<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraAttrTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('extra_attr', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('pid')->unsigned();
      $table->index('pid');
      $table->foreign('pid')->references('id')->on('products')->onDelete('cascade');
      $table->string('type');
      $table->string('distributor');
      $table->text('value');
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
    Schema::dropIfExists('extra_attr');
  }
}
