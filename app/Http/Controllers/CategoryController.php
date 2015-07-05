<?php namespace App\Http\Controllers;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		return \App\Category::all();
	}

}