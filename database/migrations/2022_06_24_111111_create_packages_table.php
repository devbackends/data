<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('packages', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->decimal('price',8,4);
      $table->decimal('cpr',8,4)->default(0);
      $table->integer('max_requests')->default(0);
      $table->decimal('exceeding_max_requests_fees',8,4)->default(0);
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
    Schema::dropIfExists('packages');
  }
}
