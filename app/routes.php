<?php

# Disable LDAP authentication for artisan commands.
if ( PHP_SAPI != 'cli' )
{
  require 'ldap-auth.php';
}


/**
 * App routes.
 * ----------------------------------------------------------------------------
 */

Route::get('/', function()
{
  # For some reason, LDAP fails for first time users.
  # If that's the case, wait for a bit and try again.
  if ( Auth::user()->id == "0" )
  {
    sleep(1);
    return Redirect::to('/');
  }

  require '../app/UserAgentParser.php';
  $logData = parse_user_agent();
  $logData['user_id'] = Auth::user()->id;
  RequestLog::create($logData);

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
    'Users' => User::newest($lastUpdate)->get()->toArray(),
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
  if ( $idea->user_id == $user->id or !$user->hasFreeVotes() )
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
          ->subject('[Angaar] Sinu idee sai hääle');
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

Route::get('ideas/{id}/delete', function($id)
{
  $user = Auth::user();
  $idea = Idea::find($id);

  if ( $idea->user_id == $user->id )
  {
    $idea->delete();
  }
});

Route::get('top', function()
{
  echo '<meta charset="utf-8"/>';
  echo "<table>";
  echo "<tr>";
  echo "  <th>Lisaja</th>";
  echo "  <th>Hääli</th>";
  echo "  <th>Kommentaare</th>";
  echo "  <th>Idee</th>";
  echo "  <th>Kirjeldus</th>";
  echo "</tr>";

  foreach ( Idea::with('votes', 'user', 'comments')->where('category_id', 1)->get() as $idea )
  {
    $votes = count($idea->votes);
    $comments = count($idea->comments);
    $url = 'http://eos.crebit.ee/angaar/#ideas/' . $idea->id;

    echo "<tr>";
    echo "  <td>{$idea->user->name}</td>";
    echo "  <td>{$votes}</td>";
    echo "  <td>{$comments}</td>";
    echo "  <td><a href=\"{$url}\" target='_blank'>{$idea->title}</a></td>";
    echo "  <td>{$idea->description}</td>";
    echo "</tr>";
  }
  echo "</table>";
});


/**
 * Debug routes.
 * ----------------------------------------------------------------------------
 */

Route::get('whoami', function()
{
  return Auth::user()->id;
});

if ( App::environment() == 'local' )
{
  Route::get('become/{id}', function($id)
  {
    $user = User::find($id);
    Auth::login($user);

    return Redirect::to('whoami');
  });
}
