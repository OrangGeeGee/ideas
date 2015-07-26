<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameIdeaUserTableToViews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('idea_user', 'views');
		Schema::table('views', function(Blueprint $table) {
			$table->renameColumn('seen_at', 'timestamp');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::rename('views', 'idea_user');
		Schema::table('idea_user', function(Blueprint $table) {
			$table->renameColumn('timestamp', 'seen_at');
		});
	}

}
