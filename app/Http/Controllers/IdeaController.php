<?php namespace App\Http\Controllers;

use App\Idea;
use App\Vote;
use Illuminate\Http\Request;

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
   * @param Request $request
   * @param integer $id
   */
  public function update(Request $request, $id) {
    $idea = Idea::find($id);

    if ( $idea->user_id !== \Auth::user()->id ) {
      return;
    }

    $idea->title = $request->get('title');
    $idea->description = $request->get('description');
    $idea->save();
  }

  /**
   * @param number $id
   * @return string
   */
  public function getTitle($id) {
    enableCORS();
		return Idea::find($id)->title;
	}

  /**
   * @param Idea $idea
   */
  public function read(Idea $idea) {
    $idea->views()->create([]);
  }

  /**
   * @param Idea $idea
   * @param Vote
   */
  public function vote(Idea $idea) {
    $user = \Auth::user();

    # Can't vote for your own idea.
    if ( $idea->user_id == $user->id ) {
      return;
    }

    # Each idea can only be voted once per user.
    if ( $idea->hasBeenVotedFor() ) {
      return;
    }

    $vote = Vote::create([
      'idea_id' => $idea->id,
      'user_id' => $user->id,
      'timestamp' => \DB::raw('NOW()')
    ]);

    return $vote;
  }

  public function share(Idea $idea, Request $request) {
    $recipient = \App\WHOISUser::where('email', $request->get('email'))->first();

    if ( !$recipient ) {
      return;
    }

    $idea->shares()->create([
      'recipient_id' => $recipient->id
    ]);
  }

  /**
   * @param Idea $idea
   */
  public function unvote(Idea $idea) {
    Vote::where([
      'user_id' => \Auth::user()->id,
      'idea_id' => $idea->id,
    ])->delete();

    # TODO: Listen to model event.
    \Activities::record(\Activities::UNVOTE_IDEA, $idea->title);
  }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Idea $idea
	 */
	public function destroy(Idea $idea)
  {
    $user = \Auth::user();

    if ( $idea->user_id != $user->id ) {
      return;
    }

    $idea->delete();
  }

}
