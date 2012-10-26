<?php


class Authentic extends \Laravel\Auth\Drivers\Driver
{
	public function __construct()
	{
		parent::__construct();

		// Populate the user variable
		$this->user();
	}
	
	public function retrieve($id)
	{
		if (filter_var($id, FILTER_VALIDATE_INT) !== false)
			return Utilisateur::find($id);
	}
	
	public function attempt($arguments = array())
	{
		$user = Utilisateur::where_login($arguments['username'])
			->where_mdp(md5($arguments['password']))
			->first();
		if (! is_null($user) )
			return $this->login($user->id, array_get($arguments, 'remember'));
		
		return false;
	}
	
	public function can($permissions)
	{
		return $this->user->can($permissions);
	}
}