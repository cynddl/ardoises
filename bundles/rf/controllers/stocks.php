<?php

class Rf_Stocks_Controller extends Base_Controller {
	
	public $restful = true;

	public function post_groupev_edit()
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

	public function post_groupe_edit()
	{	
		$groupe = Groupe::find(Input::get('pk'));
		$key = Input::get('name');
		
		if ($key == 'commentaire' or $key == 'nomreduit')
		{
			$groupe->fill(array($key => Input::get('value')));
			$groupe->save();
			return true;
		}
		return "Impossible de modifier.";
	}
	
	public function post_produit_edit()
	{	
		$produit = Produit::find(Input::get('pk'));
		$key = Input::get('name');
		
		if ($key == 'commentaire' or $key == 'nom')
		{
			$produit->fill(array($key => Input::get('value')));
			$produit->save();
			return true;
		}
		return "Impossible de modifier.";
	}
	
	public function get_groupe($id)
	{
		return View::make('rf::stocks.groupe', array(
			'groupe' => Groupe::find($id),
			'lieux' => Lieu::get()
		));
	}
	
	public function post_groupe($id)
	{		
		GroupeV::create(array(
			'lieu_id' => Input::get('lieu_id'),
			'groupe_id' => $id,
			'prix_adh' => Input::get('prix_adh'),
			'prix_non_adh' => Input::get('prix_non_adh'),
			'udv' => Input::get('udv'),
			'actif' => Input::get('actif'),
			'disponible' => Input::get('disponible'),
		))->save();
		
		return Redirect::to('rf/stocks/groupe/'.$id);
	}
}