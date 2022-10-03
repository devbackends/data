<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('inventories', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('pid')->unsigned();
      $table->index('pid');
      $table->foreign('pid')->references('id')->on('products')->onDelete('cascade');
      $table->bigInteger('did')->unsigned();
      $table->index('did');
      $table->foreign('did')->references('id')->on('distributors')->onDelete('cascade');
      $table->string('distributor');
      $table->string('sku')->nullable();
      $table->integer('stock');
      $table->decimal('cost')->nullable();
      $table->string('condition')->nullable();
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
    Schema::dropIfExists('inventories');
  }
}
