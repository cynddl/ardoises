@layout("template.layout")

@section("container")
<div class="container">
<div class="row first">
	<div class="span8 offset2 round-box">
		<h2>Débiter des consommations <small>#{{$lieu->nom}}</small></h2>
		
		{{HTML::flash()}}
		
		<ul class="nav nav-pills">
		@foreach($groupe as $g)
		@if($g['nom'] == 'Soft')
			<li class="active"><a data-pk="{{$g['nom']}}" href="#">{{$g['nom']}} ({{$g['prix']}})</a></li>
		@else
			<li><a data-pk="{{$g['nom']}}" href="#">{{$g['nom']}} ({{$g['prix']}})</a></li>
		@endif
		@endforeach
		</ul>
		
		{{Form::open()}}
			<fieldset class="well">
				{{Former::large_text('conso', 'Consommation', '', true, array('class'=>'typeahead', 'data-provide'=>'typeahead'))}}
				{{Form::hidden('lieu_id', $lieu->id)}}
			</fieldset>
			<a href="{{URL::to('/')}}" class="btn">Revenir en arrière</a>
			<button type="submit" class="btn btn-primary pull-right">Débiter</button>
		{{Form::close()}}
	</div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript" charset="utf-8">
  var mySource = [@foreach(Groupe::all() as $g)'{{$g->nom}}',@endforeach];
  $('input.typeahead').typeahead({ 'source': mySource });
	$('li a').click(function(){$('input[name="conso"]').val($(this).attr('data-pk'))});
</script>
@endsection