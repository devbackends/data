<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('attributes', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('pid')->unsigned();
      $table->index('pid');
      $table->foreign('pid')->references('id')->on('products')->onDelete('cascade');
      $table->json('description');
      $table->json('specifications');
      $table->text('teaser')->nullable();
      $table->integer('caliber')->nullable();
      $table->integer('manufacturer')->nullable();
      $table->string('color')->nullable();
      $table->string('man_part_num')->nullable();
      $table->string('model')->nullable();
      $table->decimal('lbs')->nullable();
      $table->decimal('oz')->nullable();
      $table->decimal('height')->nullable();
      $table->decimal('width')->nullable();
      $table->decimal('length')->nullable();
      $table->string('subcategory')->nullable();
      $table->integer('capacity')->nullable();
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
    Schema::dropIfExists('attributes');
  }
}
