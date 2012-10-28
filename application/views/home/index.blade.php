@layout($layout)

@section("content")
<div class="row first">
	<section class="mainbox span6 offset3">
		<h2>Débiter des consommations</h2>
		<form class="form-inline" name="form_debit" action="{{URL::to('/')}}" method="POST">
			<fieldset class="well">
				{{Former::select('conso1', 'Conso')->fromQuery(Groupe::all(), 'nom', 'id')}}
				{{Former::small_number('count1', 'Quantité')->value(1)->min(0)->max(5)}}
			</fieldset>
			<button type="submit" class="btn btn-primary">Débiter</button>
		</form>
	</section>
</div>
<div class="row tooltip grid_3">
	<p>Appuyez sur TAB et MAJ+TAB pour vous déplacer entre les champs, puis HAUT et BAS dans chaque champ. Une nouvelle conso s'ajoute au besoin.</p>
</div>
@endsection
