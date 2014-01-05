<?php

/*
    Title: Smart Templating Class for Approach

    Copyright 2002-2014 Garet Claborn

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

require_once(__DIR__.'/Render.php');
require_once(__DIR__.'/Utility.php');
$Defaults['Renderable']='div';

class Smart extends renderable
{
    public  $data;
    public  $markup=array();
    public  $template=null;
    public  $context=Array();
    public  $tokens=Array();
    public  $TemplateBinding;
    public  $Scripts;
    public  $options=array();

    function Smart($options)
    {
	global $Defaults;
	global $renderObjectIndex;
        
        if(isset($options['template'])) $this->template = GetFile($options['template']) ;
        if(isset($options['binding'])) $this->binding = GetFile($options['binding']);

        $this->options = $options;
        $this->id=$renderObjectIndex;
        $renderObjectIndex++;                /*    Register New Renderable    */
        $options['tag']=$options['tag'];
	
	if(!isset($options['tag']) ){ $options['tag']=$Defaults['Renderable']; }
        $this->tag = $options['tag'];
        $this->pageID = (isset($options['PageID']) ) ? $options['PageID'] : get_class($this) . $this->id;
        if(isset($options['classes']) )
        {
            $this->classes = $options['classes'];
        }

	$this->ResolveTemplate();
        $this->BindContext();
    }
    
    public function ResolveTemplate()
    {
	if(isset($this->template) && $this->template != null)
        {
            $dataSet=simplexml_load_string($this->template);

            $TemplateBindings=$dataSet->xpath('//Component:*');	    
            $markupHeaders=$dataSet->xpath('//Render:*');

            foreach($TemplateBindings as $binding)
            {
               $this->TemplateBinding[$binding->getName()] = json_decode((string)$binding,true);
            }
            unset($TemplateBindings);

	    $markup=array();

            foreach($markupHeaders as $mark)
	    {
		//To Do: Bring Optimized XML Parser To Utility.
		$markup[]=strrev( explode('<',	strrev( explode('>',$mark->asXML(),2)[1]	),2)[1] );
	    }
            $this->markup = array_merge($this->markup,$markup);
        }
    }

    public function BindContext()
    {
      $ActiveComponent='';
      $i=0;   $IsComponent;
      
      foreach($this->TemplateBinding as $ComponentName => $Component)
      {
          $IsComponent=false;
          switch($ComponentName)
          {
             case '__scripts_head' : foreach($Component as $Name => $Script)
                                    {
                                        $this->Scripts['head'][$Name]= $Script;
                                    }
                                    break;
             case '__scripts_tail' : foreach($Component as $Name => $Script)
                                    {
                                        $this->Scripts['tail'][$Name]= $Script;
                                    }
                                    break;
             default: $IsComponent=true; break;

          }


          //End Special Cases For Template Sections
          if($IsComponent)
          {
            $ActiveComponent = $ComponentName;
            
	    if(gettype(reset($Component)) === gettype("string"))
            {
                $i++;
            }
	    /*print_r($ComponentName);
	    print_r($Component);
	    */
            foreach($Component as $Name => $Table)
            {	
	      $TablesHolder[]=$Name;
              if(!isset($this->options[$ActiveComponent][$Name]) && isset($this->options[$ActiveComponent]['ALL']) ) //Propagate options to all
              {
                  $this->options[$ActiveComponent][$Name]=$this->options[$ActiveComponent]['ALL'];
              }
              if(!isset($this->options[$ActiveComponent][$Name]))
              {
                  $this->options[$ActiveComponent][$Name] = array();
		//  $this->options[$ActiveComponent][$Name][] = $Table;		  
              }
	      //else{ $this->options[$ActiveComponent][$Name][] = $Table; }
            }

            $context['render']=$this->id;
            $context['data']=$TablesHolder;
            $context['options']=$this->options[$ActiveComponent];
            $context['template']=$this->options['template'];

            $this->context[$ActiveComponent]=$context;
          }
          else
          {
            unset($this->TemplateBinding[$ComponentName]);
          }
      }
    }

    public function Tokenize()
    {
      $ActiveComponent='';
      $ActiveTable='';

      foreach($this->TemplateBinding as $Component => $Tables)
      {
        $ActiveComponent = $Component;
        foreach($Tables as $Name => $PropertyList)
        {
          $ActiveTable=$Name;
          foreach($PropertyList as $Column => $NewToken)
          {
            $this->tokens[$NewToken]=
            $this->data[$ActiveComponent][$ActiveTable][$Column];
          }
        }
      }
    }

    /* PERFORMANCE LOSS,  FIND OPTIMIZED ARRAY STRING REPLACE */
    function buildContent()
    {
        $this->Tokenize();
        $markupIndex=isset($this->options['markup']) ? $this->options['markup'] : 0;

        if(isset($this->data) && isset($this->markup))
        {
            foreach($this->tokens as $token => $value)
            {
                $this->markup[$markupIndex]=str_replace('[@ '.$token.' @]', $value, $this->markup[$markupIndex]);
                $this->markup[$markupIndex]=$this->parse($this->markup[$markupIndex]);
            }
            $this->content = $this->markup[$markupIndex];
        }
        parent::buildContent();    //Render Children Into Content with normal build content function
    }
}

?>