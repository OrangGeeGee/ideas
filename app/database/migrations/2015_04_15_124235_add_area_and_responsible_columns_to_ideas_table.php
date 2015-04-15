<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaAndResponsibleColumnsToIdeasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ideas', function(Blueprint $table)
		{
			$table->string('area')->after('description');
			$table->string('responsible')->after('area');
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
			$table->dropColumn(array('area', 'responsible'));
		});
	}

}