<?php

/*************************************************************************

 APPROACH 
 Organic, human driven software.
 
 garet@approachfoundation.org
 Feel free to contact me dev to dev!
 
 *************************************************************************/
error_reporting(E_ERROR);

global $RuntimePath;
global $InstallPath;
global $UserPath;
global $StaticFiles;
global $DeployPath;
global $ApproachRegisteredService;

$RuntimePath    =__DIR__;
$InstallPath    =__DIR__.'/../approach';
$UserPath       =__DIR__.'/support/components';
$StaticFiles    ='//static.nicegamez.com';
$DeployPath     ='//www.nicegamez.com';


require_once($RuntimePath.'/support/_error.php');
require_once($RuntimePath.'/support/_database.php');

require_once($InstallPath.'/base/Renderables/DisplayUnits.php');
require_once($InstallPath.'/base/Dataset.php');
require_once($InstallPath.'/base/Smart.php');

require_once($InstallPath.'/core/Component.php');
require_once($InstallPath.'/core/Composition.php'); 
//require_once($InstallPath.'/core/Service.php');

require_once($UserPath.'/UserComponents.php');

//require_once($RuntimePath.'services/Registrar.php');


?>