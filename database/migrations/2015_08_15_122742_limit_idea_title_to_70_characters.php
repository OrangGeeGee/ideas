<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LimitIdeaTitleTo70Characters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ideas', function(Blueprint $table)
		{
			$table->string('title', 70)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ideas', function(Blueprint $table)
		{
			$table->string('title', 255)->change();
		});
	}

}
