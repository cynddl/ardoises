<?php

class LogDB extends CustomEloquent {
	
	public static $table = "log";
	
	public static $accessible = array('utilisateur_id', 'nom', 'nomtable', 'idtable', 'description');

	public function utilisateur()
	{
		return $this->belongs_to('Utilisateur');
	}
	
	public static function add($arr)
	{
		$log = LogDB::create($arr);
		$log->utilisateur_id = Auth::user()->id;
		$log->save();
	}
	
	public static function add_flash($message_status, $arr)
	{
		Session::flash('message_status', $message_status);
		Session::flash('message', $arr['description']);
		LogDB::add($arr);
	}
}
