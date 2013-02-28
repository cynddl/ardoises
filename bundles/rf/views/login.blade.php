@layout($layout)

@section('content')
<div class="row first">
	<div class="span6 offset3">
	{{Former::open_horizontal()}}
		{{Former::legend('Connection à la section RF')}}
		{{HTML::flash()}}
		{{Former::large_password('rf_pass','Mot de passe')}}
		{{Former::actions(Form::submit('Accéder à la section RF', array('class'=>'btn btn-primary'))) }}
	{{Former::close()}}
	</div>
</div>
@endsection