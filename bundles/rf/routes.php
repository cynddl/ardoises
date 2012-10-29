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



Route::any('(:bundle)/(roles|logs)', function ($action)
{
	return Controller::call("RF::base@{$action}");
});


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

Route::get('(:bundle)/ardoises', function()
{
    return View::make('rf::ardoises.home');
});

Route::get('(:bundle)/produits', array('as' => 'produits', function()
{
    return View::make('rf::produits');
}));

Route::get('(:bundle)/forfaits', array('as' => 'forfaits', function()
{
    return View::make('rf::forfaits');
}));

Route::get('(:bundle)/frigos', array('as' => 'frigos', function()
{
	$date = Date::forge('now - 30 days')->format('datetime');
	$lieux = Lieu::get();
	
	foreach ($lieux as $l) {
		$consos_30d[$l->id] = Consommation::join('groupeV', 'consommation.groupeV_id', '=', 'groupeV.id')
			->where('groupeV.lieu_id','=', $l->id)
			->sum('uniteachetee');
		$vols_30d[$l->id] = Vol::where_lieu_id($l->id)
			->where('date', '>', $date)
			->sum('qte_volee');
		$temps_ecoule[$l->id] = Date::forge($l->vols()->order_by('date', 'desc')->first()->date)->ago();
	}
	
	return View::make('rf::frigos', array(
		'lieux' => $lieux,
		'vols_30d' => $vols_30d,
		'consos_30d' => $consos_30d,
		'temps_ecoule' => $temps_ecoule
	));
}));