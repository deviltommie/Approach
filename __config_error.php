<?php


ini_set('display_errors', 1);
ini_set('log_errors', 1);
//ini_set('error_log', '/var/log/httpd/error_log_php');
error_reporting(E_ALL);
date_default_timezone_set('America/Los_Angeles');


/*
  Approach Context Variables 
  TO DO: 
  Namespacing, 
  Thread-Friendly Context Class
  Static Approach Instance Handler
  Incorporate Helper-Style Functions Into the Function Registrar?


*/


$ApproachServiceCall = false;
$RecurseCount=0;
$ApproachHTML5=true;

$APPROACH_EDITMODE=true;
$APPROACH_JQUERY_EVENTHANDLING='';
$APPROACH_JQUERY_EVENTS=array();
$APPROACH_REGISTERED_FILES;
$APPROACH_SAVE_FLAG=array();

$SiteRoot = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);




function GetChildScripts($root)
{
  $Container=Array();
  if(isset($root->children))
  foreach($root->children as $child)   //Get Script Type Renderables In Head
  {
      if($child->tag == 'script')
      {
          $Container[]=$child;
      }
  }
  return $Container;
}



function toFile($filename, $data)
{
    $fh = fopen($filename, 'w') or die('cant open that file');
    fwrite($fh, $data);
    fclose($fh);
}



?>
