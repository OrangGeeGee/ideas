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
    $user = Auth::user();
    $idea = new Idea($data);

    $user->ideas()->save($idea);
    $idea->userData = $idea->userData->toArray();

    # Notify secretaries about the new idea. Candy time!
    if ( $user->hasEstonianEmailAddress() )
    {
      Mail::send('emails.idea', compact('idea', 'user'), function($message)
      {
        $message
          ->to('liivalaia-sekretarid@swedbank.ee', 'Liivalaia sekretärid')
          ->subject('[Angaar] Uus idee');
      });
    }

    return $idea;
	}

  /**
   * @param number $id
   * @return string
   */
  public function getTitle($id)
	{
		return Idea::find($id)->title;
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
