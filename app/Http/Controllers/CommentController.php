<?php namespace App\Http\Controllers;

class CommentController extends Controller {

	public function index() {
		return \App\Comment::all();
	}

	public function store() {
		$data = \Request::all();
    $comment = new \App\Comment($data);
    $user = \Auth::user();

    $user->comments()->save($comment);

		if ( isset($data['status_id']) && !$comment->idea->hasStatus($data['status_id']) ) {
			\App\StatusChange::create([
				'idea_id' => $comment->idea_id,
				'comment_id' => $comment->id,
				'status_id' => $data['status_id'],
			]);
		}

    unset($comment->idea);
    return $comment;
	}

}