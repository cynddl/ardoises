<?php

class Role extends CustomEloquent {

	public static $accessible = array('name', 'description', 'level');

	public function users()
	{
		return $this->has_many('User')->get();
	}

	public function permissions()
	{
		return $this->has_many_and_belongs_to('Permission', 'permission_role');
	}
	
	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

}
