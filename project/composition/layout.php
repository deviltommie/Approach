<?php

require_once __DIR__.'/../core.php';

global $RuntimePath;
global $StaticFiles;
global $RemoteBase;
global $ApproachDebugConsole;
$StaticMarkupPath = $RuntimePath.'/support/templates/static/';

Composition::$Active->DOM = new renderable('html');
$html = &Composition::$Active->DOM;

$html->children[] = $head = new renderable( ['tag'=>'head','template'=>$StaticMarkupPath.'head_base.htm']);
    $head->children[]= new renderable(['tag'=>'title','content'=>Composition::$Active->Context['data']['title']]);
    $head->content .='
        <link rel="stylesheet" type="text/css" href="'.$StaticFiles.'/css/layout.css">
        <link rel="stylesheet" type="text/css" href="'.$StaticFiles.'/css/style.css">
        <link rel="stylesheet" type="text/css" href="'.$StaticFiles.'/css/component.css">
    ';

$html->children[] = $body = new renderable( 'body' );

    $body -> children[] = $Navigation   = new renderable( ['tag'=>'nav', 'pageID'=>'Navigation','template'=>$StaticMarkupPath.'navigation_bar.htm'] );
    $body -> children[] = $Screen       = new renderable( ['tag'=>'ul', 'pageID'=>'Screen'] );
    $body -> children[] = $Content      = new renderable( ['tag'=>'ul', 'pageID'=>'Content'] );
    $body -> children[] = $Footer       = new renderable( ['tag'=>'ul', 'pageID'=>'Footer'] );

    $Navigation->classes=['navbar', 'navbar-default'];
    $Navigation->attributes['role']='navigation';

    $body -> children[] = $SigninModal  =
        new renderable([    'tag'=>'div','pageID'=>'SigninModal',
                            'classes'=>['modal','fade'],
                            'attributes'=>['tabindex'=>'-1', 'role'=>'dialog','aria-labelledby'=>'myModalLabel','aria-hidden'=>'true'],
                            'template'=>$StaticMarkupPath.'signin_modal.htm']);
        
    $body->children[]   = $ApproachDebugConsole;
    
    $ecureHTTP = false;
    if(isset($_SERVER['HTTPS']))    if($_SERVER['HTTPS'] !='' && strtolower($_SERVER['HTTPS']) != 'off') $ecureHTTP = true;
    if($ecureHTTP)  if(substr($_SERVER['HTTP_HOST'],1,count('portal.'.$RemoteBase)-1) == 'portal.'.$RemoteBase)
    {
        error_reporting(E_ERROR);
        Composition::$Active->InterfaceMode=true;
        $ApproachDebugConsole->content='true';
    }
    else    $ApproachDebugConsole->content=approach_dump(substr($_SERVER['HTTP_HOST'],0,count('portal.'.$RemoteBase)).' [====] portal.'.$RemoteBase);

    $body->children[]=$ApproachDebugConsole;
    $ApproachDebugConsole->attributes['style']='display: block; height: 220px; position: relative; background-color: black; color: white; font-size: 18px;';
?>