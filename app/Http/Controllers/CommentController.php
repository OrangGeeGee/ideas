<?php namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {

	public function store(Request $request) {
		$data = $request->all();
    $comment = new \App\Comment($data);
    $user = Auth::user();

    $user->comments()->save($comment);

		if ( isset($data['image_id']) ) {
			$comment->images()->attach([
				'id' => $data['image_id'],
			]);
		}

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
		$comment->load('images');

    return $comment;
	}

	public function like(Comment $comment) {

		if ( $comment->user_id == Auth::user()->id ) {
			return;
		}

		$like = $comment->like();

		return $like;
	}

}