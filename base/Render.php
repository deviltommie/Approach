<?php

/*
	Title: Renderale Class for Approach


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

//require_once('/../__config_error.php');



class renderable
{
	public static $renderObjectIndex=0;
	public static $NoAutoRender=array('html', 'head', 'body', 'script', 'style', 'channel', 'rss', 'item','title');
	
	public $id=null;
	public $pageID='';

	public $tag='div';
	public $classes=Array();
	public $attributes=Array();
	public $content=null;		//If content and children both empty, tag is <selfcontained />
	public $children=Array();

	public $preFilter=null;
	public $postFilter=null;
	public $selfContained=false;

	function renderable($t='div', $pageID='', $options=array())
	{
		$this->id=renderable::$renderObjectIndex;
		renderable::$renderObjectIndex++;				/*	Register New Renderable	*/

	if( is_array($t) ){ $options = array_merge($t,$options); $this->tag= isset($options['tag']) ? $options['tag'] : 'div';}
		else $this->tag = $t;


	if( is_array($pageID) ){ $options = array_merge($pageID,$options); $this->pageID= isset($options['pageID']) ? $options['pageID'] : get_class($this) . $this->id;}
	else $this->pageID = $pageID;	
		
		if(isset($options['pageID']) )	$this->pageID = $options['pageID'];
		if(isset($options['template'])) $this->content = GetFile($options['template']);
		if(isset($options['classes']) ) $this->classes = $options['classes'];
		if(isset($options['attributes'])) $this->attributes = $options['attributes'];
		if(isset($options['selfcontained'])) $this->selfContained = $options['selfcontained'];
		if(isset($options['content'])) $this->content = $options['content'] . $this->content;
/*
	if($this->tag == 'rss')
	{
		$this->attributes['version'] = '2.0';
		$this->attributes['xmlns:media']='http://search.yahoo.com/mrss/';
		$this->attributes['xmlns:dc']='http://purl.org/dc/elements/1.1/';
		$this->attributes['xmlns:dcterms']='http://purl.org/dc/terms/';
	}
*/	
	if(in_array($this->tag,renderable::$NoAutoRender)) $this->pageID='';
	}

	public function buildClasses()
	{
		$classesToString='';
		if(is_array($this->classes) && count($this->classes) >0 )
		{
			foreach($this->classes as $style)
			{
				$classesToString .= $style . ' ';
			}
			return $this->classes=' class="'.trim($classesToString).'" ';
		}
		elseif(is_string($this->classes) && $this->classes != '')
		{
			return $this->classes = ' class="'.trim($this->classes).'" ';
		}
		elseif(in_array($this->tag,renderable::$NoAutoRender)){ return ''; }
		else
		{
			return ' class="'.get_class($this) .' '. get_class($this) .'_'.$this->id . '" ';
		}
//		if($this->classes == ' class=" " ' || $this->classes == ' class="" ') $this->classes = '';
//		 if($this->attributes === 0){ $this->attributes = '';	 }

	}
	public function buildContent()
	{
		foreach($this->children as $renderObject)
		{
			$this->content .= $renderObject->render();
		}
	}


	public function parse($string)
	{
	static $RecurseCount;
	static $depth=0;
	static $Condition=Array();
	static $Conditions=Array();
	static $Saved=array();
	static $begin = 0;
	static $found=false;
	

	$L=strlen($string);
	for($i=0; $i < $L; ++$i)
	{
		if($string[$i]=='<' && $string[$i+1]=='@' && $string[$i+2] == '-' && $string[$i+3]=='-')	//Start Of Tag Detected
		{
			if($string[$i+4]==' ' && $string[$i+5]=='/' && $string[$i+6]==' ' && $string[$i+7] == '-' &&
			 $string[$i+8] == '-' && $string[$i+9] == '@' && $string[$i+10] == '>') //End Injector ' / --@>'
			{
			$Condition['Close']['Start']=$i;
			$i=$Condition['Close']['End']=$i+10;

			$Evaluate = substr( $string, $Condition['Open']['Start']+4, $Condition['Open']['End']-$Condition['Open']['Start']-8);
			$Evaluate .= PHP_EOL . '{ return 1; } '.PHP_EOL.'else{ return -1; }';
			$Condition['result']=eval( $Evaluate );	//Template expression blocks: Danger, Warning, Danger!
			}
			else	//Injector Detected
			{
			$Condition['Open']['Start']=$i;
			$i+=3;
			}
			continue;
		}
		elseif($string[$i]=='-' && $string[$i+1]=='-' && $string[$i+2] == '@' && $string[$i+3]=='>')	//End Of Tag Detected
		{
			$i=$Condition['Open']['End']=$i+3;
			continue;
		}
	}

	foreach($Conditions as $Cursor => $Condition)
	{
		$Cursor = $Cursor + 0; //make int?

		//Cut Out the markup *between* any if statments
		$RecurseCount++;
		$InnerStatements=substr($string, $Condition['Open']['End']+1, $Condition['Close']['Start'] - $Condition['Open']['End']-1);
		if($Condition['result']==-1) $InnerStatements ='';
		
		$string=str_replace(substr($string, $Condition['Open']['Start'], $Condition['Close']['End'] - $Condition['Open']['Start']+1 ), $InnerStatements, $string);

		if(strpos($InnerStatements, '<@--') != false) $Saved[]=$this->parse($InnerStatements);
		$begin=$Condition['Close']['End'];
	}
	return $string;
	}


	public function buildAttributes()
	{
		$attribsToString=' ';
		if(is_array($this->attributes) )
		{
			foreach($this->attributes as $att=>$val)
			{
				if(is_array($val) )
				{
					foreach($val as $_att=>$_val)
					{
						$attribsToString .= $_att . '="'.$_val.'" ';
					}
					return $this->attributes=$attribsToString;
				}
				else $attribsToString .= $att . '="'.$val.'" ';
			}
			return $this->attributes=$attribsToString;
		}
		else if(is_string($this->attributes))	return ' '.$this->attributes.' ';	
		else	$attribsToString = ' data-approach-error="ATTRIBUTE_RENDER_ERROR" ';
		
		return $this->attributes=$attribsToString;
	}

	public function render()
	{
		$OutputStream='';
		$FrontendMarkup=$this->content;
		$this->content='';
		$this->buildContent();
 
		$OutputStream = '<' . $this->tag .
			( $this->pageID != ''		?	' id="'.$this->pageID.'" '	: '')	.
			( isset($this->attributes)	?	$this->buildAttributes()	: '')	.
			( isset($this->classes)		?	$this->buildClasses()		: '')	.
			($this->selfContained 		?	'/>'.PHP_EOL				: '>'	.
			$FrontendMarkup . $this->content . '</' . $this->tag . '>' . PHP_EOL);

		return $OutputStream;
	}
}



require_once(__DIR__.'/Utility.php');

?>
