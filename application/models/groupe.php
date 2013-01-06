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
	
	public function groupev($lieu_id)
	{
		return $this->has_one('groupev')->where_lieu_id($lieu_id)->order_by('id', 'ASC');
	}
	
	public function prix($lieu_id)
	{
		return $this->groupev($lieu_id)->first()->prix_adh;
	}
}
