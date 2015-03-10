<?php

class IdeaController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    return Idea::with('userData')->get()->each(function($idea)
    {
      $idea->userData = $idea->userData->toArray();
    });
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::get();
    $idea = new Idea($data);

    Auth::user()->ideas()->save($idea);
    $idea->userData = $idea->userData->toArray();

    return $idea;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
