<!DOCTYPE html>   
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="author" content="">

<!--	<link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>-->

	{{Asset::container('bootstrapper')->styles()}}
	{{Asset::container('bootstrapper')->scripts()}}
	{{ Asset::styles()  }}
  {{ Asset::scripts() }}
		
	<!-- !Modernizr - All other JS at bottom -->
	<!--<script src="js/modernizr-1.5.min.js"></script>-->
</head>
<!-- !Body -->
<body>
	<canvas id="bgCanvas"></canvas>
	@section("topbar")
	@yield_section
	@section("container")
	@yield_section
	@section("js")
	@yield_section
	</body>
</html>
