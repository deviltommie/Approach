<?php

/*************************************************************************

 Title: Components for Approach
 Name: Component
 Original Author: Garet Claborn, garet@approachfoundation.org

 APPROACH 
 Organic, human driven software.


 COPYRIGHT NOTICE
 __________________

 (C) Copyright 2002-2013 - Approach Foundation LLC, Garet Claborn
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



require_once('/../base/Render.php');
require_once('/../base/Utility.php');
require_once('/../base/ClientEvents.php');
require_once('/../base/Smart.php');
require_once('/../base/Dataset.php');

class Component
{
    public $context = array();
    public $UpdateRow=array();

    function CreateContext($render,$data, $template)
    {
       $this->context['render']    = $render;
       $this->context['data']    = $data;
       $this->context['template'] = $template;
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
        /* -----------------------------------------*/
        /*  BEGIN FETCHING OPTIONAL APPROACH VALUES */

        global $ActiveComposition;
        global $APPROACH_DOM_ROOT;
        global $APPROACH_SAVE_FLAG;

        $root;
        if(isset($ActiveComposition))    $root = $ActiveComposition->DOM;
        else{                            global $$APPROACH_DOM_ROOT;       $root = $$APPROACH_DOM_ROOT;       }

        //Default Values
        $RenderType         = 'Smart';
        $ChildTag           = 'div';
        $ContainerClasses   = Array(get_class($this));
        $ChildClasses       = Array(get_class($this).'Child');
        $ChildAttributes    = Array('');
        $Items              = Array();
        $BuildData          = Array();
        $ParentContainer    = GetRenderable($root,$this->context['render']);
        $Scripts            = '';
        $this->ScriptPlacement = true;

        //Hard Coded Values
        if(isset($this->RenderType))        $RenderType         =$this->RenderType;
        if(isset($this->ChildTag))          $ChildTag           =$this->ChildTag;
        if(isset($this->ContainerClasses))  $ContainerClasses   =$this->ContainerClasses;
        if(isset($this->ChildClasses))      $ChildClasses       =$this->ChildClasses;
        if(isset($this->ChildAttributes))   $ChildAttributes    =$this->ChildAttributes;
        if(isset($this->Items))             $Items              =$this->Items;
        if(isset($this->Scripts))           $Scripts            =$this->Scripts;
        if(isset($this->ScriptPlacement))   $ScriptPlacement    =$this->ScriptPlacement;

        //Optional Overrides
        if(is_array($options))
        {
          foreach($options as $key => $value)
          {
              switch($key)
              {
                  case 'RenderType'          : $RenderType           = $value; break;
                  case 'ChildTag'            : $ChildTag             = $value; break;
                  case 'ContainerClasses'    : $ContainerClasses     = $value; break;
                  case 'ContainerAttributes' : $ContainerAttributes  = $value; break;
                  case 'ChildClasses'        : $ChildClasses         = $value; break;
                  case 'Items'               : $Items                = $value; break;
                  case 'Scripts'             : $Scripts              = $value; break;
                  case 'ScriptPlacement'     : $ScriptPlacement      = $value; break;
                  default: break;
              }
          }
        }

        /*  END FETCHING OPTIONAL VALUES    */
        /* ---------------------------------*/
        /*  BEGIN ARRAY ALIGNMENT           */


        foreach($this->context['data'] as $table)
        {
          $Items = LoadObjects($table,$options[$table]);
          $i=0;

          foreach($Items as $item)
          {
              foreach($item->data as $key=>$value)
              {
                $BuildData[$i][$table][$key] = $value;
              }
              if( isset($APPROACH_SAVE_FLAG[get_class($this)][$i]) )
              if( $APPROACH_SAVE_FLAG[get_class($this)][$i] == true )
              {
                $this->UpdateRow[$table] = $item->data;
              }
              $i++;
          }
      }

      $Children=array();
      $SmartObject = new $RenderType($ChildTag[0],array('template_path' => $this->context['template'], 'markup' => 0) );
      $TemplateCount=count($SmartObject->markup);
      RegisterScript($Scripts, $ScriptPlacement, $RenderType . ' ' . get_class($this));

      $TChildClasses = $ChildClasses;
      $ChildClasses = array();
      $TChildAttributes = $ChildAttributes;
      $ChildAttributes = array();
      $TChildTag = $ChildTag;
      $ChildTag = array();

      if( is_array($TChildClasses) )
      {
          if( is_array( reset($TChildClasses) ) )                      $ChildClasses = array_values($TChildClasses);
          else{ for($i=0, $L=$TemplateCount; $i<$L; $i++){              $ChildClasses[$i]=$TChildClasses;          }}
      }
      else{ for($i=0, $L=$TemplateCount; $i<$L; $i++){                  $ChildClasses[$i]=$TChildClasses;      }}

      if( is_array($TChildAttributes) )
      {    
          if( is_array( reset($TChildAttributes) ) ){                   $ChildAttributes = array_values($TChildAttributes);}
          else{ for($i=0, $L=$TemplateCount; $i<$L; $i++){              $ChildAttributes[$i]=$TChildAttributes;          }}
      }                                                                                                     //
      else{ for($i=0, $L=$TemplateCount; $i<$L; $i++){                  $ChildAttributes[$i]=$TChildAttributes;      }}

      if( is_array($TChildTag) )                                       $ChildTag = $TChildTag;
      else{ for($i=0, $L=$TemplateCount; $i<$L; $i++){                  $ChildTag[$i]=$TChildTag;      }}

      /*    END ARRAY ALIGNMENT */


      $this->PreProcess($BuildData, $ParentContainer);
      for($i=0, $L=$TemplateCount; $i<$L; $i++)
      {
        $c=0;
        foreach($BuildData as $ConsolidatedRow)
        {
          //Send Data From Database To Rendering Engine
          $SmartObject = new $RenderType($ChildTag[$i],array('template_path' => $this->context['template'], 'markup' => $i) );
          $SmartObject->tokens['__self_index']=$c;

          (isset($SmartObject->data[get_class($this)])) ? $SmartObject->data[get_class($this)]=array_merge($SmartObject->data[get_class($this)], $ConsolidatedRow) : $SmartObject->data[get_class($this)]=$ConsolidatedRow;
          $SmartObject->classes = (is_array($SmartObject->classes)) ? array_merge($SmartObject->classes, $ChildClasses[$i]) : $ChildClasses[$i];
          $SmartObject->attributes =(is_array($SmartObject->attributes)) ? array_merge($SmartObject->attributes, $ChildAttributes[$i]) : $ChildAttributes[$i];

          $Children[]=$SmartObject;
          $this->HandleChildScripts($SmartObject);
          $c++;
        }
      }

        $this->HandleScripts($ParentContainer);

        $SmartObject->Tokenize();
        $ParentContainer->children=array_merge($ParentContainer->children, $Children);

      $ParentContainer->classes = array_merge($ParentContainer->classes, $ContainerClasses);

      $this->PostProcess($BuildData, $ParentContainer);
    }

    function PreProcess($BuildData, $ParentContainer){ /* $ParentContainer->children[]= $t=new renderable('div'); $t->content=var_export($BuildData, true); */ }
    function PostProcess($BuildData, $ParentContainer){}

    function Save($IncomingTokens, $TemplateBinding)
    {
      $change = false;
      foreach($TemplateBinding as $Component => $Tables)
      {
        $ActiveComponent = $Component;
        foreach($Tables as $Name => $PropertyList)
        {
          $ActiveTable = $Name;
          if( isset($this->UpdateRow[$Name]) )
          {
            $Primary = '';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/Generator/DataObject/' . $Name . '.php';
            $dbo = new $Name($Name);

            foreach($this->UpdateRow[$Name] as $k => $v)
            {
                $dbo->data[$k] = $v;
            }

            foreach($PropertyList as $Column => $TokenName)
            {
              if( isset($dbo->data[$Column]) && isset($IncomingTokens['_'.$TokenName]) )
              {
                  $temp=$dbo->data[$Column];
                  $dbo->data[$Column] = $IncomingTokens['_'.$TokenName];
                  if($temp!=$dbo->data[$Column]) $change = true;
              }
            }

            if( $dbo->PrimaryKey == '+++PARENT+++' )
            {
                foreach($dbo->Columns as $_Name => $Table)
                {
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/Generator/DataObject/' . $_Name . '.php';
                    $origin = new $_Name($_Name);
                    if($origin->PrimaryKey != '+++PARENT+++')
                    {
                        $Primary = $origin->PrimaryKey;
                        break;
                    }
                    else
                    {
                        foreach($Table as $NameL2 => $TableL2)
                        {
                            require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/Generator/DataObject/' . $NameL2 . '.php';
                            $originL2 = new $NameL2($NameL2);
                            if($originL2->PrimaryKey != '+++PARENT+++')
                            {
                                $Primary = $originL2->PrimaryKey;
                                break;
                            }
                        }
                    }
                }
            }
            else
            {
                $Primary = $dbo->PrimaryKey;
            }
            if($change) $dbo->Save($dbo->data[$Primary]);
          }
        }
      }
      return 'CLEAR';
    }
}

