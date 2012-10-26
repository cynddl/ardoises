@layout($layout)

@section("content")
<div class="row first">
	<div class="span6 offset3">
	<h2>Modification du compte</h2>
	@render('template.prefs_form', array('user'=>Auth::user()))
	</div>
</div>
@endsection