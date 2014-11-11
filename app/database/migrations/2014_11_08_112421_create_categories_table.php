<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
      $table->string('name');
			$table->timestamps();
		});

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->integer('category_id')->after('id');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');

    Schema::table('ideas', function(Blueprint $table)
    {
      $table->dropColumn('category_id');
    });
	}

}
