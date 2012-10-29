@layout("rf::home")

@section("rf_content")
<h2>Gestion des rôles</h2>
<blockquote>Chaque utilisateur avancé (RF, RK, membre BDE avec droits pour ajouter des soirées…) dispose d'un rôle, qui lui donne des permissions pour accéder à différents services. Les permissions sont fixées mais les rôles peuvent être édités, pour ajouter des utilisateurs spécifiques par exemple.</blockquote>
<p><table class="table table-striped table-bordered"><!--- dt-table">-->
  <thead>
		<tr>
			<th rowspan="2">Permissions</th>
			<th colspan="{{Role::count()}}">Roles</th>
		</tr>
		<tr>
			@foreach($roles as $r)
			@if($r->lieu)
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
			<td>&#x2717;</td>
			@endif
			@endforeach
		</tr>
		@endforeach
  </tbody>
</table>
</p>

<ul class="nav nav-tabs">
  <li class="active"><a href="#ajouter-role" data-toggle="tab">Ajouter un rôle</a></li>
  <li><a href="#attribuer-role" data-toggle="tab">Attribuer un rôle</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="ajouter-role">
		{{Former::open()}}
		{{Former::medium_text('nom')}}
		<div class="control-group">
		{{Form::label('lieu_id', 'Lieu', array('class'=>'control-label'))}}
		@foreach(Lieu::all() as $l)
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
		{{Former::actions( Bootstrapper\Buttons::submit('Envoyer') )}}
		{{Former::close()}}
	</div>
	<div class="tab-pane active" id="attribuer-role">
	</div>
</div>

@endsection