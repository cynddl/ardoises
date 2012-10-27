<?php

class GroupeV extends Eloquent {
	public static $table = 'groupeV';

	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

	public function groupe()
	{
		return $this->belongs_to('Groupe');
	}

	public function consommations()
	{
		return $this->has_many('Consommation')->get();
	}

}
