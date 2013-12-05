<?php

require 'ldap-auth.php';


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

Route::resource('users', 'UserController');
Route::resource('ideas', 'IdeaController');
Route::resource('comments', 'CommentController');

Route::get('update', function()
{
  $lastUpdate = Session::get('lastUpdateCheck');
  Session::put('lastUpdateCheck', date('Y-m-d H:i:s'));

  return array(
    'Ideas' => Idea::latest($lastUpdate)->get()->toArray(),
    'Comments' => Comment::latest($lastUpdate)->get()->toArray()
  );
});
