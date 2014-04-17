<?php

require_once __DIR__.'/../core.php';
global $ActiveComposition;
$StaticMarkupPath = $RuntimePath.'support/templates/static/';

if(!isset($ActiveComposition))    $ActiveComposition = $Portal = new Composition();  
require_once __DIR__.'/../composition/layout.php';

$Navigation->content = GetFile($StaticMarkupPath.'service_nav.htm');
$SigninModal->content = GetFile($StaticMarkupPath.'service_signin.htm');

$Content->content = '<li><h2> You must login to view this area.</h2></li>';


$Portal->publish();

?>