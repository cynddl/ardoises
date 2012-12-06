@layout("rf::home")

@section("rf_content")
<h2>Réceptionner la commande n°{{$commande->id}} <small>({{$commande->description}})</small>
</h2>
<p>
   <table class="table table-bordered" id="debit_table">
     <thead>
    	  <tr><th>Produit</th><th>Unités achetées (caisses)</th><th>Unité d'achat (par caisse)</th><th>Prix unitaire</th></tr>
    	</thead>
    	<tbody>
				@foreach($commande->produit()->pivot()->order_by('produit_id')->get() as $row)
				<tr>
					<td>{{Produit::find($row->produit_id)->nom}}</td>
					<td><a class="editable-input" data-name="uniteachete" data-pk="{{$row->id}}">{{$row->uniteachete}}</a></td>
					<td><a class="editable-input" data-name="uda" data-pk="{{$row->id}}">{{$row->uda}}</a></td>
					<td><a class="editable-input" data-name="prixunitaire" data-pk="{{$row->id}}">{{$row->prixunitaire}}</a></td>
				</tr>
				@endforeach
   	</tbody>
  </table>
  <form class="form-horizontal" method="POST" action="">
  	<div class="form-actions">
			<p class="lead">Total : {{$commande->montant()}} €</p>
    	<button type="submit" class="btn btn-primary">Réceptionner la commande</button>
			<a href="../delete/{{$commande->id}}" class="btn btn-danger">Supprimer la commande</a>
   	</div>
	</form>
</P>
@endsection

@section('js')
<script type="text/javascript" charset="utf-8">
  $('a.editable-input').editable({
    type: 'text',
    url: '{{URL::to("rf/commandes/edit")}}',
    title: 'Enter username'
});
</script>
@endsection