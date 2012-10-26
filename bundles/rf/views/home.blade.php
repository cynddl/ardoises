@layout($layout)

@section("content")
<div class="row first">
	<div class="span2 offset1 well">
		<ul class="nav nav-list">
			<li class="nav-header"><i class="icon-user"></i> Utilisateurs</li>
		  <li><a href="{{URL::to('rf/ardoises')}}">Ardoises</a></li>
      <li>
        @if(Auth::can('peutcreerardoise'))
					<a href="{{URL::to('rf/ardoises/add')}}">Ajouter un utilisateur</a>
        @else Ajouter un utilisateur @endif
      </li>
			<li>
			  @if(Auth::can('peutcrediter'))
					<a href="{{URL::to('rf/ardoises/transfert')}}">Transactions</a></li>
			  @else Transactions @endif
			<li class="nav-header"><i class="icon-list-alt"></i> Consommations</li>
			<li>{{HTML::link_to_route('produits', 'Produits')}}</li>
			<li>{{HTML::link_to_route('forfaits', 'Forfait')}}</li>
			<li>{{HTML::link_to_route('frigos', 'Frigos & vols')}}</li>
			<li class="nav-header"><i class="icon-user"></i> Soirées</li>
			<li>
			  @if(Auth::can('peutnotersoiree'))
					<a href="{{URL::to('rf/soirees/add')}}">Ajouter une soirée</a>
			  @else Ajouter une soirée @endif
			</li>
			<li>
			  @if(Auth::can('peutvalidersoiree'))
					<a href="{{URL::to('rf/soirees/validate')}}">Valider les soirées</a>
			  @else Valider les soirées @endif
			</li>
			  
			<li><a href="{{URL::to('rf/soirees')}}">Archives</a></li>
			<li class="nav-header"><i class="icon-fire"></i> Gestion avancée</li>
			<li>
				@if(Auth::can('peutediterrole','peutattribuerrole'))
					<a href="{{URL::to('rf/roles')}}">Rôles</a>
			  @else Rôles @endif
			</li>
			<li><a href="{{URL::to('rf/logs')}}">Logs</a></li>
		</ul>
	</div>
	<div class="span8">
		{{HTML::flash()}}
		@section("rf_content")
		<div class="hero-unit">
		  <h1>Bienvenue</h1>
		  <p>sur l'interface d'administration.</p>
		  <p>
		    <a id="documentation" class="btn btn-primary btn-large" data-content="The Game." data-original-title="Et puis quoi encore ?">
		     Aide &amp; documentation
		    </a>
		  </p>
		</div>
		@yield_section
	</div>
@endsection