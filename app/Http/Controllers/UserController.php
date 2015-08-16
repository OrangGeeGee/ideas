<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		return \App\WHOISUser::all();
	}

	public function updateSettings(Request $request) {

		# Convert boolean values to integer, otherwise Eloquent's isDirty()
		# method will mistake "1 ==> true" operations as changes.
		$data = array_map(function($boolean) {
			return (int) $boolean;
		}, $request->all());
		$user = \Auth::user();

		$user->settings->fill($data);
		$user->settings->save();
	}

}