@layout("rf::home")

@section("rf_content")
<h2>Ajouter un utilisateur</h2>
@render('template.prefs_form', array('all'=>true, 'mdp'=>true, 'user'=>array()))
@endsection
