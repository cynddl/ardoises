<?php

class Rf_Stats_Controller extends Base_Controller {
	
	public function action_user($login)
	{
		$u = Utilisateur::where_login($login)->first();
		
		$d1 = Consommation::where_ardoise_id($u->ardoise->id)->min('date');
		$d2 = Consommation::where_ardoise_id($u->ardoise->id)->max('date');
		$consos = DB::query("select series.date, sum(CASE when consommation.ardoise_id='".$u->ardoise->id. "' then consommation.uniteachetee else 0 end) ".
		"from (select CURRENT_DATE + i as date from generate_series(date '$d1'- CURRENT_DATE, date '$d2' - CURRENT_DATE ) i) as series ".
		"left outer join consommation on series.date=DATE(consommation.date) ".
		"group by series.date order by series.date");
		
//		$consos = DB::query('SELECT DATE(date) AS day, COUNT(uniteachetee) FROM consommation GROUP BY day ORDER BY day;');
		//$consos = Consommation::where_ardoise_id($u->ardoise_id)->order_by('date', 'ASC')->get(array('date', 'uniteachetee'));
			
		$consos_json = array();
		foreach ($consos as $c) {
			$consos_json[] = array('date' => Date::forge($c->date)->time(), 'uniteachetee' => $c->sum);
		}
		
		return Response::json($consos_json);
	}
}