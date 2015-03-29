<?php

# Disable LDAP authentication for artisan commands.
if ( PHP_SAPI != 'cli' )
{
  require 'ldap-auth.php';
}


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('login', function()
{
  return View::make('login');
});

Route::post('auth', function()
{
  $user = User::firstOrCreate(array(
    'name' => Input::get('name'),
    'email' => Input::get('email', '')
  ));

  Auth::login($user);
});

Route::resource('users', 'UserController');
Route::resource('ideas', 'IdeaController');
Route::resource('comments', 'CommentController');
Route::resource('categories', 'CategoryController');

Route::get('update', function()
{
  $lastUpdate = Session::get('lastUpdateCheck');
  Session::put('lastUpdateCheck', date('Y-m-d H:i:s'));

  return array(
    'Ideas' => Idea::latest($lastUpdate)->get()->toArray(),
    'Comments' => Comment::latest($lastUpdate)->get()->toArray()
  );
});

function createOrUpdatePivot($pivot, $idColumn, $id, $data) {
  $existingRow = $pivot->where($idColumn, $id)->first();
  $previousData = array();

  if ( $existingRow ) {
    $previousData = $existingRow->pivot->toArray();
  }

  $newData = array_merge($previousData, $data);

  $pivot->detach($id);
  $pivot->attach($id, $newData);
}

Route::get('ideas/{id}/read', function($id)
{
  $user = Auth::user();
  $idea = Idea::find($id);

  createOrUpdatePivot($idea->userData(), 'user_id', $user->id, array(
    'seen_at' => DB::raw('NOW()')
  ));
});

Route::get('ideas/{id}/vote', function($id)
{
  $user = Auth::user();
  $idea = Idea::find($id);

  # Can't vote for your own idea.
  if ( $idea->user_id == $user->id or $user->available_votes == 0 )
  {
    return;
  }

  $userData = $idea->userData;

  # Each idea can only be voted once.
  if ( !$idea->hasBeenVotedFor() )
  {
    createOrUpdatePivot($idea->userData(), 'user_id', $user->id, array(
      'voted_at' => DB::raw('NOW()')
    ));
    $user->decrement('available_votes');

    # Notify the author.
    if ( App::environment() == 'production' )
    {
      Mail::send('emails.vote', compact('idea', 'user'), function($message) use($idea)
      {
        $author = $idea->user;

        $message
          ->to($author->email, $author->name)
          ->subject('[Brainstorm] Your idea gained a vote');
      });
    }
  }
});

Route::get('ideas/{id}/unvote', function($id)
{
  $user = Auth::user();
  $idea = Idea::find($id);

  DB::table('idea_user')
    ->where('user_id', '=', Auth::user()->id)
    ->where('idea_id', '=', $id)
    ->delete();
});
