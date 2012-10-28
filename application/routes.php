<?php

/*
|--------------------------------------------------------------------------
| Auth Filters
|--------------------------------------------------------------------------
*/

Route::get('/login', function()
{
	return View::make('auth.login');
});

Route::post('/login', function()
{
	$rules = array(
		'username' => 'required',
		'password' => 'required'
	);
	
	$validation = Validator::make(Input::all(), $rules);
	if ($validation->fails())
	    return Redirect::to('/login')->with_errors($validation)->with_input();
	
	return Auth::attempt(Input::all()) ? Redirect::to('/') : Redirect::to('/login')->with_input();
});

Route::get('/logout', array('as' => 'logout', function()
{
	Auth::logout();
	return Redirect::to('/login');
}));



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::controller(Controller::detect());
Route::any('prefs', 'home@prefs');


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/

Route::filter('before', function()
{
	View::share('layout', Auth::guest() ? 'template.layout' : 'template.logged');
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::filter('rf-auth', function()
{
	if(!(Session::get('rf_session')))
		return Redirect::to('rf/login');
});