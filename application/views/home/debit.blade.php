@layout($layout)

@section("header")
<meta http-equiv="refresh" content="5; url=/logout">
@endsection

@section("content")
<div class="row first">
	<div class="span6 offset3">
	
		<div class="alert alert-success">
			<h4>Votre consommation a bien été débitée.</h4>
			<p>Vous allez être déconnecté dans 5 secondes…</p>
		</div>
		
		<div class="lead">
			<p>Votre ardoise est maintenant à :</p>
			<div class="ardoise-box @if(Auth::user()->ardoise->montant > 0)
					positif @endif">{{Auth::user()->ardoise->montant}} €</p>
		</div>
	
	</div>
</div>
@endsection