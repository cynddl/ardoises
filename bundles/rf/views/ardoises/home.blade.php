@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Ardoises'))}}

<h2>Liste des utilisateurs poss&eacute;dant une ardoise</h2>
<div class="well">
	<div class="btn-group">
	  <a class="btn" href="pdf/ardoises">
	    Télécharger la liste des ardoises
	  </a>
	</div>
	<div class="btn-group">
	  <a class="btn btn-primary" href="pdf/ardoises_negatives">
	    Télécharger la liste des ardoises négatives
		</a>
	</div>
</div>
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