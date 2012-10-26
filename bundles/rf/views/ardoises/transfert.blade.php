@layout("rf::home")

@section("rf_content")
<h2>Transfert entre ardoises</h2>

<p>
	{{Former::open_horizontal()}}
    <fieldset>
	    <div class="control-group">
				<label class="control-label">Ardoise émettrice</label>
				<div class="controls">
					<input type="text" name="debiteur" class="typeahead" data-provide="typeahead">          
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Ardoise réceptrice</label>
				<div class="controls">
          <input type="text" name="crediteur" class="typeahead" data-provide="typeahead">
				</div>
			</div>
	 		<div class="control-group">
				<label class="control-label" for="credit">Montant à transférer</label>
				<div class="controls">
					<input type="number" class="input-small" name="montant" value="0.00" min="0">
				</div>
			</div>
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