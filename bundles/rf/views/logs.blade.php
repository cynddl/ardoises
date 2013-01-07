@layout("rf::home")

@section("rf_content")

{{\Bootstrapper\Breadcrumbs::create(array('RF' => URL::to('rf'), 'Logs'))}}

<h2>Liste des événements enregistrés</h2>
<p>
<table class="table table-striped table-bordered dt-table">
  <thead>
    <tr>
      <th>Utilisateur</th>
      <th>Date</th>
      <th>Message</th>
    </tr>
  </thead>
  <tbody>
    @foreach(LogDB::order_by('date', 'DESC')->get() as $item)
      <tr>
        <td>{{$item->utilisateur->login}}</td>
        <td>{{Date::forge($item->date)->format('datetime')}}</td>
        <td>{{$item->description}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</p>
@endsection