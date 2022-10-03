<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('distributors', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->string('description')->nullable();
      $table->string('host')->nullable();
      $table->string('contact_name')->nullable();
      $table->string('contact_email')->nullable();
      $table->string('contact_number')->nullable();
      $table->string('contact_fax')->nullable();
      $table->string('country')->nullable();
      $table->string('state')->nullable();
      $table->string('city')->nullable();
      $table->string('street')->nullable();
      $table->string('postcode')->nullable();
      $table->integer('priority')->default(0);
      $table->decimal('latitude', 10, 5)->nullable();
      $table->decimal('longitude', 10, 5)->nullable();
      $table->boolean('status')->default(1);
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
    Schema::dropIfExists('distributors');
  }
}
