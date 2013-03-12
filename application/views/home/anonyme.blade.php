@layout("template.layout")

@section("container")
<div class="container">
<div class="row first">
	<div class="span8 offset2 round-box">
		<h2>Débiter des consommations <small>#{{$lieu->nom}}</small></h2>
		
		{{HTML::flash()}}
		
		{{Form::open()}}
			<fieldset class="well">
				<h4>Consommation</h4>
				
				<select id="conso" name="conso" style="width:50%;">
					@foreach($groupe as $g)
						<option value="{{$g['nomreduit']}}">{{$g['nom']}}</option>
					@endforeach
			    </select>
		
				
				{{Form::hidden('lieu_id', $lieu->id)}}
			</fieldset>
			
			<ul  class="double">
			@foreach($groupe as $g)		
				<li>{{$g['nom']}} <span class="label label-info">{{$g['prix']}}</span></li>
			@endforeach
			</ul>
			
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
	
	$('#conso').select2();
</script>
<style type="text/css" media="screen">
	.double {
		margin-bottom: 2em;
	}
	.double:after {
		content: '';
		display: block;
		clear: both;
	}
	.double li {
		width: 50%;
		float:left;
		display: inline;
	}
</style>
@endsection