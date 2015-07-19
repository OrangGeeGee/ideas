<?php

# Avoid running certain libraries for artisan commands.
if ( PHP_SAPI != 'cli' ) {
  require app_path('LDAP.php');
  require app_path('Activities.php');
  require app_path('Notifications.php');
}

/**
 * Helper function for enabling cross-origin resource sharing via headers.
 */
function enableCORS() {
  header('Access-Control-Allow-Origin: *');
}


/**
 * App routes.
 * ----------------------------------------------------------------------------
 */

Route::get('/', function() {

  if ( !Auth::check() ) {
    LDAP::login();
  }

  Activities::record(Activities::OPEN_APP);
  $user = Auth::user();

  if ( $user->hasEstonianEmailAddress() ) {
    App::setLocale('et');
  }

	return View::make('app');
});

Route::resource('users', 'UserController');
Route::resource('ideas', 'IdeaController');
Route::get('ideas/{idea}/title', 'IdeaController@getTitle');
Route::get('ideas/{idea}/read', 'IdeaController@read');
Route::post('ideas/{idea}/vote', 'IdeaController@vote');
Route::delete('ideas/{idea}/vote', 'IdeaController@unvote');
Route::get('ideas/{idea}/delete', 'IdeaController@destroy');
Route::resource('comments', 'CommentController');
Route::resource('categories', 'CategoryController');

Route::get('update', function() {
  $user = Auth::user();
  $lastUpdate = $user->last_activity_at;
  $user->updateActivityTimestamp();

  return array(
    'Users' => App\WHOISUser::newest($lastUpdate)->get()->toArray(),
    'UserActivity' => App\User::where('last_activity_at', '>=', $lastUpdate)->get()->toArray(),
    'Ideas' => App\Idea::latest($lastUpdate)->get()->toArray(),
    'Votes' => App\Vote::latest($lastUpdate)->get()->toArray(),
    'Comments' => App\Comment::latest($lastUpdate)->get()->toArray()
  );
});

Route::get('top', function() {
  $ideas = App\Idea::with('votes', 'user', 'comments', 'status')->where('category_id', 1)->get();
  return View::make('top', compact('ideas'));
});

Route::post('top/update', function() {
  $idea = App\Idea::find(Input::get('id'));
  $idea->area = Request::get('area');
  $idea->responsible = Request::get('responsible');
  $idea->save();
});

Route::get('activities', function() {
  enableCORS();
  return App\Activity::all();
});


/**
 * Route model bindings.
 * ----------------------------------------------------------------------------
 */

Route::model('idea', 'App\Idea');
Route::model('user', 'App\User');


/**
 * Debug routes.
 * ----------------------------------------------------------------------------
 */

Route::get('/whoami', function() {
  if ( !Auth::check() ) {
    return 'User not logged in';
  }

  return Auth::user();
});

if ( App::environment() == 'local' ) {
  Route::get('register/{uid}', function($uid) {
    LDAP::login($uid);
  });

  Route::get('become/{user}', function(App\User $user) {
    Auth::login($user);
    return Redirect::to('/');
  });
}
