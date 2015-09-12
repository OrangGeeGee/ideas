<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->timestamp('uploaded_at');
		});

		Schema::create('comment_image', function(Blueprint $table)
		{
			$table->integer('comment_id');
			$table->string('image_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
		Schema::drop('comment_image');
	}

}
