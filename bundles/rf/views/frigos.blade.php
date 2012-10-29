@layout("rf::home")

@section("rf_content")


<ul class="nav nav-tabs">
	@foreach($lieux as $l)
	<li class="dropdown">
		<a href="#tab-pane-{{$l->id}}" data-toggle="tab">{{$l->nom}}</a>
    <!--<ul class="dropdown-menu">
      <li><a href="#{{$l->id}}-stocks" data-toggle="tab">Gestion des stocks</a></li>
      <li><a href="#{{$l->id}}-frigos" data-toggle="tab">Frigos</a></li>
    </ul>-->
	</li>
	@endforeach
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active">
		<p>Sélectionnez un lieu pour afficher les stocks en réserve et dans les frigos.</p>
		<p>Les vols ont été notés :
			<ul>
				@foreach($lieux as $l)
					<li><span class="label">{{$l->nom}}</span> il y a {{$l->vols()->order_by('date', 'desc')->first()->temps_ecoule()->format('%d jour(s)')}}
						<p>{{Bootstrapper\Progress::warning_normal(100 * $vols_30d[$l->id] / ($vols_30d[$l->id]+$consos_30d[$l->id]+1))}}</p>
					</li>
				@endforeach
			</ul>
		</p>
	</div>
@foreach($lieux as $l)
<div class="tab-pane fade" id="tab-pane-{{$l->id}}">
<div class="accordion" id="accordion-{{$l->id}}">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-{{$l->id}}" href="#{{$l->id}}-stocks">Gestion des stocks</a>
    </div>
    <div id="{{$l->id}}-stocks" class="accordion-body collapse in">
      <div class="accordion-inner">
				<p><a href="commande" class="btn btn-primary">Passer une commande</a></p>
				<table class="table table-striped table-bordered">
				  <thead>
				    <tr>
				      <th>Produit</th>
				      <th>Quantité en réserve</th>
				    </tr>
				  </thead>
				  <tbody>
				    @foreach(Stockproduit::with('produit')->where_lieu_id($l->id)->get() as $sp)
				      <tr>
								<td><a href="p/{{$sp->produit->id}}">{{$sp->produit->nom}}</a></td>
								<td>{{$sp->qte_reserve}}</td>
				      </tr>
				    @endforeach
				  </tbody>
				</table>
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-{{$l->id}}" href="#{{$l->id}}-frigos">Frigos</a>
    </div>
    <div id="{{$l->id}}-frigos" class="accordion-body collapse">
      <div class="accordion-inner">
				<p><a href="#modal-vols-{{$l->id}}" role="button" class="btn btn-warning" data-toggle="modal">Noter les vols</a>
					{{Vol::order_by('date', 'desc')->where_lieu_id($l->id)->first()->temps_ecoule()->format(' (%d jours écoulés)')}}
				</p>
				<div class="modal modal-vols" id="modal-vols-{{$l->id}}" tabindex="-1" role="dialog" style="display:none;" aria-labelledby="modalLabel{{$l->id}}" aria-hidden="true">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				    <h3 id="modalLabel{{$l->id}}">Gestion des vols ({{$l->nom}})</h3>
				  </div>
				  <div class="modal-body">
					{{Former::inline_open()}}
							{{Former::select('produit_nom')->fromQuery(Produit::all(), 'nom', 'nom')}}
					    <!--<input name="produit_nom" type="text" class="span3" placeholder="Produit" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska"]'>-->
						  <input name="qte_volee" type="number" class="input-small" placeholder="Quantité">
							<input type="hidden" name="lieu_id" value="{{$l->id}}">
						  <button class="btn btn-primary">Ajouter</button>
						{{Former::close()}}
						<table id="responseTable">
							<tbody>
							</tbody>
						</table>
				  </div>
				  <div class="modal-footer">
				    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
				    <button class="btn btn-primary">Noter les vols</button>
				  </div>
				</div>
				<table class="table table-striped table-bordered">
				  <thead>
				    <tr>
				      <th>Groupe</th>
				      <th>Quantité dans les frigos</th>
				    </tr>
				  </thead>
				  <tbody>
				    @foreach(Stockgroupe::with('groupe')->where_lieu_id($l->id)->get() as $sg)
				      <tr>
								<td><a href="p/{{$sp->produit->id}}">{{$sg->groupe->nom}}</a></td>
								<td>{{$sg->qte_frigo}}</td>
				      </tr>
				    @endforeach
				  </tbody>
				</table>
      </div>
    </div>
  </div>
</div>
</div>
@endforeach
</div>
@endsection