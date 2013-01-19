<?php

class Rf_Soirees_Controller extends Base_Controller {

	public $restful = true;

	public function get_index($id = null)
	{
		return View::make('rf::soirees.index');
	}
	
	public function get_one($id)
	{
		return View::make('rf::soirees.one', array(
			'soiree' => Soiree::find($id)
		));
	}
	
	public function get_add()
	{
		return View::make('rf::soirees.add');
	}
	
	public function post_add()
	{
		$rules = array(
			'nom' => 'required',
			'date' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails())
		    return Redirect::to('rf/soirees/add')->with_errors($validation)->with_input();
		
		DB::transaction(function () {
			$args = Input::all();
			
			$soiree = Soiree::create(array(
				'nom' => $args['nom'],
				'description' => $args['description'],
				'date' => $args['date']
			));
			$soiree->save();
			
			unset($args['nom']);
			unset($args['description']);
			unset($args['date']);
			unset($args['debit_defaut']);
			
			foreach($args as $key => $value)
			{
				if ($u = Utilisateur::where_login($key)->first())
					$soiree->ardoises()->attach($u->ardoise, array('prix'=>$value));
			}
			
			LogDB::add_flash('success', array(
				'description' => "La soirée « $soiree->nom » a été ajouté.",
				'nomtable' => 'soiree',
				'idtable' => $soiree->id
			));
		});
		return Redirect::to('rf/soirees/validate');
	}
	
	public function get_validate()
	{
		return View::make('rf::soirees.validate');
	}
	
	public function get_validate_one($id)
	{
		return View::make('rf::soirees.validate_one', array(
			'soiree' => Soiree::find($id)
		));
	}
	
	public function post_validate_one($id)
	{
		DB::transaction(function() use ($id) {
			$soiree = Soiree::find($id);
			foreach ($soiree->ardoises()->pivot()->get() as $row) {
				$ardoise = Ardoise::find($row->ardoise_id);
				$ardoise->montant = $ardoise->montant + $row->prix; // /!\
				$ardoise->save();
			}
			$soiree->valide = true;
			$soiree->save();
			LogDB::add_flash('success', array(
				'description' => "La soirée « $soiree->nom » a été validée.",
				'nomtable' => 'soiree',
				'idtable' => $soiree->id
			));
		});
		return Redirect::to('rf/soirees/validate');
	}
	
	
	
	public function get_delete($id)
	{
		try {
			$soiree = Soiree::find($id);
			$soiree_nom = $soiree->nom; $soiree_id = $soiree->id;
			$soiree->delete();
			LogDB::add_flash('success', array(
				'description' => "La soirée « $soiree->nom » a été supprimée.",
				'nomtable' => 'soiree',
				'idtable' => $soiree_id
			));	
			return Redirect::to('rf/soirees/validate');
		} catch (\Exception $e) {
			Session::flash('message_status', 'warning');
			Session::flash('message', 'Impossible de supprimer la soirée.');
			return Redirect::to('rf/soirees/validate');
		}	
	}
}
