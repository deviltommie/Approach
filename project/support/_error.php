<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
date_default_timezone_set('America/Los_Angeles');

//ini_set('error_log', '/var/log/httpd/error_log_php');

/*
  Approach Context Variables 
  TO DO: 
  Namespacing, 
  Thread-Friendly Context Class
  Static Approach Instance Handler
  Incorporate Helper-Style Functions Into Utility 
*/

global $ApproachServiceCall;
$ApproachServiceCall = false;

$RecurseCount=0;
$ApproachHTML5=true;

$APPROACH_EDITMODE=false;
$APPROACH_JQUERY_EVENTHANDLING='';
$APPROACH_JQUERY_EVENTS=array();
$APPROACH_REGISTERED_FILES;
$APPROACH_SAVE_FLAG=array();

//$SiteRoot = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);





?>
