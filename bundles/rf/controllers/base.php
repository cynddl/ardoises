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
		
		return View::make('rf::ardoises.one', array(
			'user' => Utilisateur::where('login', '=', $login)->first(),
		));
	}
	
	/* Crédit d'une ardoise */
	public function post_credit($login)
	{
		$user = Utilisateur::where('login', '=', $login)->first();
		$ardoise = $user->ardoise();
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
		return Redirect::to_action('rf::ardoises@edit.'.$login);
	}
	
	/* Formulaire d'ajout d'une ardoise */
	public function get_add()
	{
		return View::make('rf::ardoises.add');
	}
	
	/* Ajout d'une ardoise */
	public function post_add()
	{
		$rules = array(
			'mail' => 'required|email',
			'prenom' => 'required',
			'nom' => 'required',
			'login' => 'required|unique:utilisateur,login',
			'promo' => 'required',
			'departement_id' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		
		if ($validation->fails())
		{			
			Former::withErrors($validation, $populate = true);
			return View::make('rf::ardoises.add');
		}
		$ardoise = Ardoise::create(array('montant'=>'0'));
		$ardoise->save();
		$user_vars = Input::all();
		$user_vars['ardoise_id'] = $ardoise->id;
		$user = Utilisateur::create($user_vars);
		return Redirect::to('rf/ardoises');
	}
	
	
	/**** Transfert entre ardoises ****/
	
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
			Session::flash('message_status', 'success');
			Session::flash('message',
				'Transfert effectuée : '.$debiteur->login.' ('.$debiteur_a->montant.') > '.$crediteur->login.' ('.$crediteur_a->montant.').' );
		});
		return View::make('rf::ardoises.transfert');
	}
	
	

	public function post_add_vol()
	{
		$produit_nom = Input::get('produit_nom');
		$qte_volee = Input::get('qte_volee');
		$lieu_id = Input::get('lieu_id');
		
		DB::transaction(function() use ($produit_nom, $qte_volee, $lieu_id) {
			$produit = Produit::where_nom($produit_nom)->first();
			$groupe = $produit->groupe();

			$vol = Vol::create(array(
				'produit_id' => $produit->id,
				'qte_volee' => $qte_volee,
				'lieu_id' => $lieu_id
			));
			$vol->save();
		
			$sg = Stockgroupe::where_lieu_id($lieu_id)->where_groupe_id($groupe->id)->first();
			$sg->qte_frigo = $sg->qte_frigo - $qte_volee;
			$sg->save();
		});
		return Response::json(array('saved'=>true));
	}
}