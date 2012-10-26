<?php

Route::filter('pattern: rf/*', 'auth');

Route::controller(Controller::detect());
Route::get ('(:bundle)', 'Rf::base@index');
Route::any ('(:bundle)/ardoises/edit/(:any)', 'Rf::base@edit');
//Route::post('(:bundle)/ardoises/edit/(:any)', 'Rf::base@edit');
Route::post('(:bundle)/ardoises/credit/(:any)', 'Rf::base@credit');

Route::any('(:bundle)/ardoises/(add|transfert)', function ($action)
{
	return Controller::call("RF::base@{$action}");
});

Route::any('(:bundle)/vols/add', 'Rf::base@add_vol');

// Gestion des soiréees
Route::get('(:bundle)/soirees/(:num)', 'Rf::soirees@one');
Route::any('(:bundle)/soirees/validate/(:num)', 'Rf::soirees@validate_one');
Route::get('(:bundle)/soirees/delete/(:num)', 'Rf::soirees@delete');
Route::controller('rf::soirees');



Route::get('(:bundle)/login', function()
{
    return View::make('rf::login');
});

Route::post('(:bundle)/login', function()
{
	$mdp_super = md5(Input::get('rf_pass'));
	$user = Auth::user();
	
	if($user->roles() && $user->mdp_super == $mdp_super)
	{
		Session::put('rf_session', 1);
		return Redirect::to('rf/');
	}
	
	return Redirect::to('rf/login');
});

Route::get('(:bundle)/logs', function()
{
    return View::make('rf::logs');
});

Route::get('(:bundle)/ardoises', function()
{
    return View::make('rf::ardoises.home', array(
    	'users' => Utilisateur::get()
    ));
});

Route::get('(:bundle)/produits', array('as' => 'produits', function()
{
    return View::make('rf::produits', array(
    	'groupes' => Groupe::get()
    ));
}));

Route::get('(:bundle)/forfaits', array('as' => 'forfaits', function()
{
    return View::make('rf::forfaits', array(
    	'forfaits' => Forfait::get()
    ));
}));

Route::get('(:bundle)/frigos', array('as' => 'frigos', function()
{
    return View::make('rf::frigos', array(
    ));
}));