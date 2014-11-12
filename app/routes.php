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

Route::get('ideas/{id}/vote', function($id)
{
  $user = Auth::user();
  $idea = Idea::find($id);

  # Can't vote for your own idea.
  if ( $idea->user_id == $user->id or $user->available_votes == 0 )
  {
    return;
  }

  $votes = $idea->votes;

  # Each idea can only be voted once.
  if ( !$votes->contains($user->id) )
  {
    $idea->votes()->attach($user->id);
    $user->decrement('available_votes');

    # Notify the author.
    Mail::send('emails.vote', compact('idea', 'user'), function($message) use($idea)
    {
      $author = $idea->user;

      $message
        ->to($author->email, $author->name)
        ->subject('[Brainstorm] Your idea gained a vote');
    });
  }
});
