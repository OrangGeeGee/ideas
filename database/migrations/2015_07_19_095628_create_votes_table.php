<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('votes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('idea_id');
			$table->string('user_id');
			$table->timestamp('timestamp');
		});

		foreach ( DB::table('idea_user')->where('voted_at', '!=', '0000-00-00 00:00:00')->get() as $row )
		{
			\App\Vote::create([
				'user_id' => $row->user_id,
				'idea_id' => $row->idea_id,
				'timestamp' => $row->voted_at
			]);
		}

		Schema::table('idea_user', function($table)
		{
			$table->dropColumn('voted_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('idea_user', function($table)
		{
			$table->datetime('voted_at');
		});

		foreach ( \App\Vote::all() as $vote )
		{
			DB::table('idea_user')->where([
				'user_id' => $vote->user_id,
				'idea_id' => $vote->idea_id,
			])->update([
				'voted_at' => $vote->timestamp
			]);
		}

		Schema::drop('votes');
	}

}
