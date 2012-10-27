<?php

class Stockproduit extends CustomEloquent {

	public function produit()
	{
		return $this->belongs_to('Produit');
	}

	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

}
