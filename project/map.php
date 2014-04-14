<?php

require_once( __DIR__.'/core.php');
require_once( __DIR__.'/support/datasets/compositions.php');

/* Set up environment */
global $ApproachServiceCall;
global $ActiveComposition;
global $ActiveCompositionContext;

$ActiveComposition = array();
$ActiveCompositionContext['path'] = '';
$ActiveCompositionContext['id']= array();
$ActiveCompositionContext['type']= array();
$ActiveCompositionContext['typeid']= array();

$DefaultRoot = 0;

if(!$ApproachServiceCall){  $ActiveComposition = RouteFromURL($_SERVER['REQUEST_URI']);  }



function RouteFromURL($url, $silent=false)
{
    global $SiteRoot;
    global $DefaultRoot;
    global $ActiveComposition;
    global $ActiveCompositionContext;

    if(!isset($url)) $url = $_SERVER['REQUEST_URI'];
    $AppPath = array('home');
    $AppPath=array_merge($AppPath, ArrayFromURI($url));


    /*  Root Level & Type Detection  */

    $RootSearch=new compositions('compositions');
    $RootSearch->data['id']=$DefaultRoot;

    
    $ActiveCompositionContext['path']=ResolveComposition($RootSearch,$AppPath);
    $ActiveCompositionContext['data']=$ActiveCompositionContext['traversed'][count($ActiveCompositionContext['traversed'])-1];

    $ActiveCompositionContext['entry'] = $AppPath;
//    $ActiveCompositionContext['data'] = $RootSearch->data;
    $ActiveCompositionContext['path'] .= '/compose.php';
    
    $ActiveComposition = new Composition();
    $ActiveCompositionContext['self'] = $RootSearch;  //Database Values for this node

    array_shift($ActiveCompositionContext['traversed']);
    require_once(__DIR__ . '/'.$ActiveCompositionContext['path']);  

    $ActiveComposition->publish($silent);
    $ActiveCompositionContext['self'] = $ActiveComposition;  //Application instantiated by running node values throw chained scopes

    return $ActiveComposition;
}

function ArrayFromURI(&$uri)
{
    $result=array();
    $uri = urldecode($uri);
    $exts=array('.aspx','.asp','.jsp','.php','.html','.htm','.rhtml','.py','.cfm','.cfml', '.cpp', '.c', '.ruby','.dll', '.asm');
    $uri = str_replace($exts, '', $uri);
    $result = explode('/',$uri);

    for($i=0, $L=count($result); $i<$L; $i++)
    {
        if($result[$i] == '' || empty($result[$i])){ unset($result[$i]); continue; }
        else $result[$i] = strtolower($result[$i]);
    }

    return  array_values($result);
}


function ResolveComposition($RootSearch,$PathList)
{
    global $ActiveCompositionContext;
    $options['method']= 'WHERE `alias` LIKE \'' . $PathList[0] . '\' AND `parent` = '.$RootSearch->data['id'].' ';
    $options['condition']= 'ORDER BY self LIMIT 1';
//    $options['debug']=true;

    $RootSearch = LoadObject('compositions', $options);

    if(!isset($RootSearch->data['type']) )
    {
        $options['method']= 'WHERE `title` LIKE \'' . $PathList[0].'\'';
        $options['condition']= 'LIMIT 1';
        
        $RootSearch = LoadObject('compositions', $options);

        if(!isset($RootSearch->data['type']) ) exit('Failed To Route Composition: TYPECAST FAILURE.');
    }
    
    $options['method']= 'WHERE id='.$RootSearch->data['type'];
    $options['condition']= 'ORDER BY id LIMIT 1';
    $Type = LoadObject('types', $options);
        
    $ActiveCompositionContext['id'][]=$RootSearch->data['id'];
    $ActiveCompositionContext['type'][]=$Type->data['name'];
    $ActiveCompositionContext['typeid'][]= $Type->data['id'];
    $ActiveCompositionContext['traversed'][]=$RootSearch->data;
    
    if(count($PathList)<=1) return $Type->data['name'];
    else return $Type->data['name'] .'/'.ResolveComposition($RootSearch,array_slice($PathList, 1));
}
?>
