<?php

class Consommation extends Eloquent {

	public function groupeV()
	{
		return GroupeV::find($this->groupev_id);
	}
	
	public function groupe ()
	{
		return $this->groupeV()->groupe();
	}

	public function ardoise()
	{
		return $this->belongs_to('Ardoise');
	}

}
