<?php

class Home_Controller extends Base_Controller {

	public $restful = true;
	
	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'auth')->except(array('login'));
	}

	public function get_index()
	{
		$lieu = Lieu::first();
		return View::make('home.index', array(
			'lieu' => $lieu,
			'groupe' => array_map(function($s) use($lieu) { return array(
				'id'=>$s->groupe->id,
				'nom'=>$s->groupe->nom . ' ('. $s->groupe->prix($lieu->id) .')'
			); }, Stockgroupe::with('groupe')->where_lieu_id($lieu->id)->get())
		));
	}
	
	public function post_index()
	{
		$inputs = Input::get();
		$l_id = Input::get('lieu_id');
		$ardoise = Auth::user()->ardoise;
		
		for ($i=1; $i < count($inputs) / 2 + 1; $i++) {
			if(!isset($inputs['conso'.$i]) || !isset($inputs['count'.$i]))
				continue;			
			
			DB::transaction(function() use($i, $inputs, $ardoise, $l_id) {
				$groupe = Groupe::find($inputs['conso'.$i]);
				$qte = $inputs['count'.$i];
				$conso = Consommation::create(array(
					'groupeV_id' => $groupe->groupev($l_id)->first()->id,
					'uniteachetee' => $qte,
					'ardoise_id' => Auth::user()->ardoise->id
				));
				$conso->save();
				
				Stockgroupe::modifier($groupe->id, $l_id, -$qte);
				
				$ardoise->montant = $ardoise->montant + $groupe->prix($l_id) * $qte;
				$ardoise->save();
			});
		}
		return $this->get_index();
	}
	
	public function get_prefs()
	{
		$dep_options = array();

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
}