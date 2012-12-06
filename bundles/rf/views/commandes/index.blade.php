@layout("rf::home")

@section("rf_content")
<h2>Liste des dernières commandes</h2>
<p>
<table class="table table-bordered dt-table">
	<thead>
	  <tr><th>Numéro</th><th>Lieu</th><th>Description</th><th>Date</th><th>Total</th></tr>
	</thead>
	<tbody>
@foreach(Commande::get() as $c)
    <tr>
			<td><a href="commandes/{{$c->id}}">{{$c->id}}</a></td>
			<td>{{$c->lieu->nom}}</td>
			<td>{{$c->description}}</td><td>{{$c->date}}</td>
			<td>{{$c->montant()}} €</td>
		</tr>
@endforeach
	</tbody>
</table>
	
</table>
</p>
@endsection