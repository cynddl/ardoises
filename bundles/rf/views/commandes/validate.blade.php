@layout("rf::home")

@section("rf_content")
<h2>Commandes à réceptionner</h2>
<p>
<table class="table table-bordered">
	<thead>
	  <tr><th></th><th>Numéro</th><th>Lieu</th><th>Description</th><th>Date</th><th>Total</th></tr>
	</thead>
	<tbody>
@foreach(Commande::where_receptionne(0)->get() as $c)
    <tr>
			<td>
				<a href="delete/{{$c->id}}" class="label label-important">Supprimer</a>
				<a href="validate/{{$c->id}}" class="label label-warning">Réceptionner</a>
			</td>
			<td>{{$c->id}}</td>
			<td>{{$c->lieu->nom}}</td>
			<td>{{$c->description}}</td><td>{{$c->date}}</td>
			<td>{{$c->montant()}} €</td>
		</tr>
@endforeach
	</tbody>
</table>
</p>
@endsection