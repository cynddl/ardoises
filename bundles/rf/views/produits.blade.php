@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Produits'))}}

<h2>Groupes et produits</h2>

<p>Un groupe est constitué d'un ensemble de produits. Par exemple, le groupe <em>soft</em> rassemble les Pampryl, les sodas… On peut consommer des groupes, mais pas des produits.</p>


<table class="table table-striped table-bordered dt-table">
	<thead>
		<tr><th>Nom réduit</th><th>Nom du groupe</th><th>Nombre de produits</th></tr>
	</thead>
	<tbody>
		@foreach(Groupe::get() as $groupe)
		<tr>
			<td><span class="badge badge-important">{{$groupe->nomreduit}}</span></td>
			<td>
				<a href="stocks/groupe/{{$groupe->id}}">{{$groupe->nom}}@if($groupe->commentaire && $groupe->commentaire != "None")
<small>({{$groupe->commentaire}})</small>@endif</a>
			</td>
			<td>{{$groupe->produits()->count()}}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<hr />

<ul class="nav nav-tabs">
  <li class="active"><a href="#ajouter-groupe" data-toggle="tab">Ajouter un groupe</a></li>
  <li><a href="#ajouter-produit" data-toggle="tab">Ajouter un produit</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="ajouter-groupe">
		<div class="alert">
		  <button type="button" class="close" data-dismiss="alert">×</button>
		  <strong>Attention aux doublons !</strong> Ne rajoutez pas un produit ou un groupe déjà présent dans la base. Pour passer une commande ou ajouter des produits aux frigos, allez plutôt sur la page {{HTML::link_to_route('frigos','Frigos')}}.
		</div>
		{{Former::open('rf/produits/add/g/')}}
		  <fieldset>
				{{Former::xlarge_text('nom', 'Nom')}}
		 		{{Former::small_text('nomreduit', 'Nom réduit')}}
				{{Former::xlarge_text('commentaire', 'Commentaire')}}
		  </fieldset>
			<div class="form-actions">
		    <button type="submit" class="btn btn-primary">Ajouter le groupe</button>
		  </div>
		{{Former::close()}}
  </div>
  <div class="tab-pane" id="ajouter-produit">
		{{Former::open('rf/produits/add/p/')}}
		  <fieldset>
		 		{{Former::xlarge_text('nom', 'Nom')}}
				{{Former::xlarge_text('commentaire', 'Commentaire')}}
				{{Former::select('groupe_id', 'Groupe')->fromQuery(Groupe::all(), 'nom', 'id')}}
		  </fieldset>
			<div class="form-actions">
		    <button type="submit" class="btn btn-primary">Ajouter le produit</button>
		  </div>
		</form>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript" charset="utf-8">
  $('a.editable-input.text-input').editable({
    type: 'text',
    url: '{{URL::to("rf/stocks/produit/edit")}}',
		inputclass: ''
});
</script>
@endsection