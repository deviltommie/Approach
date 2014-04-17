<?php

/*************************************************************************

 APPROACH 
 Organic, human driven software.
 
 garet@approachfoundation.org
 Feel free to contact me dev to dev!
 
 *************************************************************************/

global $RuntimePath;
global $InstallPath;
global $UserPath;
global $StaticFiles;
global $DeployPath;
global $RemoteBase;

$RuntimePath    =__DIR__;
$InstallPath    =__DIR__.'/../approach';
$UserPath       =__DIR__.'/support/components';
$StaticFiles    ='//static.YourSite.com';
$DeployPath     ='//www.YourSite.com';
$RemoteBase     ='YourSite.com';


require_once($RuntimePath.'/support/_error.php');
require_once($RuntimePath.'/support/_database.php');

require_once($InstallPath.'/base/Renderables/DisplayUnits.php');
require_once($InstallPath.'/base/Dataset.php');
require_once($InstallPath.'/base/Smart.php');
require_once($InstallPath.'/base/ClientEvents.php');
require_once($InstallPath.'/core/Component.php');
require_once($InstallPath.'/core/Composition.php'); 
require_once($InstallPath.'/core/Service.php');

require_once($UserPath.'/UserComponents.php');
require_once($RuntimePath.'/support/datasets/compositions.php');



function RouteFromURL($url, $silent=false, $RootComposition=0)
{
	global $RuntimePath;

    if(!isset($url)) $url = $_SERVER['REQUEST_URI'];
    $AppPath=array_merge(array('home'), ArrayFromURI($url));

    /*  Root Level & Type Detection  */

    $RootSearch=new compositions('compositions');
    $RootSearch->data['id']=$RootComposition;

    Composition::$Active = new Composition();
    Composition::$Active->Context['path']=ResolveComposition($RootSearch,$AppPath);
    Composition::$Active->Context['data']=Composition::$Active->Context['traversed'][count(Composition::$Active->Context['traversed'])-1];
    array_shift(Composition::$Active->Context['traversed']);
    Composition::$Active->Context['entry'] = $AppPath;
    Composition::$Active->Context['path'] .= '/compose.php';
    Composition::$Active->Context['self'] = $RootSearch;  //Database Values for this node
    
    
    require_once($RuntimePath . '/'.Composition::$Active->Context['path']);
    Composition::$Active->publish($silent);
    Composition::$Active->Context['self'] = &Composition::$Active;  //Application instantiated by running node values throw chained scopes

    return Composition::$Active;
}

function ResolveComposition($RootSearch,$PathList)
{
    $options['method']= 'WHERE `alias` LIKE \'' . $PathList[0] . '\' AND `parent` = '.$RootSearch->data['id'].' ';
    $options['condition']= 'ORDER BY self LIMIT 1';

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
        
    Composition::$Active->Context['id'][]=$RootSearch->data['id'];
    Composition::$Active->Context['type'][]=$Type->data['name'];
    Composition::$Active->Context['typeid'][]= $Type->data['id'];
    Composition::$Active->Context['traversed'][]=$RootSearch->data;
    
    if(count($PathList)<=1) return $Type->data['name'];
    else return $Type->data['name'] .'/'.ResolveComposition($RootSearch,array_slice($PathList, 1));
}

?>