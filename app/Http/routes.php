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
 * Alias for trans() helper, prioritizing the $locale argument.
 *
 * @param string $label
 * @param string $locale
 * @param array $data
 * @return string
 */
function localize($label, $locale = 'en', $data = []) {
  return trans($label, $data, null, $locale);
};

/**
 * @param array $list
 * @param string $locale
 * @return string
 */
function humanizeList(array $list, $locale = 'en') {
  $and = localize('frame.and', $locale);

  if ( count($list) == 1 ) {
    return $list[0];
  }
  else {
    $lastItem = array_pop($list);
    $commaSeparated = implode(', ', $list) . " $and $lastItem";

    return $commaSeparated;
  }
}

/**
 * @param string $text
 * @return array
 */
function getMentionedUsers($text) {
  preg_match_all('/@([^@ ]\w+)/', $text, $mentions);

  return $mentions[1];
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
Route::post('ideas/{idea}/views', 'IdeaController@view');
Route::post('ideas/{idea}/vote', 'IdeaController@vote');
Route::delete('ideas/{idea}/vote', 'IdeaController@unvote');
Route::post('ideas/{idea}/share', 'IdeaController@share');
Route::post('ideas/{idea}/subscription', 'IdeaController@subscribe');
Route::delete('ideas/{idea}/subscription', 'IdeaController@unsubscribe');
Route::get('ideas/{idea}/notifySecretaries', function($idea) {
  Notifications::notifySecretaries($idea);
});
Route::get('ideas/{idea}/delete', 'IdeaController@destroy');
Route::resource('comments', 'CommentController');
Route::post('comments/{comment}/like', 'CommentController@like');
Route::resource('categories', 'CategoryController');

Route::get('update', function() {
  $user = Auth::user();
  $lastUpdate = $user->last_activity_at;
  $user->updateActivityTimestamp();

  return array(
    'Users' => App\WHOISUser::newest($lastUpdate)->get()->toArray(),
    'UserActivity' => App\User::where('last_activity_at', '>=', $lastUpdate)->get()->toArray(),
    'Ideas' => App\Idea::latest($lastUpdate)->get()->toArray(),
    'IdeaViews' => App\View::latest($lastUpdate)->get()->toArray(),
    'StatusChanges' => App\StatusChange::latest($lastUpdate)->get()->toArray(),
    'Votes' => App\Vote::latest($lastUpdate)->get()->toArray(),
    'CommentLikes' => App\CommentLike::latest($lastUpdate)->get()->toArray(),
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
Route::model('comment', 'App\Comment');
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
