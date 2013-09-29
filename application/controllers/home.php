<?php

class Home_Controller extends Base_Controller {

	public $restful = true;
	
	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'auth')->except(array('login', 'anonyme'));
	}

	public function get_index()
	{
		$lieu = Lieu::first();

		return View::make('home.index', array(
			'lieu' => $lieu,
			'groupe' =>
				array_map(function($s) use($lieu) { return array(
				'id'=>$s->groupe->id,
				'nom'=>$s->groupe->nom . ' ('. $s->groupe->prix($lieu->id) .')'
			); }, GroupeV::with('groupe')->where_actif(true)->where_disponible(true)->where_lieu_id($lieu->id)->order_by('prix_adh')->get())
		));
	}
	
	public function post_index()
	{
		$inputs = Input::get();
		$l_id = Input::get('lieu_id');
		$ardoise = Auth::user()->ardoise;
		$error = false;
						
		for ($i=0; $i < count($inputs['conso']) ; $i++) {

			DB::transaction(function() use($i, $inputs, $ardoise, $l_id, &$error) {
				$groupe = Groupe::find($inputs['conso'][$i]);
				$qte = $inputs['count'][$i];
				if($qte > 0) {
					$conso = Consommation::create(array(
						'groupeV_id' => $groupe->groupev($l_id)->first()->id,
						'uniteachetee' => $qte,
						'ardoise_id' => Auth::user()->ardoise->id
					));
					$conso->save();
					
					Stockgroupe::modifier($groupe->id, $l_id, -$qte);
					
					$ardoise->montant = $ardoise->montant + $groupe->prix($l_id) * $qte;
					$ardoise->save();
				} else {
					Session::flash('message_status', 'error');
					Session::flash('message', 'Veuillez entrer une quantité positive.');
					$error = true;
				}
			});
		}

		if($error)
			return Redirect::to('/');
		else
			return Redirect::to('/debit');
	}
	
	public function get_anonyme()
	{		
		$lieu = Lieu::first();
		return View::make('home.anonyme', array(
			'lieu' => $lieu,
			'groupe' =>
				array_map(function($s) use($lieu) { return array(
				'id'=>$s->groupe->id,
				'nom'=>$s->groupe->nom,
				'nomreduit'=>$s->groupe->nomreduit,
				'prix' => money_format('%!n €', $s->groupe->prix($lieu->id))
			); }, GroupeV::with('groupe')->where_actif(true)->where_disponible(true)->where_lieu_id($lieu->id)->order_by('prix_adh')->get())
		));
	}
	
	public function post_anonyme()
	{
		$rules = array(
			'conso' => 'required|exists:groupe,nomreduit',
			'lieu_id' => 'required|exists:lieu,id'
		);
				
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
		{
			Session::flash('message_status', 'error');
			Session::flash('message', 'Impossible d\'enregister cette consommation !');
			return Redirect::to('/anonyme')->with_errors($validation);
		}
		
		try {
			DB::transaction(function() {
				$l_id = Input::get('lieu_id');
				$groupe = Groupe::where_nomreduit(Input::get('conso'))->first();
				// Pas d'ardoise pour un anonyme
				$conso = Consommation::create(array(
					'groupeV_id' => $groupe->groupev($l_id)->first()->id,
					'uniteachetee' => 1
				));
				$conso->save();
				Stockgroupe::modifier($groupe->id, $l_id, -1);
			});
			Session::flash('message_status', 'success');
			Session::flash('message', 'Consommation enregistrée !');
			return Redirect::to('/login');
		} catch (Exception $e) {
			Session::flash('message_status', 'error');
			Session::flash('message', 'Impossible d\'enregister cette consommation !');
			return Redirect::to('/anonyme');
		}
	}
	
	
	public function get_login()
	{
		return View::make('auth.login');
	}
	
	public function post_login()
	{
		$rules = array(
		'username' => 'required',
		'password' => 'required'
		);
		
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
			return Redirect::to('/login')->with_errors($validation)->with_input();
		
		return Auth::attempt(Input::all()) ? Redirect::to('/') : Redirect::to('/login')->with_input();
	}
	
	public function get_logout()
	{
		Auth::logout();
		return Redirect::to('/login');
	}
	
	
	public function get_prefs()
	{
		return View::make('home.prefs');
	}
	
	public function post_prefs()
	{
		$rules = array(
			'mail' => 'required|email',
			'prenom' => 'required',
			'nom' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		
		if ($validation->fails())
		    return Redirect::to('prefs')->with_errors($validation)->with_inputs();
		
		Auth::user()->mail = Input::get('mail');
		Auth::user()->prenom = Input::get('prenom');
		Auth::user()->nom = Input::get('nom');
		Auth::user()->save();

		return Redirect::to('prefs');
	}
	
	public function get_historique()
	{
		return View::make('home.historique', array(
			'consos' => Consommation::where_ardoise_id(Auth::user()->ardoise_id)->order_by('date', 'DESC')->get()
		));
	}
	
	public function get_debit()
	{
		return View::make('home.debit');
	}
}