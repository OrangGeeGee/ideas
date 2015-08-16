<?php namespace App\Http\Controllers;

class IdeaController extends Controller {

  /**
   * @return $this
   */
	public function index() {
    return \App\Idea::all();
	}

  /**
   * @return \App\Idea
   */
	public function store() {
		$data = \Request::all();
    $user = \Auth::user();
    $idea = new \App\Idea($data);

    $user->ideas()->save($idea);

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
    $idea->views()->create([]);
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

    # Each idea can only be voted once per user.
    if ( $idea->hasBeenVotedFor() ) {
      return;
    }

    \App\Vote::create([
      'idea_id' => $idea->id,
      'user_id' => $user->id,
      'timestamp' => \DB::raw('NOW()')
    ]);
  }

  /**
   * @param \App\Idea $idea
   */
  public function unvote(\App\Idea $idea) {
    \App\Vote::where([
      'user_id' => \Auth::user()->id,
      'idea_id' => $idea->id,
    ])->delete();

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
