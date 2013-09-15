<?php

/*************************************************************************

 APPROACH 
 Organic, human driven software.
 
 *************************************************************************/
 
 
$SiteDirectory = '/var/www/html/approachfoundation.org/';
$InstallPath = substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));

require_once('_config_error.php');
require_once('_config_database.php');

//require_once('Core/Service.php');
require_once('Base/Renderables/DisplayUnits.php');
//require_once('Core/Services/Registrar.php');

require_once('Core/Publication.php');

?>