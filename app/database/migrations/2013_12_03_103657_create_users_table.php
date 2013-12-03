<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->timestamps();
		});

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->string('user_id')->after('id');
    });

    Schema::table('comments', function(Blueprint $table)
    {
      $table->string('user_id')->after('idea_id');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->dropColumn('user_id');
    });

    Schema::table('comments', function(Blueprint $table)
    {
      $table->dropColumn('user_id');
    });
	}

}
