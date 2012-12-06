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

	public static function modifier($produit_id, $lieu_id, $qte)
	{
		if(Stockproduit::where_produit_id($produit_id)->where_lieu_id($lieu_id)->count() > 0)
		{
			$sp = Stockproduit::where_produit_id($produit_id)->where_lieu_id($lieu_id)->first();
			$sp->qte_reserve = $sp->qte_reserve + $qte;
			$sp->save();
		}
		else {
			Stockproduit::create(array(
				'produit_id' => $produit_id,
				'lieu_id' => $lieu_id,
				'qte_reserve' => $qte
			))->save();
		}
	}
}
