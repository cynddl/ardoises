@layout("rf::home")

@section("rf_content")
<h2>Liste des utilisateurs poss&eacute;dant une ardoise</h2>
<p>
<table class="table table-bordered dt-table">
	<thead>
		<tr><th>Login</th><th>Nom complet</th><th>Ardoise</th><th>Courriel</th></tr>
	</thead>
	<tbody>
		@foreach(Utilisateur::with(array('ardoise'))->get() as $item)
			<tr>
				<td>{{HTML::link_to_action('rf::ardoises@edit', $item->login, array($item->login))}}</td>
				<td>{{$item->prenom}} {{$item->nom}}</td>
				<td>{{$item->ardoise->montant}}</td>
				<td>{{$item->mail}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
</p>
@endsection