<?php

class Rf_Ardoises_Controller extends Base_Controller {
	
	public $restful = true;

	public function get_index()
	{
		return View::make('rf::ardoises.home');
	}

	public function get_edit($login)
	{
		return View::make('rf::ardoises.one', array(
			'user' => Utilisateur::where('login', '=', $login)->first(),
		));
	}
	
	//
	// Modification des préférences d'un utilisateur
	//
	public function post_edit($login)
	{
		if(!Auth::can('peutediterardoise')) return Redirect::to('rf/permission');
			
		$user = Utilisateur::where('login', '=', $login)->first();
		$inputs = Input::all();
		
		$user->fill($inputs);
		if(!$user->is_valid())
			return Redirect::to('rf/ardoises/edit/'.$user->login.'#edition')->with_input()->with_errors($user->validation);
		
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
	
	//
	// Crédit d'une ardoise
	//
	public function post_credit($login)
	{
		if(!Auth::can('peutcrediter')) return Redirect::to('rf/permission');
		
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
		if(!Auth::can('peutcreerardoise')) return Redirect::to('rf/permission');
		return View::make('rf::ardoises.add');	
	}
	
	public function post_add()
	{
		if(!Auth::can('peutcreerardoise')) return Redirect::to('rf/permission');

		$validation = Validator::make(Input::all(), Utilisateur::$rules);
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
		if(!Auth::can('peutcrediter')) return Redirect::to('rf/permission');
		return View::make('rf::ardoises.transfert');
	}
	
	public function post_transfert ()
	{
		if(!Auth::can('peutcrediter')) return Redirect::to('rf/permission');
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

}