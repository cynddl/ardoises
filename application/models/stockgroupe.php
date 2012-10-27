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

}
