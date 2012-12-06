<?php

HTML::macro('flash', function()
{
    $message_status = Session::get('message_status');
    $message        = Session::get('message');

    return ($message && $message_status) ? '<div class="alert alert-' . $message_status . '"><button type="button" class="close" data-dismiss="alert">×</button>' . $message . '</div>' : '';
});


HTML::macro('menu', function()
{
	$menu = array(
		array('label'=>'Utilisateurs', 'icon'=>'user'),
		array('label'=>'Ardoises', 'url'=>'rf/ardoises'),
		array('label'=>'Ajouter un utilisateur', 'url'=>'rf/ardoises/add', 'permission'=>'peutcreerardoise'),
		array('label'=>'Transactions', 'url'=>'rf/ardoises/transfert', 'permission'=>'peutcrediter'),
		
		array('label'=>'Consommations', 'icon'=>'list-alt'),
		array('label'=>'Produits', 'route'=>'produits'),
		array('label'=>'Forfaits', 'route'=>'forfaits'),
		array('label'=>'Frigos', 'route'=>'frigos'),
		
		array('label'=>'Commandes', 'icon'=>'list-alt'),
		array('label'=>'Passer une commande', 'url'=>'rf/commandes/add'),
		array('label'=>'Réceptionner les commandes', 'url'=>'rf/commandes/validate'),
		array('label'=>'Archives', 'url'=>'rf/commandes'),
		
		array('label'=>'Soirées', 'icon'=>'user'),
		array('label'=>'Ajouter une soirée', 'url'=>'rf/soirees/add', 'permission'=>'peutnotersoiree'),
		array('label'=>'Valider les soirées', 'url'=>'rf/soirees/validate', 'permission'=>'peutvalidersoiree'),
		array('label'=>'Archives', 'url'=>'rf/soirees'),
		
		array('label'=>'Gestion avancée', 'icon'=>'fire'),
		array('label'=>'Rôles', 'url'=>'rf/roles', 'permission'=>'peutattribuerrole'),
		array('label'=>'Logs', 'url'=>'rf/logs')
	);
	
	$html = '';
	
	$permissions = Auth::user()->permissions();
	
	foreach ($menu as $m)
	{
		if(isset($m['permission']) && !$permissions[$m['permission']])
		{
			$html .= '<li>' . $m['label'] . '</li>';
			continue;
		}
		
		if (isset($m['url']))
		{
			$html .= '<li><a href="'.URL::to($m['url']).'">'.$m['label'].'</a></li>';
		}
		else if (isset($m['route']))
		{
			$html .= '<li>' . HTML::link_to_route($m['route'], $m['label']) . '</li>';
		}
		else
		{
			$html .= '<li class="nav-header"><i class="icon-' . $m['icon'] . '"></i> '.$m['label'].'</li>';
		}
	}
	return '<ul class="nav nav-list well">' . $html . '</ul>';
});
