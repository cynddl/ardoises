<?php

class Groupe extends CustomEloquent {

	public static $accessible = array('nom', 'nomreduit', 'commentaire');

	public function produits()
	{
		return $this->has_many_and_belongs_to('Produit', 'produit_groupe');
	}
	
	public function stockgroupe()
	{
		return $this->has_one('stockgroupe');
	}
	
	public function groupev()
	{
		return $this->has_one('groupev')->order_by('id', 'ASC');
	}
}
