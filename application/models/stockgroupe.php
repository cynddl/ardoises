<?php

class Stockgroupe extends CustomEloquent {

	public function groupe()
	{
		return $this->belongs_to('Groupe');
	}

	public function lieu()
	{
		return $this->belongs_to('Lieu');
	}
	
	public function groupev()
	{
		return $this->groupe->groupev->where_lieu_lieu($this->lieu->id);
	}
	
	public static function modifier($groupe_id, $lieu_id, $qte)
	{
		if(Stockgroupe::where_groupe_id($groupe_id)->where_lieu_id($lieu_id)->count() > 0)
		{
			$sg = Stockgroupe::where_groupe_id($groupe_id)->where_lieu_id($lieu_id)->first();
			$sg->qte_frigo = $sg->qte_frigo + $qte;
			$sg->save();
		}
		else {
			Stockgroupe::create(array(
				'groupe_id' => $groupe_id,
				'lieu_id' => $lieu_id,
				'qte_frigo' => $qte
			))->save();
		}
	}

}
