<?php

/*************************************************************************

 APPROACH 
 Organic, human driven software.
 
 garet@approachfoundation.org
 Feel free to contact me dev to dev!
 
 *************************************************************************/
 
 
$SiteDirectory = '/var/www/html/approachfoundation.org/';
$InstallPath = substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));

require_once('__config_error.php');
require_once('__config_database.php');

//require_once('Core/Service.php');
require_once('base/Renderables/DisplayUnits.php');
//require_once('Core/Services/Registrar.php');

require_once('core/Composition.php');

?>