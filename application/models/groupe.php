<?php

class Groupe extends CustomEloquent {

	public function produits()
	{
		return $this->has_many_and_belongs_to('Produit', 'produit_groupe');
	}
	
	public function groupev()
	{
		return $this->has_one('groupev')->order_by('id', 'ASC');
	}
}
