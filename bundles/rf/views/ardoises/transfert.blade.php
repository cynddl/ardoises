@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Ardoises' => URL::to('rf/ardoises'), 'Transfert'))}}

<h2>Transfert entre ardoises</h2>

<p>
	{{Former::open_horizontal()}}
    <fieldset>
			{{Former::text('debiteur', 'Ardoise émettrice', null, array('class'=>'typeahead'))}}
	    {{Former::text('crediteur', 'Ardoise réceptrice', null, array('class'=>'typeahead'))}}
			{{Former::small_number('montant', 'Montant à transférer')->value('0.00')->min(0)->step('any')}}
	    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Transférer</button>
      </div>
		</fieldset>
	{{Former::close()}}
</p>
@endsection

@section('js')
<script type="text/javascript" charset="utf-8">
  var mySource = [@foreach(Utilisateur::all() as $u)'{{$u->login}}',@endforeach];
  $('input.typeahead').typeahead({ 'source': mySource });
</script>
@endsection