@layout("rf::home")

@section("rf_content")
<h2>Valider la soirée {{$soiree->nom}} <small>({{$soiree->description}})</small>
</h2>
<p>
   <table class="table table-bordered" id="debit_table">
     <thead>
    	  <tr><th>Ardoise</th><th>Montant</th></tr>
    	</thead>
    	<tbody>
				@foreach($soiree->ardoises()->pivot()->get() as $item)
        <tr><td>{{Ardoise::find($item->ardoise_id)->utilisateur->login}}</td><td>{{$item->prix}} €</td></tr>
				@endforeach
   	</tbody>
  </table>
  <form class="form-horizontal" method="POST" action="">
  	<div class="form-actions">
			<p class="lead">Total : {{$soiree->montant()}} €</p>
    	<button type="submit" class="btn btn-primary">Valider la soirée</button>
			<a href="../delete/{{$soiree->id}}" class="btn btn-danger">Supprimer la soirée</a>
   	</div>
	</form>
</P>
@endsection