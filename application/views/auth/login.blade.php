@layout($layout)

@section("container")
<div class="container">
	<div class="row">
		<div class="span3 offset4 login-box">
			{{Former::open()}}
				{{Former::xlarge_text('username', '', null, array('placeholder'=>'Identifiant'))}}
				{{Former::xlarge_password('password', '', null, array('placeholder'=>'Mot de passe'))}}
				{{Form::submit('Connexion', array('class'=>'btn btn-primary')) }}				
			{{Former::close()}}
		</div>
	</div>
</div>
@endsection