<?php

class CommentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Comment::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::get();
    $comment = Comment::create($data);
    $user = Auth::user();

    $user->comments()->save($comment);
    $ideaAuthor = $comment->idea->user;

    # Notify the author of the idea.
    if ( $user->id != $ideaAuthor->id and !empty($ideaAuthor->email) )
    {
      Mail::send('emails.comment', compact('comment', 'ideaAuthor'), function($message) use($ideaAuthor)
      {
        $message
          ->to($ideaAuthor->email, $ideaAuthor->name)
          ->subject('[Brainstorm] New comment to your idea');
      });
    }

    unset($comment->idea);
    return $comment;
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