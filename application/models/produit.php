<?php

class Produit extends CustomEloquent {
	
	public static $accessible = array('nom', 'dernierprix', 'commentaire');

	public function groupe() // bon, on ne va pas mettre un produit dans plusieurs groupes !
	{
		return $this->has_many_and_belongs_to('Groupe', 'produit_groupe');
	}
	
	public function stockproduit()
	{
		return $this->has_one('stockproduit');
	}

}
