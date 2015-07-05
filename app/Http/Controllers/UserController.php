<?php namespace App\Http\Controllers;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		return \App\WHOISUser::all();
	}

}