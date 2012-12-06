<?php

class ProduitCommande extends Eloquent {

	public static $table = 'produit_commande';

	public function produit()
	{
		return $this->has_one('Produit');
	}

	public function commande()
	{
		return $this->has_one('Commande');
	}

}
