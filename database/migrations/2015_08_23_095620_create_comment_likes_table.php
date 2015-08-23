<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comment_likes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->integer('comment_id');
			$table->timestamp('timestamp');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comment_likes');
	}

}
