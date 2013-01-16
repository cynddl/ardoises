<?php

class Rf_Base_Controller extends Base_Controller {
	
	public $restful = true;

	public function get_index()
	{
		return View::make('rf::home');
	}
	
	//
	// Connection
	//
	
	public function get_login()
	{
		return View::make('rf::login');
	}
	
	public function post_login()
	{
		$mdp_super = md5(Input::get('rf_pass'));
		$user = Auth::user();
	
		if($user->roles() && $user->mdp_super == $mdp_super)
		{
			Session::put('rf_session', 1);
			return Redirect::to('rf/');
		}
	
		return Redirect::to('rf/login');
	}

	// Ajout des vols à la base
	public function post_add_vol()
	{
		$vols_groupe = array();
		$lieu_id = Input::get('lieu_id');
		
		foreach(Input::get('vols') as $nom => $qte)
		{
			$produit = Produit::where_nom($nom)->first();
			$groupe_id = $produit->groupe()->first()->id;
			$vols_groupe[$groupe_id] = isset($vols_groupe[$groupe_id]) ? ($vols_groupe[$groupe_id] + (int) $qte) : $qte;
		}
		foreach($vols_groupe as $groupe_id => $qte)
		{
			$stock = Stockgroupe::where_lieu_id($lieu_id)->where_groupe_id($groupe_id)->first();
			if($qte != $stock->qte_frigo)
			{
				$vol = Vol::create(array('groupe_id'=>$groupe_id, 'qte_volee'=>$qte-$stock->qte_frigo, 'lieu_id'=>$lieu_id));
				$vol->save();
				$stock->qte_frigo = $qte;
				$stock->save();
			}
		}
		return Redirect::to('rf/frigos');
	}
	
	public function post_add_frigos()
	{
		DB::transaction(function()
		{
			$ajout_groupe = array();
			$lieu_id = Input::get('lieu_id');
		
			foreach(Input::get('ajout') as $nom => $qte)
			{
				$produit = Produit::where_nom($nom)->first();
				Stockproduit::modifier($produit->id, $lieu_id, -$qte);
				
				$groupe_id = $produit->groupe()->first()->id;
				$ajout_groupe[$groupe_id] = isset($ajout_groupe[$groupe_id]) ? ($ajout_groupe[$groupe_id] + (int) $qte) : $qte;
			}
			foreach($ajout_groupe as $groupe_id => $qte)
			{
				Stockgroupe::modifier($groupe_id, $lieu_id, $qte);
			}
			
			$lieu_nom = Lieu::find($lieu_id)->nom;
			
			LogDB::add_flash('success', array(
				'description' => "Frigos remplis ($lieu_nom)",
				'nomtable' => 'stockproduit',
			));
		});
		return Redirect::to('rf/frigos');
	}
	
	
	public function get_roles()
	{
		return View::make('rf::roles', array(
			'roles' => Role::all(),
			'lieux' => Lieu::all(),
			'permissions' => Permission::get(array('id', 'nom')),
			'utilisateurs' => Utilisateur::with('role')->get(array('id', 'login'))
		));
	}
	
	public function post_roles()
	{
		if(Input::get('nom')) // On ajoute un rôle
		{
			if(!Auth::can('peutajouterole')) return Redirect::to('rf/permission');
			
			DB::transaction(function(){
				$role = new Role;
				$role->nom = Input::get('nom');
				if(Input::get('lieu_id'))
					$role->set_attribute('lieu_id', Input::get('lieu_id'));
				$role->save();
				if(Input::get('permissions'))
					$role->permissions()->sync(Input::get('permissions'));
				$role->save();
				
				LogDB::add_flash('success', array(
					'description' => "Le rôle « $role->nom » a été ajouté.",
					'nomtable' => 'role',
					'idtable' => $role->id
				));
			});
		} else if(Input::get('utilisateur')) // On attribue un rôle
		{
			if(!Auth::can('peutattribuerrole')) return Redirect::to('rf/permission');
			
			DB::transaction(function(){
				$utilisateur = Utilisateur::where_login(Input::get('utilisateur'))->first();
				$role = Role::find(Input::get('role'));
				$utilisateur->roles()->attach($role, array('echeance' => Input::get('echeance')));
				LogDB::add_flash('success', array(
					'description' => "Le rôle « $role->nom » a été attribué à « $utilisateur->login ».",
					'nomtable' => 'role',
					'idtable' => $role->id
				));
			});
		}
		return Redirect::to('rf/roles#utilisateurs-privilegies');
	}
	
	public function post_add_groupe()
	{
		$rules = array(
			'nom' => 'required|unique:groupe,nom',
			'nomreduit' => 'required|unique:groupe,nomreduit'
		);

		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
		    return Redirect::to('rf/produits/')->with_errors($validation)->with_input();
		
		DB::transaction(function(){
			$groupe = Groupe::create(Input::all());
			$groupe->save();
			$groupe_nom = $groupe->nom;
			LogDB::add_flash('success', array(
				'description' => "Le groupe « $groupe_nom » a été ajouté.",
				'nomtable' => 'groupe',
				'idtable' => $groupe->id
			));
		});
	
		return Redirect::back();
	}
	
	public function post_add_produit()
	{
		$rules = array(
			'nom' => 'required|unique:produit,nom',
			'groupe_id' => 'required'
		);

		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
		    return Redirect::to('rf/produits/#ajouter-produit')->with_errors($validation)->with_input();
		
		DB::transaction(function(){
			$produit = Produit::create(Input::all());
			$produit->save();
	
			$produit->groupe()->attach(Input::get('groupe_id'));
			
			$produit_nom = $produit->nom;
			LogDB::add_flash('success', array(
				'description' => "Le produit « $produit_nom » a été ajouté.",
				'nomtable' => 'produit',
				'idtable' => $produit->id
			));
		});
	
		return Redirect::back();
	}
	
	public function get_logs()
	{
		return View::make('rf::logs');
	}
	
	public function get_permission()
	{
		return View::make('rf::permission');
	}
}