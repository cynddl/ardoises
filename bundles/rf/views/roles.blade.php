@layout("rf.home.twig")

@section("rf_content")
<h2>Gestion des rôles</h2>
<table class="table table-striped table-bordered dt-table">
  <thead>
    <tr>
      <th rowspan=2>Utilisateur</th>
      <th colspan=9>Droits</th>
    </tr>
    <tr>
      <th>Créditer ardoise</th>
      <th>Créer ardoise</th>
      <th>Noter conso</th><th>Enlever conso</th>
      <th>Noter soirée</th><th>Valider soirée</th>
      <th>Recevoir commande</th>
      <th>Editer rôle</th><th>Attribuer rôle</th>
    </tr>
  </thead>
  <tbody>
    @for(item in roles)
      <tr>
        <td>{{item.utilisateur.login}}</td>
        @for(role in item.roles_array)
        	{@if(role == 1)
        	<td>✔</td>
        	@else
        	<td>✘</td>
        	@endif
        @endfor
      </tr>
    @endfor
  </tbody>
</table>
@endsection