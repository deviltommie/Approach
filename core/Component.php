<?php

/*************************************************************************

 APPROACH
 Organic, human driven software.


 COPYRIGHT NOTICE
 __________________

 (C) Copyright 2014 - Approach Corporation, Garet Claborn
 All Rights Reserved.

 Notice: All information contained herein is, and remains
 the property of Approach Corporation and the original author, Garet Claborn,
 herein referred to as "original author".

 The intellectual and technical concepts contained herein are
 proprietary to Approach Corporation and the original author
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
* http://www.approach.im/now.
*
*
*************************************************************************/



require_once(__DIR__ . '/../base/Render.php');
include_once(__DIR__ . '/../base/Smart.php');
include_once(__DIR__ . '/../base/Dataset.php');

class Component
{
    public static $ComponentName = 'Component';
    public static $SaveFlag = array();
	public $context = array();
	protected $UpdateDataclass=array();
	
    /***************************************************************************//*
     * @type string|enum|null $RenderType Defines the class of @see renderable used to contain template results.
     * @type string|array|null $ChildTag Defines the inner element @see renderable::$tag of the templates of  used.  
     * @type string|array|null $ChildClasses Defines the CSS $see renderable::classes applied to the outermost element(s) the template(s) used.
     * @type string|array|null $ChildClasses Defines the XML $see renderable::attributes applied to the outermost element(s) the template(s) used.
     * @type string|array|null $ParentContainer Defines a reference to the @see renderable used to contain template results.
     * @type string|array|null $ContainerClasses Defines the CSS @see renderable::classes applied to the @see renderable used to contain template results.
     *****************************************************************************/

	protected $RenderType='Smart';
	protected $ChildTag='li';
	protected $ChildClasses=array();
	protected $ChildAttributes=array();
	protected $ContainerClasses=array();
	protected $ParentContainer=array();
	protected $Items=array();
	protected $Scripts='';
	protected $ScriptPlacement=true;

	function CreateContext(&$root,&$render,&$data,&$template, $options=array())
	{
		$this->context['root']		= &$root;
		$this->context['render']	= &$render;
		$this->context['data']		= &$data;
		$this->context['template']	= &$template;

		/******************************************//*
		* FETCHING OPTIONAL APPROACH VALUES
		*********************************************/

		//Default Values
		if(!isset($this->ContainerClasses))	$this->ContainerClasses	=Array($this::$ComponentName.'Container');
		if(!isset($this->ChildClasses))		$this->ChildClasses		=Array($this::$ComponentName);
		if(!isset($ParentContainer))		$this->ParentContainer	= &$root;//GetRenderable($this->context['root'],$this->context['render']);

		if(is_array($options))
		{
			foreach($options as $key => $value)
			{
				switch($key)
				{
					case 'RenderType'			: $this->RenderType				= $value; break;
					case 'ChildTag'				: $this->ChildTag			 	= $value; break;
					case 'ContainerClasses'		: $this->ContainerClasses	 	= $value; break;
					case 'ContainerAttributes'	: $this->ContainerAttributes	= $value; break;
					case 'ChildClasses'			: $this->ChildClasses			= $value; break;
					case 'Items'				: $this->Items					= $value; break;
					case 'Scripts'				: $this->Scripts				= $value; break;
					case 'ScriptPlacement'		: $this->ScriptPlacement		= $value; break;
					default: break;
				}
			}
		}
	}
	function edit()
	{
		$miniDOM = new renderable('div');

		return $ResultString = $miniDOM->render();
	}

	function HandleChildScripts($child)
	{
		if(isset($child->Scripts['head']))
		{
			foreach($child->Scripts['head'] as $Name => $Script)
			{
				RegisterScript($Script, true, $Name);
			}
		}
		if(isset($child->Scripts['tail']))
		{
			foreach($child->Scripts['tail'] as $Name => $Script)
			{
				RegisterScript($Script, false, $Name);
			}
		}
	}

	function HandleScripts($wrapper){}

	function Load($options=Array()) //smart RenderType
	{

		//Optional Overrides
		if(is_array($options))
		{
			foreach($options as $key => $value)
			{
				switch($key)
				{
					case 'RenderType'			: $this->RenderType				= $value; break;
					case 'ChildTag'				: $this->ChildTag			 	= $value; break;
					case 'ContainerClasses'		: $this->ContainerClasses	 	= $value; break;
					case 'ContainerAttributes'	: $this->ContainerAttributes	= $value; break;
					case 'ChildClasses'			: $this->ChildClasses			= $value; break;
					case 'Items'				: $this->Items					= $value; break;
					case 'Scripts'				: $this->Scripts				= $value; break;
					case 'ScriptPlacement'		: $this->ScriptPlacement		= $value; break;
                    case 'context'              :   foreach($value as $context => &$variable)
                                                    {   switch($context)
                                                        {
                                                            case 'root': 	    $this->context['root']		= &$variable; break;
															case 'render': 	    $this->context['render']	= &$variable; break;
															case 'data': 	    $this->context['data']		= &$variable; break;
															case 'template': 	$this->context['template']	= &$variable; break;
															default:            break;
                                                        }
                                                    }
					default: break;
				}
			}
		}

		/*	END FETCHING OPTIONAL VALUES	*/
		/* ---------------------------------*/
		/*	BEGIN ARRAY ALIGNMENT			 */

		foreach($this->context['data'] as $_class)
		{
			$_instances = LoadObjects($_class,$options[$_class]);
			$i=0;

			foreach($_instances as $_instance)
			{
				foreach($_instance->data as $key=>$value)
				{
    				$BuildData[$i][$_class][$key] = $value;
				}
				if( isset($this::$SaveFlag[$i]) )
				if( $this::$SaveFlag[$i] == true )
				{
                    $this->UpdateDataclass[$_class] = $_instance->data;
				}
				++$i;
			}
		}

		$this->Process($BuildData);
	}


