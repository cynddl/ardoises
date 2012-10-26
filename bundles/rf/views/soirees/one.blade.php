@layout("rf::home")

@section("rf_content")
<h2>Soirée {{$soiree->nom}} <small>({{$soiree->description}})</small> <span class="badge badge-info">{{$soiree->montant()}} €</span>
</h2>
<div>
   <table class="table table-bordered" id="debit_table">
     <thead>
    	  <tr><th>Ardoise</th><th>Montant</th></tr>
    	</thead>
    	<tbody>
				@foreach($soiree->ardoises()->pivot()->get() as $item)
        <tr><td>{{Ardoise::find($item->ardoise_id)->utilisateur()->login}}</td><td>{{$item->prix}} €</td></tr>
				@endforeach
   	</tbody>
  </table>
</div>
@endsection