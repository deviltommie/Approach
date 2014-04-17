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

    function Smart($t='div',$pageID='',$options=array())
    {
        $this->id=renderable::$renderObjectIndex;
        renderable::$renderObjectIndex++;                /*    Register New Renderable    */

	if( is_array($t) ){ $options = $t; $this->tag= isset($t['tag']) ? $t['tag'] : 'div';}
        else $this->tag = $t;

	if( is_array($pageID) ){ $options = $pageID; $this->pageID= isset($pageID['pageID']) ? $pageID['pageID'] : get_class($this) . $this->id;}
        else $this->pageID = $pageID;

        if(isset($options['template'])) $this->template = GetFile($options['template']);
        if(isset($options['binding'])) $this->binding = GetFile($options['binding']);

        if(isset($options['pageID']) )  $this->pageID = $options['pageID'];
        if(isset($options['classes']) ) $this->classes = $options['classes'];
        if(isset($options['attributes'])) $this->attributes = $options['attributes'];
        if(isset($options['selfcontained'])) $this->selfContained = $options['selfcontained'];
        if(isset($options['content'])) $this->content = $options['content'] . $this->content;

		$this->options=$options;
		$this->ResolveTemplate();
        $this->BindContext();
    }

    public function ResolveTemplate()
    {
		if(isset($this->template) && $this->template != null)
		{
			$markup=array();
			$dataSet=simplexml_load_string($this->template);

			$TemplateBindings=$dataSet->xpath('//Component:*');
			$markupHeaders=$dataSet->xpath('//Render:*');

            foreach($TemplateBindings as $binding)
			{
				$this->TemplateBinding[$binding->getName()] = json_decode((string)$binding,true);
			}
			unset($TemplateBindings);
			foreach($markupHeaders as $mark)
			{
				$tmpStr=$mark->asXML();
				$markup[]=$tmpStr=substr($tmpStr,strpos($tmpStr,'>',15)+1,-16);
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
		if(gettype(reset($Component)) === gettype('string'))	$i++;
		foreach($Component as $Dataclass => $Properties)
		{
			$context['data'][]=$Dataclass;
			if(!isset($this->options[$ComponentName][$Dataclass]) && isset($this->options[$ComponentName]['*']) ) //Propagate options to all
				$this->options[$ComponentName][$Dataclass]=$this->options[$ComponentName]['*'];
			if(!isset($this->options[$ComponentName][$Dataclass]))
				$this->options[$ComponentName][$Dataclass] = array();
			//else	$this->options[$ActiveComponent][$Dataclass][] = $Table;
		}

		$context['self']=&$this;
		$context['render']=$this->id;
		$context['options']=$this->options[$ComponentName];
		$context['template']=$this->options['template'];

		$this->context[$ComponentName]=$context;
      }
    }

    public function Tokenize()
    {
      foreach($this->TemplateBinding as $ActiveComponent => $Dataclasses)
      {
        foreach($Dataclasses as $ActiveDataclass => $PropertyList)
        {
          foreach($PropertyList as $ActiveProperty => $NewToken)
          {
            $this->tokens[$NewToken]=$this->data[$ActiveComponent][$ActiveDataclass][$ActiveProperty];
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