	function Process(&$BuildData)
	{
		$this->PreProcess($BuildData);
        $TemplateCount=$this->AlignMarkup();
		for($i=0, $L=$TemplateCount; $i<$L; ++$i)
		{
			$c=0;
			foreach($BuildData as $ConsolidatedRow)
			{
				//Send Data From Database To Rendering Engine
				$SmartObject = new $this->RenderType(array('tag'=>$this->ChildTag[$i],'template' => $this->context['template'], 'markupindex' => $i) );
				$SmartObject->tokens['__self_index']=$c;

				$SmartObject->data[$this::$ComponentName] = (isset($SmartObject->data[$this::$ComponentName])) ?
				array_merge($SmartObject->data[$this::$ComponentName], $ConsolidatedRow) : $SmartObject->data[$this::$ComponentName]=$ConsolidatedRow;
				$SmartObject->classes = (is_array($SmartObject->classes)) ?
				array_merge($SmartObject->classes, $this->ChildClasses[$i]) : $this->ChildClasses[$i];
				$SmartObject->attributes =(is_array($SmartObject->attributes)) ?
				array_merge($SmartObject->attributes, $this->ChildAttributes[$i]) : $this->ChildAttributes[$i];

				$Children[]=$SmartObject;
				$this->HandleChildScripts($SmartObject);
				++$c;
			}
		}
		$this->HandleScripts($this->ParentContainer);

		$SmartObject->Tokenize();
		$this->ParentContainer->children=array_merge($this->ParentContainer->children, $Children);
		$this->ParentContainer->classes = array_merge($this->ParentContainer->classes, $this->ContainerClasses);
		$this->PostProcess($BuildData, $this->ParentContainer);
	}
	function PreProcess(&$BuildData){   /* $this->ParentContainer->children[]= $t=new renderable('div');    */  }
	function PostProcess(&$BuildData){}

	function Save($IncomingTokens, $TemplateBinding)
	{
		$change = false;
		foreach($TemplateBinding as $Component => $_Dataclass)
		{
			$ActiveComponent = $Component;
			foreach($_Dataclass as $Name => $PropertyList)
			{
				if( isset($this->UpdateDataclass[$Name]) )
				{
					require_once($RuntimePath . '/support/datasets/'. $Name . '.php');
					$dbo = new $Name($Name);

					foreach($this->UpdateDataclass[$Name] as $k => $v)   $dbo->data[$k] = $v;

					foreach($PropertyList as $Property => $TokenName)
					{
						if( isset($dbo->data[$Property]) && isset($IncomingTokens['_'.$TokenName]) )
						{
							if($IncomingTokens['_'.$TokenName] != $dbo->data[$Property])
							{
								$change = true;
								$dbo->data[$Property] = $IncomingTokens['_'.$TokenName];
							}
						}
					}
					if($change) $dbo->Save($dbo->data[ $dbo::$profile['Accessor']['Primary'] ]);
				}
			}
		}
		return 'CLEAR';
	}

    function AlignMarkup()
    {
        $Children=array();

		$TemplateCount=count($this->ParentContainer->markup);
		if($this->Scripts != '')	RegisterScript($this->Scripts, $this->ScriptPlacement, $this->RenderType . ' ' . $this::$ComponentName);

		$TChildClasses = $this->ChildClasses;
		$TChildAttributes = $this->ChildAttributes;
		$TChildTag = $this->ChildTag;
		$this->ChildClasses = array();
		$this->ChildAttributes = array();
		$this->ChildTag = array();

		if( is_array($TChildClasses) )
		{
			if( is_array( reset($TChildClasses) ) )						$this->ChildClasses = array_values($TChildClasses);
			else{ for($i=0, $L=$TemplateCount; $i<$L; ++$i){			$this->ChildClasses[$i]=$TChildClasses;			}}
		}
		else{ for($i=0, $L=$TemplateCount; $i<$L; ++$i){				$this->ChildClasses[$i]=$TChildClasses;		}}

		if( is_array($TChildAttributes) )
		{
			if( is_array( reset($TChildAttributes) ) ){					$this->ChildAttributes = array_values($TChildAttributes);}
			else{ for($i=0, $L=$TemplateCount; $i<$L; ++$i){			$this->ChildAttributes[$i]=$TChildAttributes;			}}
		}
		else{ for($i=0, $L=$TemplateCount; $i<$L; ++$i){				$this->ChildAttributes[$i]=$TChildAttributes;		}}

		if( is_array($TChildTag) )										$this->ChildTag = $TChildTag;
		else{ for($i=0, $L=$TemplateCount; $i<$L; ++$i){				$this->ChildTag[$i]=$TChildTag;		}}

		/*	END ARRAY ALIGNMENT */
        return $TemplateCount;
    }
}

?>