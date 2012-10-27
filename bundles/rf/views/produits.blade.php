@layout("rf::home")

@section("rf_content")
<h2>Groupes et produits</h2>

<p>Un groupe est constitué d'un ensemble de produits. Par exemple, le groupe <em>soft</em> rassemble les Pampryl, les sodas… On peut consommer des groupes, mais pas des produits.</p>

<ul class="nav nav-pills nav-stacked">
@foreach(Groupe::get() as $groupe)
  <li class="active">
		<a href="g/{{$groupe->id}}"><span class="badge badge-important">{{$groupe->nomreduit}}</span> {{$groupe->nom}}@if($groupe->commentaire)
			<small>({{$groupe->commentaire}})</small>@endif</a>
			<ul class="nav nav-list">
				@foreach($groupe->produits()->get() as $produit)
				<li><a href="p/{{$produit->id}}">{{$produit->nom}} @if($produit->commentaire)
					<small>({{$produit->commentaire}})</small>
				@endif<span class="label">{{$produit->dernierprix}}</span></a></li>
				@endforeach
			</ul>
  </li>
@endforeach
</ul>

<ul class="nav nav-tabs">
  <li class="active"><a href="#ajouter-groupe" data-toggle="tab">Ajouter un groupe</a></li>
  <li><a href="#ajouter-produit" data-toggle="tab">Ajouter un produit</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="ajouter-groupe">
		<form class="form-horizontal" method="POST" action="add/g/">
		  <fieldset>
		 		<div class="control-group">
					<label class="control-label" for="nom">Nom</label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="nom">
					</div>
				</div>
				<div class="control-group">
		      <label class="control-label" for="nomreduit">Nom réduit</label>
		      <div class="controls">
		        <input type="text" class="input-small" id="mail" name="nomreduit">
		      </div>
		    </div>
		 		<div class="control-group">
					<label class="control-label" for="commentaire">Commentaire</label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="commentaire">
					</div>
				</div>
		  </fieldset>
			<div class="form-actions">
		    <button type="submit" class="btn btn-primary">Ajouter le groupe</button>
		  </div>
		</form>
  </div>
  <div class="tab-pane" id="ajouter-produit">
		<form class="form-horizontal" method="POST" action="add/p/">
		  <fieldset>
		 		<div class="control-group">
					<label class="control-label" for="nom">Nom</label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="nom">
					</div>
				</div>
		 		<div class="control-group">
					<label class="control-label" for="commentaire">Commentaire</label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="commentaire">
					</div>
				</div>
		 		<div class="control-group">
					<label class="control-label" for="groupe">Groupe</label>
					<div class="controls">
						<select name="groupe" multiple>
						@foreach(Groupe::get() as $g)
							<option value="{{$g->id}}">{{$g->nom}}</option>
						@endforeach
						</select>
					</div>
				</div>
		  </fieldset>
			<div class="form-actions">
		    <button type="submit" class="btn btn-primary">Ajouter le produit</button>
		  </div>
		</form>
  </div>
</div>
@endsection