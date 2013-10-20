<?php

/*************************************************************************

 APPROACH 
 Organic, human driven software.


 COPYRIGHT NOTICE
 __________________

 (C) Copyright 2012 - Approach Foundation LLC, Garet Claborn
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


$ActiveComposition;

require_once('Component.php');

class Composition
{
    public $DOM;
    public $ComponentList=Array();
    
    public $options;
    public $meta;
    public $intents;

    public $editable=Array();

    function Composition()
    {
        global $ActiveComposition;
        $ActiveComposition = $this;
    }

    function init($options=array())
    {
        global $APPROACH_DOM_ROOT;
        global $$APPROACH_DOM_ROOT;

        $this->DOM = $$APPROACH_DOM_ROOT;
        $this->options = $options;
    }
    
    public function ResolveComponents(&$DOM)
    {
        $editCount=0;
        global $APPROACH_EDITMODE;
        foreach($DOM->children as $child)
        {
            if($child instanceof Smart)
            {
                if($APPROACH_EDITMODE=false)
                {
                  if($child->tag != 'html' && $child->tag != 'head' && $child->tag != 'body' && $child->tag != 'script' && $child->tag != 'style')
                    $child->classes[] = "editableFeature";

                  foreach($child->context as $WhichComponent => $InstanceContext)
                  {
                      $this->editable[$editCount]['name'] = $WhichComponent;
                      $this->ComponentList[$WhichComponent][]=$InstanceContext;
                      $this->editable[$editCount]['index'] = count($this->ComponentList[$WhichComponent])-1;
                      $this->editable[$editCount]['reference']=$child;
                      $editCount++;
                  }
                }
                else
                {
                  foreach($child->context as $WhichComponent => $InstanceContext)
                  {
                      $this->ComponentList[$WhichComponent][]=$InstanceContext;
                  }
                }
            }
            if($child->children != null) $this->ResolveComponents($child);
        }
    }

    function publish($silent=false)
    {
        global $RegisteredScripts;

        global $APPROACH_DOM_ROOT;
        global $$APPROACH_DOM_ROOT;
        global $ApproachDebugConsole;
        global $ApproachDebugMode;

        $$APPROACH_DOM_ROOT = $this->DOM;

        $this->ResolveComponents($this->DOM);

        foreach($this->ComponentList as $ComponentInstance => $Instances)
        {
            $test='asdf';
            foreach($Instances as $Context)
            {
                $Component = new $ComponentInstance();
                $Component->createContext($Context['render'], $Context['data'], $Context['template']);
                $Component->Load($Context['options']);
            }
        }
        foreach($this->editable as &$editableFeature)
        {
            $references=array();
            if($editableFeature['reference']->children != null)
            {
                foreach($editableFeature['reference']->children as $child)
                {
                    $child->classes[]='editable';
                    $references[]=$child->pageID;
                }
            }
            $editableFeature['reference']=array();
            $editableFeature['reference']=$references;      //Links to child template's $tokens['__self_id']
        }

        $json=json_encode($this->editable);

/*
        RegisterJQueryEvent('BUBBLE_CLASS_CLICK', 'editableFeature', $SettingsServiceCall);
        RegisterJQueryEvent('BUBBLE_ID_CLICK', 'ApproachControlUnit', $UpdateServiceCall .PHP_EOL. $PreviewServiceCall);
        RegisterScript($JqueryReadyFunction, true, "To Feature Editor");
        CommitJQueryEvents();
*/

        foreach($this->DOM->children as $child)   //Get Body
        {
            if($child->tag == 'body')
            {
                if($ApproachDebugMode)  $child->children[]=$ApproachDebugConsole;
                $child->children[]=$RegisteredScripts;
                break;
            }
        }

        /*  THIS IS WHERE THE HEADER SHOULD GET SENT    */
        header('Access-Control-Allow-Origin: *');
	if(!$silent) print_r('<!DOCTYPE html>'.PHP_EOL.$this->DOM->render()); //Deploy html response - usually
        elseif($silent && isset($this->options['toFile'])) toFile($this->options['toFile'], $this->DOM->render());
	
    }
}


?>