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
		return View::make('home.index');
	}
	
	public function post_index()
	{
		print_r(Input::get());die();
	}
	
	public function get_prefs()
	{
		$dep_options = array();
		foreach (Departement::get() as $key => $value)
		{
			$dep_options[$key] = $value->nom;
		}
		
		return View::make('home.prefs', array(
			'departements' => $dep_options
		));
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