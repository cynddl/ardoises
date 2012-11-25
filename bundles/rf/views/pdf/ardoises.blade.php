@layout('rf::pdf._layout')

@section('css')

table p + p {
 border-top: none;
}
 
table p {
  border: 1px solid black;
	width: 520px;
	padding: 10px 5px;
	margin: 0;
}
@endsection

@section('content')
<table cellspacing="10">
<tr>
	<td width="100%" valign="top">
@foreach($ardoises as $key => $a)
		<p>{{$a->prenom}} {{$a->nom}}</p>
@if(($key + 1)% 30 == 0)
	</td>
</tr>
<tr>
	<td width="50%" valign="top">
@elseif(($key + 1) % 15 == 0)
	</td>
	<td width="50%" valign="top">
@endif
@endforeach
	</td>
</tr>
</table>
@endsection