@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Ardoises' => URL::to('rf/ardoises'), 'Ajouter un utilisateur'))}}

<h2>Ajouter un utilisateur</h2>
@render('template.prefs_form', array('all'=>true, 'mdp'=>true, 'user'=>array()))
@endsection
