@layout("rf::home")

@section("rf_content")
<h2>Passer une commande</h2>
<p>
	{{Former::open()}}
    <fieldset>
			{{Former::select('lieu_id', 'Lieu')->fromQuery(Lieu::all(), 'nom', 'id')}}
			{{Former::select('fournisseur_id', 'Fournisseur')->fromQuery(Fournisseur::all(), 'nom', 'id')}}			
			{{Former::large_text('description', 'Description')}}
    </fieldset>
    <div class="form-actions">
			<select id="produit_id">
				@foreach(Produit::all() as $p)
				<option id="produit_id" value="{{$p->id}}">{{$p->nom}}</option>
				@endforeach
			</select>
      <input class="input-small" id="qte" type="number" step="any" min="0" value="1">
      <button class="btn" id="add_row" type="button">Ajouter</button>
    </div>
    <fieldset>
      <table class="table table-bordered" id="commande_table">
        <thead>
      	  <tr>
						<th>Produit</th>
						<th><a href="#" rel="tooltip" data-original-title="Unités achetées">Nombre de caisses</a></th>
						<th><a href="#" rel="tooltip" data-original-title="Unité d'achat">Produits par caisse</a></th>
						<th>Prix unitaire</a></th>
					</tr>
      	</thead>
      	<tbody>
      	</tbody>
      </table>
    </fieldset>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Enregistrer la commande</button>
    </div>
		{{Former::close()}}
</p>
@endsection

@section("js")
<script type="text/javascript" charset="utf-8">
	$("[rel=tooltip]").tooltip();

  $("form").keypress(function(e) {
    if (e.which == 13) {
      return false;
    }
  });

  var mySource = [@foreach(Utilisateur::all() as $u)'{{$u->login}}',@endforeach];
  $('input.typeahead').typeahead({ 'source': mySource });
  
  $('#add_row').click(function(){
    var produit_id = $('#produit_id')[0].value;
		var produit_nom = $('#produit_id option:selected').html();
    var qte = $('#qte')[0].value;

    var selector = $('#commande_table input[name="produit['+produit_id+']"]');
    console.log(selector.length);
    if(selector.length > 0) {
      selector[0].value = parseFloat(selector[0].value) + parseFloat(qte);
    }
    else {
      $('#commande_table > tbody:last').append('<tr> \
				<td>'+produit_nom+'</td> \
				<td><input class="input-small" type="number" step="any" min="0" name="produit['+produit_id+']" value="'+qte+'"></td> \
				<td><input class="input-small" type="number" step="any" min="0" name="uda['+produit_id+']" value="24"></td> \
				<td><div class="input-append"><input class="input-small" type="number" step="any" min="0" name="prix['+produit_id+']" value="1.50"><span class="add-on">€</span></div></td> \
			</tr>');
    }
      
  })
</script>
@endsection