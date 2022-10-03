<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('map_categories', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('cid')->unsigned();
      $table->index('cid');
      $table->foreign('cid')->references('id')->on('categories')->onDelete('cascade');
      $table->bigInteger('did')->unsigned();
      $table->index('did');
      $table->foreign('did')->references('id')->on('distributors')->onDelete('cascade');
      $table->string('value');
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
    Schema::dropIfExists('map_categories');
  }
}
