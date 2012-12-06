<?php

class Rf_Stocks_Controller extends Base_Controller {
	
	public $restful = true;

	public function post_groupe_edit()
	{	
		$groupe_v = GroupeV::find(Input::get('name'));
		$key = Input::get('pk');
		
		if ($key == 'actif' or $key == 'disponible')
		{
			$groupe_v->fill(array($key => Input::get('value')));
			$groupe_v->save();
			return true;
		}
		return "Impossible de modifier.";
	}
}