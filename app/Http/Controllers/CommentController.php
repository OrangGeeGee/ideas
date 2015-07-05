<?php namespace App\Http\Controllers;

class CommentController extends Controller {

	public function index() {
		return \App\Comment::all();
	}

	public function store() {
		$data = \Request::all();
    $comment = \App\Comment::create($data);
    $user = \Auth::user();

    $user->comments()->save($comment);
    $ideaAuthor = $comment->idea->user;

    # Notify the author of the idea.
    if ( $user->id != $ideaAuthor->id and !empty($ideaAuthor->email) )
    {
      \Mail::send('emails.comment', compact('comment', 'ideaAuthor', 'user'), function($message) use($ideaAuthor)
      {
        $message
          ->to($ideaAuthor->email, $ideaAuthor->name)
          ->subject('[Brainstorm] New comment to your idea'); # TODO: Localize.
      });
    }

    unset($comment->idea);
    return $comment;
	}

}