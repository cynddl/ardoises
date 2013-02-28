<?php

class Role extends CustomEloquent {

	public static $accessible = array('name', 'description', 'level');

	public function permissions()
	{
		return $this->has_many_and_belongs_to('Permission', 'permission_role');
	}
	
	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

	public function utilisateur()
	{
		return $this->has_many_and_belongs_to('Utilisateur', 'utilisateur_role')->with('id');
	}

}
