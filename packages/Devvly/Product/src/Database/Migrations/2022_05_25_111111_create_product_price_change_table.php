<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceChangeTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_price_changes', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('product_id');
      $table->string('upc');
      $table->decimal('old_msrp')->nullable();
      $table->decimal('old_map')->nullable();
      $table->decimal('msrp')->nullable();
      $table->decimal('map')->nullable();
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
    Schema::dropIfExists('product_price_changes');
  }
}
