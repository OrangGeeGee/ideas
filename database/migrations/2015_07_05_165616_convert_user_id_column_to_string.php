<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertUserIdColumnToString extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('idea_user', function(Blueprint $table)
		{
			$table->string('user_id')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('idea_user', function(Blueprint $table)
		{
			$table->integer('user_id')->change();
		});
	}

}
