<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ffl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('license_number', 20)->unique();
            $table->integer('license_region')->nullable();
            $table->integer('license_district')->nullable();
            $table->string('license_fips',3)->nullable();
            $table->string('license_type',2)->nullable();
            $table->char('license_expire_date',2)->nullable();
            $table->char('license_sequence',5)->nullable();
            $table->string('license_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state',2)->nullable();
            $table->string('zip_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('latitude',9,6)->nullable();
            $table->decimal('longitude',9,6)->nullable();
            $table->boolean('preferred')->default(false);
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
        Schema::dropIfExists('ffl');
    }
}
