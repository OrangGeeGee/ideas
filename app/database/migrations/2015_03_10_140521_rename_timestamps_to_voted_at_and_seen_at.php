<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdeaUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('idea_user', function(Blueprint $table)
		{
			$table->renameColumn('created_at', 'voted_at');
			$table->renameColumn('updated_at', 'seen_at');
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
      $table->renameColumn('voted_at', 'created_at');
      $table->renameColumn('seen_at', 'updated_at');
		});
	}

}