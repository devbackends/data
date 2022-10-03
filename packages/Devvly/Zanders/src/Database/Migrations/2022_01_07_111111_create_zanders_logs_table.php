<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZandersLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zanders_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('import_zanders')->default(0);
            $table->dateTime('import_latest_run')->nullable();
            $table->boolean('update_zanders_distribute')->default(0);
            $table->dateTime('update_latest_run')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zanders_logs');
    }
}
