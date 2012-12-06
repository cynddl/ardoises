<?php

class Commande extends Eloquent {

	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}

	public function fournisseur()
	{
		return $this->belongs_to('Fournisseur');
	}

	public function produit_commandes()
	{
		return $this->has_many('Produit_Commande');
	}
	
	public function produit()
	{
		return $this->has_many_and_belongs_to('Produit', 'produit_commande');
	}
	
	public function montant()
	{
		$count = 0;
		foreach($this->produit()->pivot()->get() as $row)
		{
			$count += (float) $row->prixunitaire * $row->uda * $row->uniteachete;
		}
		return $count;
	}
}
