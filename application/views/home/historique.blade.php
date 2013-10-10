@layout($layout)

@section("content")
<div class="row first">
	<div class="span8 offset2">
	<h2>Historique des consommations</h2>
	
	<table class="table table-striped table-bordered dt-table">
		<thead>
		<tr>
			<th>Date</th><th>Nom</th><th>Quantité</th><th>Prix total</th><th>Lieu</th>
		</tr>
		</thead>
		<tbody>
		@foreach($consos as $c)
		<tr>
			<td>{{Date::forge($c->date)->format('datetime')}}</td>
			<td>{{$c->groupe->nom}}</td>
			<td>{{$c->uniteachetee}}</td>
			<td>{{(float) $c->groupeV()->prix_adh * (float) $c->uniteachetee}}</td>
			<td>{{$c->groupeV()->lieu->nom}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
</div>
@endsection
