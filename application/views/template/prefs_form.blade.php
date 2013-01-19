{{ Former::horizontal_open() }}
{{ Former::populate($user)}}
	<fieldset>
		<legend>Informations principales</legend>
		@if(isset($all))
			{{Former::medium_text('login','Login')}}
			{{Former::xlarge_text('mail','Adresse mail')}}
			{{Former::xlarge_text('nom','Nom')}}
			{{Former::xlarge_text('prenom','Prenom')}}
			{{Former::medium_password('mdp', 'Mot de passe')}}
		@else
			{{Former::medium_uneditable('login','Login')}}	
			{{Former::xlarge_uneditable('mail','Adresse mail')}}
			{{Former::xlarge_uneditable('nom','Nom')}}
			{{Former::xlarge_uneditable('prenom','Prenom')}}
		@endif		
	</fieldset>
	<fieldset>
		<legend>Informations complémentaires</legend>
		@if(isset($all))
			{{Former::small_number('promo','Promo')}}
			{{Former::select('departement_id', 'Département')->fromQuery(Departement::all(), 'nom', 'id')}}
		@else
			{{Former::small_uneditable('promo','Promo')}}
			{{Former::xlarge_uneditable('', 'Département')->value($user->departement()->nom)}}
		@endif
	</fieldset>
	@if(isset($all))
		{{Former::actions(Form::submit('Enregistrer les modifications', array('class'=>'btn btn-primary'))) }}
	@else
		{{Former::actions("N'hésitez pas à contacter un responsable pour une modification de votre profil.")}}
	@endif
{{ Former::close() }}