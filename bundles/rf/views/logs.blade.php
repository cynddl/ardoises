@layout("rf::home")

@section("rf_content")
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
    @foreach(LogDB::get() as $item)
      <tr>
        <td>{{$item->utilisateur->login}}</td>
        <td>{{$item->date}}</td>
        <td>{{$item->description}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</p>
@endsection