@layout("rf::home")

@section("rf_content")
{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Ardoises' => URL::to('rf/ardoises'), "Gestion de l'utilisateur $user->login"))}}


<h2>Gestion de l'utilisateur {{$user->login}}</h2>


<ul class="nav nav-tabs" id="tab-nav">
  <li class="active"><a data-toggle="tab" href="#crediter">Créditer</a></li>
  <li><a data-toggle="tab" href="#edition">Edition du compte</a></li>
  <li><a data-toggle="tab" href="#consos">Consommations</a></li>
  <li><a data-toggle="tab" href="#stats">Statistiques</a></li>
	
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
				@foreach($user->ardoise->consommations()->get() as $c)
				<tr>
					<td><a href="../../stocks/groupe/{{$c->groupe->id}}">{{$c->groupe->nom}}</a></td>
					<td>{{$c->uniteachetee}}</td>
					<td>{{Date::forge($c->date)->format('datetime')}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
  </div>
	<div class="tab-pane" id="stats">
		<h4 class="lead">Consommations sur tous les sites</h4>
		<div id="chart_container">
			<div id="user_y_axis"></div>
			<div id="user-chart"></div>
		</div>
	</div>
</div>
@endsection

@section("js")
<style>
</style>

<script src="http://code.shutterstock.com/rickshaw/vendor/d3.v2.js"></script>
<script src="http://code.shutterstock.com/rickshaw/vendor/d3.layout.min.js"></script>
<script src="http://code.shutterstock.com/rickshaw/rickshaw.min.js"></script>
<link rel="stylesheet" href="http://code.shutterstock.com/rickshaw/rickshaw.min.css" type="text/css" charset="utf-8">
<style type="text/css" media="screen">
#chart_container {
	position: relative;
}

#user_chart {
	position: relative;
	left: 40px;
}
#user_y_axis {
	position: absolute;
	top: 0;
	bottom: 0;
	width: 40px;
}
</style>

<script type="text/javascript" charset="utf-8">
d3.json('{{URL::to("rf/stats/user/".$user->login)}}', function(data){
	var parseDate =  d3.time.format.utc("%Y-%m-%d %H:%M:%S").parse;
	data.forEach(function(d) {
	    d.x = d.date;
	    d.y = d.uniteachetee;
	});
	
	var graph = new Rickshaw.Graph( {
	        element: document.querySelector("#user-chart"),
	        width: 500,
	        height: 100,
					renderer: 'bar',
	        series: [ {
	                color: 'steelblue',
	                data: data
	        } ]
	});
	
	var x_axis = new Rickshaw.Graph.Axis.Time( { graph: graph } );

	var y_axis = new Rickshaw.Graph.Axis.Y( {
	        graph: graph,
	        orientation: 'left',
	        tickFormat: Rickshaw.Fixtures.Number.formatKMBT,
	        element: document.getElementById('user_y_axis'),
	} );
	
	console.log(y_axis);
	graph.render();
});

</script>
@endsection