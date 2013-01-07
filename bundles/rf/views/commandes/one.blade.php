@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Commandes' => URL::to('rf/commandes'), "Commande n°$commande->id"))}}


<h2>Commande n°{{$commande->id}} <small>({{$commande->description}})</small> <span class="badge badge-info">{{$commande->lieu->nom}}</span> <span class="badge badge-info">{{$commande->montant()}} €</span>
</h2>
<p class="lead">
@if($commande->receptionne)
La commande a été réceptionnée (commande créée le {{Date::forge($commande->date)->format('date')}}).
@else
La commande est en cours d'expédition depuis {{Date::forge($commande->date)->ago()}}.
@endif
</p>
<div>
  <table class="table table-bordered">
    <thead>
   	  <tr><th>Produit</th><th>Unités achetées (caisses)</th><th>Unité d'achat (par caisse)</th><th>Prix unitaire</th></tr>
   	</thead>
   	<tbody>
			@foreach($commande->produit()->pivot()->order_by('produit_id')->get() as $row)
			<tr>
				<td>{{Produit::find($row->produit_id)->nom}}</td>
				<td>{{$row->uniteachete}}</td>
				<td>{{$row->uda}}</td>
				<td>{{$row->prixunitaire}}</td>
			</tr>
			@endforeach
  	</tbody>
 </table>
  
</div>
@endsection