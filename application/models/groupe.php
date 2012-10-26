<?php

class Groupe extends CustomEloquent {

	public function produits()
	{
		return $this->has_many_and_belongs_to('Produit', 'produit_groupe');
	}
}
