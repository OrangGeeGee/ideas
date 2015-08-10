<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRedundantColumnsFromUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\App\User::chunk(50, function($users) {
			foreach ( $users as $user ) {
				if ( !\App\WHOISUser::find($user->id) ) {
					try {
						$data = \LDAP::getUserData($user->id);
					} catch ( Exception $e ) {
						$data = [
							'id' => $user->id,
							'name' => $user->name,
							'email' => $user->email,
						];
					}

					$whoisUser = new \App\WHOISUser($data);
					$whoisUser->created_at = $user->created_at;
					$whoisUser->updated_at = $user->updated_at;
					$whoisUser->save();
				}
			}
		});

		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn(['name', 'email', 'available_votes']);
			$table->dropTimestamps();

			$table->rememberToken();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->removeColumn('remember_token');

			$table->string('name');
			$table->string('email');
			$table->integer('available_votes');
			$table->timestamps();
		});
	}

}
