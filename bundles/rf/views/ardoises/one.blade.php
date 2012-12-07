@layout("rf::home")

@section("rf_content")
<h2>Gestion de l'utilisateur {{$user->login}}</h2>


<ul class="nav nav-tabs" id="tab-nav">
  <li class="active"><a data-toggle="tab" href="#crediter">Créditer</a></li>
  <li><a data-toggle="tab" href="#edition">Edition du compte</a></li>
  <li><a data-toggle="tab" href="#consos">Consommations</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="crediter">
		{{Former::open(URL::to_action('rf@ardoises/credit.'.$user->login))}}
		<legend>Cr&eacute;diter l'ardoise</legend>
		{{Former::xlarge_uneditable('montant')->value($user->ardoise->montant)}}
		{{Former::xlarge_number('credit', 'Montant &agrave; ajouter')->value(0)}}
		{{Former::select('moyenpaiement', 'Moyen de paiement')->fromQuery(MoyenPaiement::all(), 'nom', 'id')}}
		{{Former::actions(Form::submit('Cr&eacute;diter', array('class'=>'btn btn-primary'))) }}
		{{Former::close()}}
    </form>
  </div>
  <div class="tab-pane" id="edition">
	@render('template.prefs_form', array('all'=>true, 'user'=>$user))
  </div>
  <div class="tab-pane" id="consos">
		<table class="table table-striped table-bordered dt-table">
			<thead>
				<tr>
					<th>Groupe</th><th>Nombre</th><th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach(Auth::user()->ardoise->consommations()->get() as $c)
				<tr>
					<td><a href="../../stocks/groupe/{{$c->groupe->id}}">{{$c->groupe->nom}}</a></td>
					<td>{{$c->uniteachetee}}</td>
					<td>{{Date::forge($c->date)->format('datetime')}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
  </div>
</div>
@endsection