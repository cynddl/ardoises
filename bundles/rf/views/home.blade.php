@layout($layout)

@section("content")
<div class="row row-fluid first">
	<div class="span3 offset1">
		{{HTML::menu()}}
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