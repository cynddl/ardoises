<?php

class Vol extends CustomEloquent {

	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

	public function produit()
	{
		return $this->belongs_to('Produit');
	}
	
	public function temps_ecoule()
	{
		$date_1 = new DateTime($this->date);
		$date_2 = new DateTime("now");
		return $date_1->diff($date_2);
	}

}
