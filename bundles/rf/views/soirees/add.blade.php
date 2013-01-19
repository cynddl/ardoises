@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Soirées' => URL::to('rf/soirees'), 'Ajouter une soirée'))}}

<h2>Ajouter une soirée</h2>
<p>
	{{Former::open()}}
    <fieldset>
			{{Former::large_text('nom', 'Nom de la soirée')}}
			{{Former::large_text('description', 'Description')->blockHelp('Lieu/type de soirée…')}}
			{{Former::large_date('date', 'Date')}}
    </fieldset>
    <div class="form-actions">
        <input type="text" id="user_name" placeholder="Ardoise à débiter" class="typeahead">
        <div class="input-append">
          <input class="input-small" id="montant" type="number" step="any" value="1.00"><span class="add-on">€</span>
        </div>
        <button class="btn" id="add_row" type="button">Ajouter</button>
    </div>
    <fieldset>
      <table class="table table-bordered" id="debit_table">
        <thead>
      	  <tr><th>Ardoise</th><th>Montant</th></tr>
      	</thead>
      	<tbody>
      	</tbody>
      </table>
    </fieldset>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Ajouter une soirée</button>
    </div>
		{{Former::close()}}
</p>
@endsection

@section("js")
<script type="text/javascript" charset="utf-8">
  $("form").keypress(function(e) {
    if (e.which == 13) {
      return false;
    }
  });

  var user_list = [@foreach(Utilisateur::all() as $u)'{{$u->login}}',@endforeach];
  $('input.typeahead').typeahead({ 'source': user_list });
  
  $('#add_row').click(function(){
    var user_name = $('#user_name')[0].value;
    var montant = $('#montant')[0].value;

    var selector = $('#debit_table input[name='+user_name+']');
    
    if(selector.length > 0) {
      selector[0].value = parseFloat(selector[0].value) + parseFloat(montant);
    }
    else if ($.inArray(user_name, user_list) != -1){
      $('#debit_table > tbody:last').append('<tr><td>'+user_name+'</td><td><input type="number" step="any" min="0" name="'+user_name+'" value="'+montant+'"></td></tr>');
    }
      
  })
</script>
@endsection