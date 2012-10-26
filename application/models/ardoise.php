<?php

class Ardoise extends CustomEloquent {

	public function soirees()
	{
		return $this->has_and_belongs_to_many('Soiree');
	}
	
	public function consommations () {
		return $this->has_many('Consommation')->get();
	}
	
	public function utilisateur()
	{
		return $this->has_one('Utilisateur')->first();
	}
	
	// hack
	public function results()
	{
		return parent::get();
	}

}
