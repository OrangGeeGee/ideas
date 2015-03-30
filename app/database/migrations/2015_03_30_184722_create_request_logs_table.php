<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLogsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('request_logs', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('user_id');
      $table->string('browser');
      $table->string('version');
      $table->dateTime('created_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('request_logs');
  }

}
