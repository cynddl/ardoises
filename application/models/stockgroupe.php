<?php

class Stockgroupe extends CustomEloquent {

	public function groupe()
	{
		return $this->belongs_to('Groupe')->first();
	}

	public function lieu()
	{
		return $this->belongs_to('Lieu')->first();
	}

}
