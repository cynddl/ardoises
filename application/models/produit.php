<?php

class Produit extends CustomEloquent {

	public function groupe() // bon, on ne va pas mettre un produit dans plusieurs groupes !
	{
		return $this->has_many_and_belongs_to('Groupe', 'produit_groupe')->first();
	}

}
