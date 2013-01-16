@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Gestion des rôles'))}}

<h2>Gestion des rôles</h2>
<blockquote>Chaque utilisateur avancé (RF, RK, membre BDE avec droits pour ajouter des soirées…) dispose d'un rôle, qui lui donne des permissions pour accéder à différents services. Les permissions sont fixées mais les rôles peuvent être édités, pour ajouter des utilisateurs spécifiques par exemple.</blockquote>

<ul class="nav nav-tabs">
	<li class="active"><a href="#liste-role" data-toggle="tab">Liste des rôles</a></li>
	<li><a href="#utilisateurs-privilegies" data-toggle="tab">Utilisateurs privilégiés</a></li>
  <li><a href="#ajouter-role" data-toggle="tab">Ajouter un rôle</a></li>
</ul>

<div class="tab-content">
<div class="tab-pane active" id="liste-role">
	<table class="table table-striped table-bordered"><!--- dt-table">-->
	  <thead>
			<tr>
				<th rowspan="2">Permissions</th>
				<th colspan="{{Role::count()}}">Rôles</th>
			</tr>
			<tr>
				@foreach($roles as $r)
				@if($r->lieu_id)
				<th>{{$r->nom}} <span class="label label-info">{{$r->lieu->nom}}</span></th>
				@else
				<th>{{$r->nom}}</th>
				@endif
				@endforeach
			</tr>
	  </thead>
	  <tbody>
			@foreach($permissions as $p)
			<tr>
				<th>{{$p->nom}}</th>
				@foreach($roles as $r)
				@if(DB::table('permission_role')->where_role_id($r->id)->where_permission_id($p->id)->count() > 0)
				<td>&#x2713;</td>
				@else
				<td></td>
				@endif
				@endforeach
			</tr>
			@endforeach
	  </tbody>
	</table>
</div>
<div class="tab-pane" id="utilisateurs-privilegies">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Utilisateur</th>
				<th>Roles</th>
			</tr>
			</tr>
		</thead>
		<tbody>
			@foreach($utilisateurs as $u)
			@if(DB::table('utilisateur_role')->where_utilisateur_id($u->id)->count() > 0)
			<tr>
				<td>{{$u->login}}</td>
				<td><a href="#" class="editable attrib-input" data-type="checklist" data-pk="{{$u->id}}" data-value="{{implode($u->roles_id(), ',')}}" data-original-title="Select options"></a></td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
	{{Former::open()}}
	<legend>Attribuer un rôle</legend>
	{{Former::medium_text('utilisateur')->class('typeahead')}}
	{{Former::medium_date('echeance', 'Échéance')->value(Date::forge('now + 1 year')->format('date'))}}
	{{Former::large_select('role', 'Rôle')->fromQuery(Role::all(), 'nom', 'id')}}
	{{Former::actions( Bootstrapper\Buttons::submit('Attribuer', array('class'=>'btn btn-primary')) )}}
	{{Former::close()}}
</div>
<div class="tab-pane" id="ajouter-role">
	{{Former::open()}}
	{{Former::medium_text('nom')}}
	<div class="control-group">
	{{Form::label('lieu_id', 'Lieu', array('class'=>'control-label'))}}
	@foreach($lieux as $l)
	<div class="controls">
	    <label class="radio">
				{{Form::radio('lieu_id', $l->id) . $l->nom}}
			</label>
	</div>
	@endforeach
	</div>
	<div class="control-group">
	{{Form::label('permissions', 'Permissions', array('class'=>'control-label'))}}
	@foreach($permissions as $p)
	<div class="controls">
	    <label class="checkbox">
				{{Form::checkbox('permissions[]', $p->id) . $p->nom}}
			</label>
	</div>
	@endforeach
	</div>
	{{Former::actions( Bootstrapper\Buttons::submit('Envoyer', array('class'=>'btn btn-primary')) )}}
	{{Former::close()}}
</div>
</div>
@endsection

@section('js')
<script type="text/javascript" charset="utf-8">
	$(function(){
		var mySource = [@foreach(Utilisateur::all() as $u)'{{$u->login}}',@endforeach];
		$('input.typeahead').typeahead({ 'source': mySource });
		
	    $('a.editable.attrib-input').editable({
	        source: "{{URL::to('rf/roles/list')}}",
					//sourceCache: true,
					url: "{{URL::to('rf/roles/attrib')}}",
					
					success: function(response, newValue) {
						if(response != true)
							return "Désolé, une erreur est survenue";
					},
					
					//display checklist as comma-separated values
					display: function(value, sourceData) {
					   var html = [],
					       checked = $.fn.editableutils.itemsByValue(value, sourceData);
       
					   if(checked.length) {
					       $.each(checked, function(i, v) { html.push($.fn.editableutils.escape(v.text)); });
					       $(this).html(html.join(', '));
					   } else {
					       $(this).empty(); 
					   }
					}
					
	    });
	});
</script>
@endsection