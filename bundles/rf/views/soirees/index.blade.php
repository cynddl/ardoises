@layout("rf::home")

@section("rf_content")
<h2>Liste des dernières soirées</h2>
<p>
<table class="table table-bordered dt-table">
	<thead>
	  <tr><th>Nom</th><th>Description</th><th>Date</th><th>Montant (€)</th></tr>
	</thead>
	<tbody>
@foreach(Soiree::where_valide(1)->get() as $s)
    <tr><td><a href="{{URL::to('rf/soirees/') . $s->id}}">{{$s->nom}}</a></td><td>{{$s->description}}</td><td>{{$s->date}}</td><td>{{$s->montant()}}</td></tr>
@endforeach
	</tbody>
</table>
</p>
@endsection