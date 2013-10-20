<?php

require_once('../../approach/core.php');
require_once('navigation.php');


$StaticFiles='http://portal.localhost/approachfoundation.org/static/';

$pub = $Composing;
$pub->init(array());

$head = new renderable('head');
$pub->DOM->children[] = $head;                          //Attach Head
$pub->DOM->children[] = $body = new renderable('body'); //Attach Body

$head->content='
<meta http-equiv="content-type" content="text/html; charset=utf-8" >
<title>An organic approach to software</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"> </script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"> </script>


<link rel="stylesheet" type="text/css" href="'.$StaticFiles.'css/layout.css">

<link href="http://fonts.googleapis.com/css?family=Signika:700,600,400,300" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Signika:700,600,400,300|Quattrocento+Sans:400,700italic,400italic,700" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="'.$StaticFiles.'img/display/logo.png" />

';


$screen = new renderable('ul', 'Screen');
$ribbon = new renderable('ul', 'Ribbon');

$screen->children[]= $logomap = new renderable('img','ApproachStruct', array('selfcontained'=>true,''));
$logomap->attributes['src']=$StaticFiles . 'img/display/logo.png';

$footer = new renderable('ul', 'footer');   PopulateNav($footer, $Navigation);
$footer->content='<li class="notice">&copy; 2013 Approach Foundation, LLC</li>';

$screen->classes[]='sheer';
$ribbon->classes[]='sheer';

$body->children[] = $screen;
$body->children[] = $ribbon;
$body->children[] = $footer;

$DisplayArea['Stage']=new renderable('li', 'Stage');
$DisplayArea['Content'] = new renderable('li', 'Content');
$DisplayArea['Visual'] = new renderable('li', 'Visual');

$screen->children[] = $DisplayArea['Stage'];
$screen->children[] = $DisplayArea['Content'];
$screen->children[] = $DisplayArea['Visual'];



/*

$Dynamics = new renderable('ul','Dynamics');
$Dynamics->children[]=$HeroUnit = new renderable('li', 'ApproachHeroUnit');
$Dynamics->children[]=$ControlUnit = new renderable('li','ApproachControlUnit');

$body->children[] = $Dynamics;

*/

?>
