{{ Former::horizontal_open() }}
{{ Former::populate($user)}}
	<fieldset>
		<legend>Informations principales</legend>
		@if(isset($all))
			{{Former::medium_text('login','Login')}}
		@else
			{{Former::medium_uneditable('login','Login')}}
		@endif
		@if(isset($mdp))
			{{Former::medium_password('mdp', 'Mot de passe')}}
		@endif
		{{Former::xlarge_text('mail','Adresse mail')}}
		{{Former::xlarge_text('nom','Nom')}}
		{{Former::xlarge_text('prenom','Prenom')}}
	</fieldset>
	<fieldset>
		<legend>Informations complémentaires</legend>
		@if(isset($all))
		{{Former::small_number('promo','Promo')}}
		{{Former::select('departement_id', 'Département')->fromQuery(Departement::all(), 'nom', 'id')}}
		@else
		{{Former::small_uneditable('promo','Promo')}}
		{{Former::select('departement_id', 'Département')->fromQuery(Departement::all(), 'nom', 'id')}}
		@endif
	</fieldset>
	{{Former::actions(Form::submit('Enregistrer les modifications', array('class'=>'btn btn-primary'))) }}
{{ Former::close() }}