<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller {

	public function index() {
		return \App\Comment::all();
	}

	public function store(Request $request) {
		$data = $request->all();
    $comment = new \App\Comment($data);
    $user = \Auth::user();

    $user->comments()->save($comment);

		$idea = $comment->idea;
		$statusId = $request->input('status_id', $idea->status_id);

		if ( !$idea->hasStatus($statusId) && $idea->statusCanBeChangedBy($user) ) {
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