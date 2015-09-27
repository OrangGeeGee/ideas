<?php namespace App\Http\Controllers;

use App\Idea;
use App\IdeaSubscription;
use App\View;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
   * @return View
   */
  public function view(Idea $idea) {
    $lastView = $idea->views->filter(function($view) {
      return $view->user_id == Auth::user()->id;
    })->last();

    # Idea author's own views don't count.
    if ( $idea->user->id == Auth::user()->id ) {
      return;
    }

    # Limit one view per user per 30 minutes.
    if ( !$lastView || $lastView->timestamp->diffInMinutes(Carbon::now()) > 30 ) {
      return $idea->view();
    }
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
   * @return IdeaSubscription|void
   */
  public function subscribe(Idea $idea) {
    $user = Auth::user();

    if ( $idea->user_id == $user->id || $idea->userHasSubscribed() ) {
      return;
    }

    $subscription = $idea->subscribe();

    return $subscription;
  }


  /**
   * @param Idea $idea
   */
  public function unsubscribe(Idea $idea) {
    $idea->getUserSubscription()->delete();
  }

  /**
   * @param Idea $idea
   */
  public function unvote(Idea $idea) {

    # TODO: Delete by vote ID.
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
