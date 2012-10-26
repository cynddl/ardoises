@layout("rf::home")

@section("rf_content")
<h2>Soirées à valider</h2>
<p>
<table class="table table-bordered">
	<thead>
	  <tr><th>Nom</th><th>Description</th><th>Date</th><th>Total</th></tr>
	</thead>
	<tbody>
@foreach(Soiree::where_valide(0)->get() as $s)
    <tr><td><a href="validate/{{$s->id}}">{{$s->nom}}</a> <a href="delete/{{$s->id}}" class="label label-important">Supprimer</a></td><td>{{$s->description}}</td><td>{{$s->date}}</td><td>{{$s->montant()}}</td></tr>
@endforeach
	</tbody>
</table>
</p>
@endsection