<?php

Route::filter('pattern: rf', 'auth');
Route::filter('pattern: rf/*', 'auth');

//Route::filter('pattern: rf/(?!login)*', 'rf-auth');

// Gestion des stocks
Route::any('(:bundle)/stocks/produit/edit', 'Rf::stocks@produit_edit');
Route::any('(:bundle)/stocks/groupe/edit', 'Rf::stocks@groupe_edit');
Route::any('(:bundle)/stocks/groupev/edit', 'Rf::stocks@groupev_edit');
Route::any('(:bundle)/vols/add', 'Rf::base@add_vol');
Route::any('(:bundle)/frigos/add', 'Rf::base@add_frigos');

//Gestion des commandes
Route::any('(:bundle)/commandes/validate/(:num)', 'Rf::commandes@validate_one');
Route::any('(:bundle)/commandes/(:num)', 'Rf::commandes@one');

// Gestion des soiréees
Route::any('(:bundle)/soirees/validate/(:num)', 'Rf::soirees@validate_one');
Route::any('(:bundle)/soirees/(:num)', 'Rf::soirees@one');


Route::controller(Controller::detect('rf'));
Route::any('(:bundle)/(:any)', 'Rf::base@(:1)');
Route::any('(:bundle)', 'Rf::base@index');




//
// Gestion des produits
//

Route::get('(:bundle)/produits', array('as' => 'produits', function()
{
    return View::make('rf::produits');
}));

Route::post('(:bundle)/produits/add/g', 'Rf::base@add_groupe');
Route::post('(:bundle)/produits/add/p', 'Rf::base@add_produit');

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
		
		if($l->vols()->count() > 0)
			$temps_ecoule[$l->id] = Date::forge($l->vols()->order_by('date', 'desc')->first()->date)->ago();
	}
	
	return View::make('rf::frigos', array(
		'lieux' => $lieux,
		'vols_30d' => $vols_30d,
		'consos_30d' => $consos_30d,
		'temps_ecoule' => $temps_ecoule
	));
}));


Route::get('(:bundle)/roles/list', function(){
	$roles = Role::get(array('id', 'nom'));
	$roles_json = array();
	foreach ($roles as $r) {
		$roles_json[] = array('value' => $r->id, 'text'=>$r->nom);
	}
	return Response::json($roles_json);
});

Route::post('(:bundle)/roles/attrib', function()
{
	if(!Auth::can('peutattribuerrole'))
		return "Désolé, vous n'avez pas les droits nécessaires.";
	
	try {
		$u = Utilisateur::find(Input::get('pk'));
		if(Input::get('value') == array())
			$u->roles()->delete();
		else
			$u->roles()->sync(array_values(Input::get('value')));
		return true;
	} catch (Exception $e) {
		return "Désolé, une erreur est survenue lors de l'enregistrement.";
	}
});

Route::post('(:bundle)/roles/mdp', function()
{
	if(!Auth::can('peutattribuerrole'))
		return "Désolé, vous n'avez pas les droits nécessaires.";
	
	try {
		$u = Utilisateur::find(Input::get('pk'));
		if(Input::get('value') != '')
		{
			$u->mdp_super = md5(Input::get('value'));
			$u->save();
			return true;
		}
		else
			return "Le mot ne passe ne doit pas être vide";
	} catch (Exception $e) {
		return "Désolé, une erreur est survenue lors de l'enregistrement.";
	}
});
