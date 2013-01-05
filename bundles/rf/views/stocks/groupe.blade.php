@layout("rf::home")

@section("rf_content")
<h2>{{$groupe->nom}} <small><a class="editable-input text-input" data-name="nomreduit" data-pk="{{$groupe->id}}">{{$groupe->nomreduit}}</a></small></h2>
<p class="lead">Description : <a class="editable-input text-input" data-name="commentaire" data-pk="{{$groupe->id}}">{{$groupe->commentaire}}</a></p>

<ul class="nav nav-tabs">
	@foreach($lieux as $l)
	<li class="dropdown">
		<a href="#tab-pane-{{$l->id}}" data-toggle="tab">{{$l->nom}}</a>
	</li>
	@endforeach
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active">
		
	</div>
@foreach($lieux as $l)
<div class="tab-pane fade" id="tab-pane-{{$l->id}}">
  <div class="well">
		@if(Stockgroupe::where_groupe_id($groupe->id)->where_lieu_id($l->id)->count() > 0)
		<p>Quantité dans les frigos : {{Stockgroupe::where_groupe_id($groupe->id)->where_lieu_id($l->id)->first()->qte_frigo}}.</p>
		@if(Vol::where_lieu_id($l->id)->where_groupe_id($groupe->id)->count() > 0)
		<p>Vols depuis 30 jours : {{Vol::where_lieu_id($l->id)->where('date', '>', Date::forge('now - 30 day')->format('datetime'))->where_groupe_id($groupe->id)->sum('qte_volee')}}.</p>
		@else
		<p>Aucun vol depuis 30 jours.</p>
		@endif
		@endif
	</div>
	<p><a href="#modal-groupev-{{$l->id}}" role="button" class="btn btn-primary" data-toggle="modal">Modifier les prix</a></p>
	
	
	<div class="modal" id="modal-groupev-{{$l->id}}" tabindex="-1" role="dialog" style="display:none;" aria-labelledby="modalGroupeVLabel{{$l->id}}" aria-hidden="true">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="modalGroupeVLabel{{$l->id}}">Modification du groupe {{$groupe->nom}}</h3>
	  </div>
	  <div class="modal-body">
		{{Former::open('rf/stocks/groupe/'.$groupe->id)}}
			{{Former::populate(Groupev::where_lieu_id($l->id)->where_groupe_id($groupe->id)->first())}}
			{{Former::withRules(array('prix_adh' => 'numeric', 'prix_non_adh' => 'numeric'))}}
			{{Former::small_number('prix_adh', 'Prix adhérent')->min(0)->append('€')}}
			{{Former::small_number('prix_non_adh', 'Prix non adhérent')->min(0)->append('€')}}
			{{Former::small_number('udv', 'Unité de vente')->min(0)}}
			{{Former::small_select('actif', 'Actif')->options(array(0=>'Non', 1=>'Oui'), 1)}}
			{{Former::small_select('disponible', 'Disponible (pour tous)')->options(array(0=>'Non', 1=>'Oui'), 1)->blockHelp("Si le produit n'est pas disponible, il ne s'affichera pas sur les interfaces des consommateurs mais pourra être débité par un RF.")}}
		  <input type="hidden" name="lieu_id" value="{{$l->id}}">
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
		    <button class="btn btn-primary">Enregistrer</button>
		  </div>
		{{Former::close()}}
	  </div>
	</div>

	<table class="table table-striped table-bordered">
		<tr>
			<th>Date</th><th>Prix adhérent</th><th>Prix non adhérent</th><th>Unité de vente</th>
		</tr>
		@foreach(Groupev::where_lieu_id($l->id)->order_by('date', 'desc')->where_groupe_id($groupe->id)->get() as $gv)
		<tr>
			<td>{{Date::forge($gv->date)->format('datetime')}}</td>
			<td>{{$gv->prix_adh}} €</td>
			<td>{{$gv->prix_non_adh}} €</td>
			<td>{{$gv->udv}}</td>
		</tr>
		@endforeach
		@if(Groupev::where_lieu_id($l->id)->where_groupe_id($groupe->id)->count() == 0)
		<tr>
			<td colspan="4"><span class="label label-important">Aucun historique de prix.</span></td>
		</tr>
		@endif
	</table>
</div>
@endforeach
</div>
@endsection


@section('js')
<script type="text/javascript" charset="utf-8">
  $('a.editable-input.text-input').editable({
    type: 'text',
    url: '{{URL::to("rf/stocks/groupe/edit")}}',
		inputclass: ''
});
</script>
@endsection