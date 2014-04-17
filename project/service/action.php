<?php

require_once __DIR__.'/../core.php';
global $ActiveComposition;
$StaticMarkupPath = $RuntimePath.'support/templates/static/';

$user=array();
if(!isset($ActiveComposition))    $ActiveComposition = $Portal = new Composition();  

require_once __DIR__.'/../composition/layout.php';

$Navigation->content = GetFile($StaticMarkupPath.'service_nav.htm');
$SigninModal->content = GetFile($StaticMarkupPath.'service_signin.htm');

if(
 $_SERVER['HTTP_REFERER'] == 'http://service.nicegamez.com/' ||
 false !== strpos($_SERVER['HTTP_REFERER'], 'http://service.nicegamez.com/portal') )
{
        //{( Tsoammmwieell97 != $kainJump7 )
        $user=LoadObject('operator',['target'=>'operator','method'=>'WHERE keyauth = \''. hash('SHA512',$_POST['authen-key'].'HF+!(+!(HJCN*#(Y@&').'\'' ]);
        
        if(count($user) == 0) $Content->content = '<li><h2 style="color:red"> Login failure! You may or may not be stabbed repeatedly for this.</h2></li>';
        else $Content->content = '<li><h2>Welcome, site operator '.$user->data['operator'].'! You have logged in successfully.</h2></li>';

}



$Portal->publish();

?>