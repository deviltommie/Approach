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

require_once('Render.php');


class Smart extends renderable
{
    public  $data;
    public  $markup=null;
    public  $context=Array();
    public  $tokens=Array();
    public  $TemplateBinding;
    public  $Scripts;
    public  $options=array();

    function Smart($t,$options)
    {
        $this->markup = GetFile($options['template_path']);

        $this->options = $options;
        global $renderObjectIndex;
        $this->id=$renderObjectIndex;
        $renderObjectIndex++;                /*    Register New Renderable    */
        if(!isset($t)){ $t='div'; }
        $this->tag = $t;
        $this->pageID = (isset($options['PageID']) ) ? $options['PageID'] : get_class($this) . $this->id;
        if(isset($options['classes']) )
        {
            $this->classes = $options['classes'];
        }


        if(isset($this->markup) && $this->markup != null)
        {

            $dataSet=simplexml_load_string($this->markup);
            $templateHeaders=$dataSet->xpath('//Component:*');

            foreach($templateHeaders as $template)
            {
               $this->TemplateBinding[$template->getName()] = json_decode($template,true);
               array_shift($dataSet);
            }
            unset($templateHeaders);
            $this->markup=$dataSet;

           /*
            $dataSet = ("•••••••••••••••••••••",$this->markup);
            $tokens=Array();
            $this->TemplateBinding = json_decode($dataSet[0], true);
            array_shift($dataSet); $this->markup = $dataSet;
           */
            //$this->markup = $dataSet[1];
        }

        $this->BindContext();
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
              }
            }

            $context['render']=$this->id;
            $context['data']=$TablesHolder;
            $context['options']=$this->options[$ActiveComponent];
            $context['template']=$this->options['template_path'];


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

      /* MASSIVE PERFORMANCE LOSS,  FIND OPTIMIZED ARRAY STRING REPLACE */
    function buildContent()
    {
        $this->Tokenize();
        $markupIndex=isset($this->options['markup']) ? $this->options['markup'] : 0;

        if(isset($this->data) && isset($this->markup))
        {
            foreach($this->tokens as $token => $value)
            {
                $this->markup[$markupIndex]=str_replace('['.$token.']', $value, $this->markup[$markupIndex]);
                $this->markup[$markupIndex]=$this->parse($this->markup[$markupIndex]);
            }
            $this->content = $this->markup[$markupIndex];
        }

        parent::buildContent();    //Render Children Into Content with normal build content function
    }
}




?>



