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

Route::get('test', function() {
  $data = [];
  $today = new DateTime();
  $yesterday = (new DateTime())->modify('-1 day');
  $periodStart = $yesterday->format('Y-m-d');
  $periodEnd = $today->format('Y-m-d');

  $data['ideas'] = \App\Idea::latest($periodStart, $periodEnd)->get();
  $data['comments'] = \App\Comment::latest($periodStart, $periodEnd)->get();
  $data['votes'] = \App\Vote::latest($periodStart, $periodEnd)->get();

  # No updates, cancel notification.
  if ( $data['ideas']->count() == 0
    && $data['comments']->count() == 0
    && $data['votes']->count() == 0 ) {
    return;
  }

  $data['title'] = trans('emails.dailyHeading') . " " . $yesterday->format('d/m/Y');

  return View::make('emails.daily', $data);
});


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

  $data['statuses'] = App\Status::all()->each(function($status) {
    $status->name = trans('statuses.' . camel_case($status->code));
  });

  $data['users'] = App\WHOISUser::all()->each(function($user) {
    if ( $user->id === Auth::user()->id ) {
      $user->settings = App\User::find($user->id)->settings;
    }
  });

	return View::make('app', $data);
});

Route::resource('users', 'UserController');
Route::post('users/settings', 'UserController@updateSettings');
Route::resource('ideas', 'IdeaController');
Route::get('ideas/{idea}/title', 'IdeaController@getTitle');
Route::get('ideas/{idea}/read', 'IdeaController@read');
Route::post('ideas/{idea}/vote', 'IdeaController@vote');
Route::get('ideas/{idea}/notifySecretaries', function($idea) {
  Notifications::notifySecretaries($idea);
});
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
    'StatusChanges' => App\StatusChange::latest($lastUpdate)->get()->toArray(),
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
