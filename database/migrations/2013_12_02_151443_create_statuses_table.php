<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statuses', function(Blueprint $table)
		{
			$table->increments('id');
      $table->string('name');
		});

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->integer('status_id')->after('id');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('statuses');

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->dropColumn('status_id');
    });
	}

}
