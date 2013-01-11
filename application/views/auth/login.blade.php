@layout($layout)

@section("container")
<div class="container-fluid">
	<div class="row-fluid">		
		<div class="span4 offset4 login-box round-box first">
			{{Former::open()}}
				{{Former::text('username', '', null, array('placeholder'=>'Identifiant', 'class'=>'input-block-level'))}}
				{{Former::password('password', '', null, array('placeholder'=>'Mot de passe', 'class'=>'input-block-level'))}}
				{{Form::submit('Connexion', array('class'=>'btn btn-primary')) }}				
			{{Former::close()}}
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4 offset4 login-box round-box">
			{{HTML::flash()}}
			<p class="lead">Je n'ai pas d'ardoise, je note quand même !
				<form>
			<a class="btn btn-primary" href="{{URL::to('anonyme')}}">Anonyme</a></form>
			</p>
		</div>
	</div>
</div>
@endsection