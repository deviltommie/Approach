<?php
/*************************************************************************

 APPROACH
 Organic, human driven software.


 COPYRIGHT NOTICE
 __________________

 Copyright (C) 2002-2013, 2014 - Approach Foundation LLC, Garet Claborn
 All Rights Reserved.

 Notice: All information contained herein is, and remains
 the property of Approach Foundation LLC and the original author, Garet Claborn,
 herein referred to as "original author".

 The intellectual and technical concepts contained herein are
 proprietary to Approach Foundation LLC and the original author
 and may be covered by U.S. and Foreign Patents, patents in process,
 and are protected by trade secret or copyright law.

/*************************************************************************
*
*
* Approach by Garet Claborn is licensed under a
* Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.
*
* Based on a work at https://github.com/stealthpaladin .
*
* Permissions beyond the scope of this license may be available at
* http://www.approachfoundation.org/now.
*
*
*
*************************************************************************/



error_reporting(E_ALL);

require_once(__DIR__ .'/../core.php');
require_once($InstallPath.'/core/Service.php');
require_once($RuntimePath.'service/Registrar.php');

$composed = array();
$i=0;

$composer = new compositions('compositions');
$compose=array();

foreach(LoadObjects('compositions') as $obj)
{
    $a=$composer->Load(['method'=>'WHERE id = '.$obj->data['id']]);
    var_dump($a);
}


$html = new renderable('html');
$head = new renderable('head');
$body = new renderable('body');

$html->children[]=$head;
$html->children[]=$body;
$content = new renderable('div','');
//$body->children[]=$content;

/*
foreach($composed as $comp)
{
    $cnt = clone $content;
    foreach($comp->data as $key=>$value)
    {
        $cnt->children[]=new renderable('div','',['content'=>'<span style="float: left;"></span>'.$key.'<span style="float: right;">'.$value.'</span>']);
    }
    $cnt->content = '<hr noshade /><hr /><hr noshade />';
    $body->children[]=$cnt;
    
}
*/
foreach($composed as $comp)
{
    $cnt = clone $content;

    ob_start();
    var_dump($comp);
    $r = ob_get_clean();
    $cnt->content = $r.'<hr noshade /><hr /><hr noshade />';
    $body->children[]=$cnt;
    
}

print_r($html->render());
?>