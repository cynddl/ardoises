@layout($layout)

@section("content")
<div class="row first">
	<section class="span6 offset3">
		<h2>Débiter des consommations <small>#{{$lieu->nom}}</small></h2>
		<form class="form-inline" name="form_debit" action="{{URL::to('/')}}" method="POST">
			<fieldset class="well">
				{{Former::select('conso[]', 'Conso')->fromQuery($groupe, 'nom', 'id')}}
				{{Former::small_number('count[]', 'Quantité')->value(1)->min(0)->max(5)}}
				{{Form::hidden('lieu_id', $lieu->id)}}
			</fieldset>
			<button type="submit" class="btn btn-primary">Débiter</button>
		</form>
	</section>
	<section class="span3">
		<h4>Avec le clavier</h4>
		<p>Déplacements : <span class="key">Tab</span> et <span class="key">&#8679;</span>+<span class="key">Tab</span>.</p>
		<p>Modification : <span class="key">&uarr;</span> et <span class="key">&darr;</span> : vous pouvez ainsi ajouter plusieurs consos.</p>
	</section>
</div>

@endsection
