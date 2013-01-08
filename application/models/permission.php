<?php

class Permission extends CustomEloquent
{
	
	public static $accessible = array('nom', 'description');

	public function roles()
	{
		return $this->has_many_and_belongs_to('Role', 'permission_role')->get();
	}
	
	public function __toString()
	{
		return $this->nom;
	}

}
