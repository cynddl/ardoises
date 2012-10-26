<?php

Autoloader::map(array(
	'Authentic' 	=> __DIR__ . '/libraries/authentic.php'
));

Auth::extend('authentic', function() {
	return new Authentic;
});