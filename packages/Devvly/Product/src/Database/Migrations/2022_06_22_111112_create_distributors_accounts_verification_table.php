<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsAccountsVerificationTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dist_accounts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('uid')->unsigned();
      $table->index('uid');
      $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('did')->unsigned();
      $table->index('did');
      $table->foreign('did')->references('id')->on('distributors')->onDelete('cascade');
      $table->string('distributor');
      $table->boolean('active')->default(0);
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
    Schema::dropIfExists('dist_accounts');
  }
}
