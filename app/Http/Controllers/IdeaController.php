<?php namespace App\Http\Controllers;

class IdeaController extends Controller {

  /**
   * @return $this
   */
	public function index() {
    return \App\Idea::with('userData')->get()->each(function($idea)
    {
      $idea->userData = $idea->userData->toArray();
    });
	}

  /**
   * @return \App\Idea
   */
	public function store() {
		$data = \Request::all();
    $user = \Auth::user();
    $idea = new \App\Idea($data);

    $user->ideas()->save($idea);
    $idea->userData = $idea->userData->toArray();

    # Notify secretaries about the new idea. Candy time!
    if ( $user->hasEstonianEmailAddress() )
    {
      \Mail::send('emails.idea', compact('idea', 'user'), function($message)
      {
        $message
          #->to('liivalaia-sekretarid@swedbank.ee', 'Liivalaia sekretärid')
          ->cc('mattias.saldre@swedbank.ee', 'Mattias Saldre')
          ->subject('[Angaar] Uus idee');
      });
    }

    return $idea;
	}

  /**
   * @param number $id
   * @return string
   */
  public function getTitle($id) {
    enableCORS();
		return \App\Idea::find($id)->title;
	}

  /**
   * @param \App\Idea $idea
   */
  public function read(\App\Idea $idea) {
    $user = \Auth::user();

    if ( $idea->votes->contains($user->id) ) {
      $idea->votes()->updateExistingPivot($user->id, [
        'seen_at' => \DB::raw('NOW()')
      ]);
    }
    else {
      $idea->votes()->save($user, [
        'seen_at' => \DB::raw('NOW()')
      ]);
    }

    \Activities::record(\Activities::OPEN_IDEA, $idea->title);
  }

  /**
   * @param \App\Idea $idea
   */
  public function vote(\App\Idea $idea) {
    $user = \Auth::user();

    # Can't vote for your own idea.
    if ( $idea->user_id == $user->id ) {
      return;
    }

    # Each idea can only be voted once.
    if ( $idea->hasBeenVotedFor() )
    {
      return;
    }

    if ( $idea->votes->contains($user->id) ) {
      $idea->votes()->updateExistingPivot($user->id, [
        'voted_at' => \DB::raw('NOW()')
      ]);
    }
    else {
      $idea->votes()->save($user, [
        'voted_at' => \DB::raw('NOW()')
      ]);
    }

    # Notify the author.
    if ( \App::environment() == 'production' )
    {
      \Mail::send('emails.vote', compact('idea', 'user'), function($message) use($idea)
      {
        $author = $idea->user;

        $message
          ->to($author->email, $author->name)
          ->subject('[Angaar] Sinu idee sai hääle');
      });
    }

    \Activities::record(\Activities::VOTE_IDEA, $idea->title);
  }

  /**
   * @param \App\Idea $idea
   */
  public function unvote(\App\Idea $idea) {
    $user = \Auth::user();
    $idea->votes()->detach($user);

    \Activities::record(\Activities::UNVOTE_IDEA, $idea->title);
  }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Idea $idea
	 */
	public function destroy(\App\Idea $idea)
  {
    $user = \Auth::user();

    if ( $idea->user_id != $user->id ) {
      return;
    }

    $idea->delete();
  }

}
