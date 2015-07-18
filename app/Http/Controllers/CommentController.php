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

    unset($comment->idea);
    return $comment;
	}

}