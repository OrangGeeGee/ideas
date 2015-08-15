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
		$data = $request->all();
		$user = \Auth::user();

		# Prevent users from self-assigning them elevated rights.
		unset($data['canModerateStatuses']);

		$user->settings()->update($data);
	}

}