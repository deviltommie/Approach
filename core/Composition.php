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

require_once('Component.php');

class Composition
{
    public static $Active;
    public $DOM;
	public $ComponentList=Array();
    public $InterfaceMode=false;

    public $context=array();
	public $options;
	public $meta;
	public $intents;

	public $editable=Array();

	function Composition($options=array(), $activiate=false)
	{
		if($activiate) $this::$Active =&$this;
		$this->options = $options;
	}
	
	public function ResolveComponents(&$DOM)
	{
		$editCount=0;
		foreach($DOM->children as $child)
		{
			if($child instanceof Smart)
			{
				if($this->InterfaceMode)
				{
				  if(!in_array($child->tag,renderable::$NoAutoRender))
					$child->classes[] = 'Interface controls editable';

				  foreach($child->context as $WhichComponent => $InstanceContext)
				  {
					  $this->editable[$editCount]['name'] = $WhichComponent;
					  $this->editable[$editCount]['index'] = count($this->ComponentList[$WhichComponent])-1;
					  $this->editable[$editCount]['reference']=$child;
					  $this->ComponentList[$WhichComponent][]=$InstanceContext;
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

		global $ApproachDebugConsole;
		global $ApproachDebugMode;

		$this->ResolveComponents($this->DOM);

		foreach($this->ComponentList as $ComponentInstance => $Instances)
		{
			foreach($Instances as $Context)
			{
				$Component = new $ComponentInstance();
				$Component->createContext($Context['self'], $Context['render'], $Context['data'], $Context['template']);
				$Component->Load($Context['options']);
				//$this->DOM->children[1]->children[count($this->DOM->children[1]->children)-1]->content=var_export($Component,true);
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
			$editableFeature['reference']=$references;	//Links to child template's $tokens['__self_id']
		}

		//$json=json_encode($this->editable);
		//RegisterJQueryEvent('BUBBLE_CLASS_CLICK','editableFeature',$SettingsServiceCall);
		//RegisterJQueryEvent('BUBBLE_ID_CLICK','ApproachControlUnit',$UpdateServiceCall.PHP_EOL.$PreviewServiceCall);
		//RegisterScript("",true,"ToFeatureEditor");
		//CommitJQueryEvents();


		foreach($this->DOM->children as $child)   //Get Body
		{
			if($child->tag == 'body')
			{
				if($ApproachDebugMode)  $child->children[]=$ApproachDebugConsole;
				$child->children[]=$RegisteredScripts;
				break;
			}
		}

		/*  THIS IS WHERE THE HEADER SHOULD GET SENT	*/
		header('Access-Control-Allow-Origin: *');
        if(!$silent) print_r('<!DOCTYPE html>'.PHP_EOL.$this->DOM->render()); //Deploy html response - usually
		elseif($silent && isset($this->options['toFile'])) toFile($this->options['toFile'], $this->DOM->render());
	
	}
}

?>