class Massive extends Component
{
    public $RenderType = 'Smart';
    public $ChildTag = 'li';

    public $ChildClasses = array
    (
      'SlideMarkup' => array
      (
          'Slide'
      ),
      'ControlMarkup' => array
      (
          'ControlButton'
      )
    );

    public $ContainerClasses = array('Massive');
}

class Post extends Component
{
    public $RenderType = 'Smart';
    public $ChildTag = 'li';
    public $ChildClasses = array('nav','nav-pills', 'nav-stacked');
}

class BootstrapTable extends Component
{
    public $RenderType = 'Smart';
    public $ChildTag = 'tr';
    public $ContainerClasses = array('table','table-striped','table-bordered');

}
class BootstrapList extends Component
{
    public $RenderType = 'Smart';
    public $ChildTag = 'li';
    public $ContainerClasses = array('nav','nav-pills', 'nav-stacked');

}

//null

class Player extends Component
{
    function Player()
    {   global $ApproachHTML5;
        if($ApproachHTML5) // isHTML5 call in root.php sets this var. this var also goes to client side.
        {
            $this->ChildTag = array( 'video' , 'ul');
            $this->ChildAttributes = array
                             (
                                  'VideoMarkup' =>array
                                  (
                                        'width'=>'640',
                                        'height'=>'480',
                                        'align'=>'middle'

                                  ),
                                  'ControlMarkup' => array()
                             );
        }
        else
        {
            $this->ChildTag = array( 'object', 'ul');
            $this->ChildAttributes = array
                             (
                                  'VideoMarkup' =>array
                                  (
                                        'width'=>'640',
                                        'height'=>'480',
                                        'align'=>'middle',
                                        'classid'=>'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'

                                  ),
                                  'ControlMarkup' => array()
                             );
        }
    }
    public $RenderType = 'Smart';
    public $ChildClasses = array
                          (
                            'VideoMarkup' => array
                            (
                                'video'
                            ),
                            'ControlMarkup' => array
                            (
                                'controls'
                            )
                          );
    public $ContainerClasses = array('Player');
    public $ScriptPlacement = true;


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
    function HandleScripts($wrapper)
    {

    }

    function PostProcess($BuildData, $ParentContainer)
    {
        $r=new renderable('div');
        $r->content=var_export(/*$a*/$BuildData, true);
        $b->children[]=$r;
    }
}

class HomeDisplay extends Component
{
    public $ChildTag='ul';
}


class mRSS_Item extends Component
{
     public $ChildTag='item';
}

?>