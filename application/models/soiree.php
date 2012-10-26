<?php

class Soiree extends CustomEloquent {

	public function ardoises()
	{
		return $this->has_many_and_belongs_to('Ardoise');
	}
	
	public function montant()
	{
		return $this->ardoises()->pivot()->sum('prix');
	}

}
