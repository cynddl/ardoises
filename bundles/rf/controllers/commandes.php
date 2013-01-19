<?php

class Rf_Commandes_Controller extends Base_Controller {

	public $restful = true;

	public function get_index()
	{
		return View::make('rf::commandes.index');
	}
	
	public function get_one($id)
	{
		return View::make('rf::commandes.one', array(
			'commande' => Commande::find($id)
		));
	}
	
	public function get_add()
	{
		if(!Auth::can('peuteditercommande')) return Redirect::to('rf/permission');
		return View::make('rf::commandes.add');
	}
	
	public function post_add()
	{
		if(!Auth::can('peuteditercommande')) return Redirect::to('rf/permission');
		$rules = array(
			'fournisseur_id' => 'required',
			'lieu_id' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
		    return Redirect::to('rf/commandes/add')->with_errors($validation)->with_input();
		
		DB::transaction(function () {
			$args = Input::all();
			
			$commande = Commande::create(array(
				'description' => $args['description'],
				'lieu_id' => $args['lieu_id'],
				'fournisseur_id' => $args['fournisseur_id']
			));
			$commande->save();

			foreach($args['produit'] as $produit_id => $qte)
			{
				if ($p = Produit::find($produit_id))
					$commande->produit()->attach($p->id, array(
						'uda'						=> $args['uda'][$produit_id],
						'uniteachete'	=> $qte,
						'prixunitaire'	=> $args['prix'][$produit_id]
					));
			}
						
			LogDB::add_flash('success', array(
				'description' => "La commande n°$commande->id a été ajouté.",
				'nomtable' => 'commande',
				'idtable' => $commande->id
			));
		});
		return Redirect::to('rf/commandes/validate');
	}
	
	public function get_validate()
	{
		return View::make('rf::commandes.validate');
	}
	
	public function get_validate_one($id)
	{		
		return View::make('rf::commandes.validate_one', array(
			'commande' => Commande::find($id)
		));
	}
	
	public function post_validate_one($id)
	{
		if(!Auth::can('peutrecevoircommande')) return Redirect::to('rf/permission');
		
		DB::transaction(function() use ($id) {
			$commande = Commande::find($id);
			foreach ($commande->produit()->pivot()->get() as $row) {
				Stockproduit::modifier($row->produit_id, $commande->lieu_id, $row->uda * $row->uniteachete);
			}
			$commande->receptionne = true;
			$commande->save();
			LogDB::add_flash('success', array(
				'description' => "La commande n°$commande->id a été réceptionnée.",
				'nomtable' => 'commande',
				'idtable' => $commande->id
			));
		});
		return Redirect::to('rf/commandes/validate');
	}
	
	
	
	public function get_delete($id)
	{
		if(!Auth::can('peuteditercommande')) return Redirect::to('rf/permission');
		try {
			$commande = Commande::find($id);
			$commande_description = $commande->description; $commande_id = $commande->id;
			$commande->delete();
			LogDB::add_flash('success', array(
				'description' => "La commande n°$commande_id a été supprimée.",
				'nomtable' => 'commande',
				'idtable' => $commande_id
			));	
		} catch (\Exception $e) {
			Session::flash('message_status', 'warning');
			Session::flash('message', 'Impossible de supprimer la commande.');
		}
		return Redirect::to('rf/commandes/validate');
	}
	
	public function post_edit()
	{
		if(!Auth::can('peuteditercommande')) return Redirect::to('rf/permission');
	  if (Request::ajax())
	  {
			$args = Input::all();
			
			try {
				$p_c = ProduitCommande::find($args['pk']);
				$p_c->fill(array($args['name'] => (float) $args['value']));
				$p_c->save();
			} catch (Exception $e) {
				return "Impossible d'enregistrer la valeur";
			}
			return true;
		}
	}
}
