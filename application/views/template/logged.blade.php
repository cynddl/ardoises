@layout("template.layout")

@section('topbar')
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</a>
			
			<p class="brand">Bienvenue, {{ Auth::user()->prenom }}. <small>Votre Ardoise : <span class="badge @if(Auth::user()->ardoise->montant <= 0)
					badge-success
				@else badge-important @endif">{{Auth::user()->ardoise->montant}}</span></small></p>
			<div class="nav-collapse collapse">
@if(Auth::user()->roles())
@if(Session::get('rf_session'))
				<a class="btn btn-success active pull-right" href="{{URL::to('rf/')}}">Interface RF</a>
@else
				<a class="btn btn-success pull-right" href="{{URL::to('rf/login')}}">Passer en mode RF</a>
@endif
@endif
				<ul class="nav pull-right">
					<li@if(Request::route()->is('home'))
						class="active"@endif><a href="{{URL::to('')}}"><i class="icon-home icon-white"></i> Accueil</a></li>
					<li@if(Request::route()->is('prefs'))
						class="active"@endif><a href="{{URL::to('prefs')}}"><i class="icon-cog icon-white"></i> Préférences</a></li>
					<li@if(Request::route()->is('historique'))
						class="active"@endif><a href="{{URL::to('historique')}}"><i class="icon-signal icon-white"></i> Historique</a></li>
					<li><a href="{{URL::to('logout')}}"><i class="icon-off icon-white"></i> Déconnection</a></li>
				</ul>
		</div>
		</div>
	</div>
</div>
@section('subnav')
@yield_section
@endsection

@section("container")
<div class="container full fluid">
@section("content")
@yield_section
</div>
@endsection