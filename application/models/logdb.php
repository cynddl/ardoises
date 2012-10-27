<?php

class LogDB extends CustomEloquent {
	
	public static $table = "log";

	public function utilisateur()
	{
		//return Utilisateur::find($this->utilisateur_id);
		return $this->belongs_to('Utilisateur');
	}

}
