<?php

class Rf_Base_Controller extends Base_Controller {
	
	public $restful = true;

	public function get_index()
	{
		return View::make('rf::home');
	}

	public function get_edit($login)
	{
		return View::make('rf::ardoises.one', array(
			'user' => Utilisateur::where('login', '=', $login)->first(),
		));
	}
	
	/* Modification des préférences d'un utilisateur */
	public function post_edit($login)
	{
		$user = Utilisateur::where('login', '=', $login)->first();
		$inputs = Input::all();
		
		$user->fill($inputs);
		$user->save();
		
		$login = $user->login;
		LogDB::add_flash('success', array(
			'description' => "Les préférences de l'utilisateur « $login » ont été modifiées.",
			'nomtable' => 'utilisateur',
			'idtable' => $user->id
		));
		
		return View::make('rf::ardoises.one', array(
			'user' => Utilisateur::where('login', '=', $login)->first(),
		));
	}
	
	/* Crédit d'une ardoise */
	public function post_credit($login)
	{
		$user = Utilisateur::where('login', '=', $login)->first();
		$ardoise = $user->ardoise;
		
		DB::transaction(function () use ($ardoise) {
			$credit = Credit::create(array(
				'ardoise_id' => $ardoise->id,
				'credit' => Input::get('credit'),
				'moyenpaiement_id' => Input::get('moyenpaiement')
			));
			$credit->save();
			$ardoise->montant = $ardoise->montant - $credit->credit;
			$ardoise->save();
		});
		
		$qte = Input::get('credit');
		LogDB::add_flash('success', array(
			'description' => "L'ardoise de « $login » a été créditée de $qte €.",
			'nomtable' => 'ardoise',
			'idtable' => $ardoise->id
		));
		
		return Redirect::to_action('rf::ardoises@edit.'.$login);
	}
	
	
	//
	// Création d'utilisateur et d'ardoise
	//
	
	public function get_add()
	{
		return View::make('rf::ardoises.add');
	}
	
	public function post_add()
	{
		$rules = array(
			'mail' => 'required|email',
			'prenom' => 'required',
			'nom' => 'required',
			'mdp' => 'required',
			'login' => 'required|unique:utilisateur,login',
			'promo' => 'required',
			'departement_id' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
			return Redirect::to('rf/ardoises/add/')->with_errors($validation)->with_input();
		
		DB::transaction(function(){
			$ardoise = Ardoise::create(array('montant'=>'0'));
			$ardoise->save();
			$user_vars = Input::all();
			$user_vars['ardoise_id'] = $ardoise->id;
			$user = Utilisateur::create($user_vars);
			$user->mdp = md5(Input::get('mdp'));
			$user->save();
		
			$login = Input::get('login');
			LogDB::add_flash('success', array(
				'description' => "Le compte « $login » a été créé.",
				'nomtable' => 'utilisateur',
				'idtable' => $user->id
			));
				
			return Redirect::to('rf/ardoises/credit/' . $user->id);
		});
		
		return Redirect::to('rf/ardoises');
	}
	
	
	//
	// Transfert entre ardoises
	//
	
	public function get_transfert ()
	{
		return View::make('rf::ardoises.transfert');
	}
	
	public function post_transfert ()
	{
		DB::transaction(function(){
			$debiteur = Utilisateur::where_login(Input::get('debiteur'))->first();
			$debiteur_a = $debiteur->ardoise;
			$crediteur = Utilisateur::where_login(Input::get('crediteur'))->first();
			$crediteur_a = $crediteur->ardoise;
			$montant = Input::get('montant');
			
			$t = Transfert::create(array(
				'ardoise_id_debiteur' => $debiteur_a->id,
				'ardoise_id_crediteur' => $crediteur_a->id,
				'montant' => $montant
			));
			$t->save();
			
			$debiteur_a->montant = $debiteur_a->montant + $montant; // il donne l'argent /!\
			$debiteur_a->save();
			$crediteur_a->montant = $crediteur_a->montant - $montant; // il gagne l'argent
			$crediteur_a->save();
			
			LogDB::add_flash('success', array(
				'description' => "Transfert de $montant € effectué de « " . $debiteur->login . " » vers « " . $crediteur->login . " ».",
				'nomtable' => 'transfert',
				'idtable' => $t->id
			));
		});
		return View::make('rf::ardoises.transfert');
	}
	
	

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
				if($produit->stockproduit()->where_lieu_id($lieu_id)->count() > 0 )
				{
					$stockproduit = $produit->stockproduit->where_lieu_id($lieu_id)->first();
					$stockproduit->qte_reserve = $stockproduit->qte_reserve - $qte;
					$stockproduit->save();
				} else {
					$stockproduit = StockProduit::create(array(
						'produit_id' => $produit->id,
						'lieu_id' => $lieu_id,
						'qte_reserve' => - $qte
					));
					$stockproduit->save();
				}
				$groupe_id = $produit->groupe()->first()->id;
				$ajout_groupe[$groupe_id] = isset($ajout_groupe[$groupe_id]) ? ($ajout_groupe[$groupe_id] + (int) $qte) : $qte;
			}
			foreach($ajout_groupe as $groupe_id => $qte)
			{
				$stock = Stockgroupe::where_lieu_id($lieu_id)->where_groupe_id($groupe_id)->first();
				$stock->qte_frigo = $stock->qte_frigo + $qte;
				$stock->save();
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
		DB::transaction(function(){
			if(Input::get('nom')) // On ajoute un rôle
			{
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
			}
			else if (Input::get('utilisateur')) // On attribue un rôle
			{
				$utilisateur = Utilisateur::where_login(Input::get('utilisateur'))->first();
				$role = Role::find(Input::get('role'));
				$utilisateur->roles()->attach($role, array('echeance' => Input::get('echeance')));
				LogDB::add_flash('success', array(
					'description' => "Le rôle « $role->nom » a été attribué à « $utilisateur->login ».",
					'nomtable' => 'role',
					'idtable' => $role->id
				));
			}
		});
		return $this->get_roles();
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
	
	public function get_commande()
	{
		return View::make('rf::commande');
	}
}