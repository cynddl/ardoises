<?php

class Lieu extends CustomEloquent {
	
	public function vols()
	{
		return $this->has_many('vol');
	}
}
