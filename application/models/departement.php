<?php

class Departement extends CustomEloquent {
	
	public function __toString()
	{
		return $this->nom;
	}

}
