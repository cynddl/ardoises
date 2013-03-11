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
		<p>{{$a->login}} <small>({{$a->prenom}} {{$a->nom}})</small></p>
@if(($key + 1)% 58 == 0)
	</td>
</tr>
<tr>
	<td width="50%" valign="top">
@elseif(($key + 1) % 29 == 0)
	</td>
	<td width="50%" valign="top">
@endif
@endforeach
	</td>
</tr>
</table>
@endsection