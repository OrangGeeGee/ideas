<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_changes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('idea_id');
			$table->integer('comment_id');
			$table->integer('status_id');
			$table->timestamp('timestamp');
		});

		Schema::table('statuses', function($table)
		{
			$table->renameColumn('name', 'code');
		});

		Schema::table('ideas', function($table)
		{
			$table->dropColumn('status_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('status_changes');

		Schema::table('statuses', function($table)
		{
			$table->renameColumn('code', 'name');
		});

		Schema::table('ideas', function($table)
		{
			$table->integer('status_id')->after('user_id');
		});
	}

}
