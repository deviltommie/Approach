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

require_once('/../__config_error.php');

$renderObjectIndex=0;

class renderable
{
    public $belongTo=null;
    public $id=null;
    public $pageID=null;

    public $tag='div';
    public $classes=Array();
    public $attributes=Array();
    public $content=null;        //If content and children both empty, tag is <selfcontained />
    public $children=Array();

    public $preFilter=null;
    public $postFilter=null;
    public $selfContained=false;

    function renderable($t='div', $PageID='', $options=array())
    {
        global $renderObjectIndex;
        $this->id=$renderObjectIndex;
        $renderObjectIndex++;                /*    Register New Renderable    */


	if( is_array($t) ){ $options = $t; $this->tag= $t['tag'];}
        else $this->tag = $t;
        if(isset($options['template'])) $this->content = GetFile($options['template']);
        $this->pageID = (isset($options['PageID']) ) ? $options['PageID'] : get_class($this) . $this->id;

        $this->pageID = $PageID;
        if(isset($options['PageID']) )  $this->pageID = $options['PageID'];
        elseif($this->pageID=='')       $this->pageID= 'render' . $this->id;

        if(isset($options['classes']) ) $this->classes = $options['classes'];
        if(isset($options['attributes'])) $this->attributes = $options['attributes'];
        if(isset($options['selfcontained'])) $this->selfContained = $options['selfcontained'];
        if(isset($options['content'])) $this->content = $options['content'] . $this->content;

    }

    public function buildClasses()
    {
        $classesToString='';
        if(is_array($this->classes) )
        {
            foreach($this->classes as $style)
            {
                $classesToString .= $style . ' ';
            }
          return $this->classes=' class="'.trim($classesToString).'" ';
        }
        elseif(is_string($this->classes))
        {
          return $this->classes = ' class="'.trim($this->classes).'" ';
        }
        else
        {
          return $this->classes=get_class($this) . $this->pageID . '';
        }

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
    global $RecurseCount;
    $depth=0;
    $Condition=Array();
    $Conditions=Array();
    $Saved=array();
    $begin = 0;

    $L=strlen($string);
    for($i=0; $i < $L; $i++)
    {
        if($string[$i]=='<')   //Start Of Tag Detected
        {
          if(substr($string, $i, 11) == '<?-- / --?>') //End Injector
          {
            $Condition['Close']['Start']=$i;
          }
          elseif(substr($string, $i, 4) == '<?--')    //Injector Detected
          {
            $Condition['Open']['Start']=$i;
          }
        }
        if($string[$i]=='>')  //End Of Tag Detected
        {
          if(substr($string, $i-10, 11) == '<?-- / --?>')
          {
              $Condition['Close']['End']=$i;
              $Evaluate = substr( $string, $Condition['Open']['Start']+4, $Condition['Open']['End']-$Condition['Open']['Start']-8);
              $Evaluate .= PHP_EOL . '{ return "true"; } '.PHP_EOL.'else{ return "false"; }';
              $Condition['result']=eval( $Evaluate );
          }
          elseif(substr($string, $i-3, 4) == '--?>')
          {
            $Condition['Open']['End']=$i;
          }
        }
    }

    foreach($Conditions as $Cursor => $Condition)
    {
      $Cursor = $Cursor + 0; //make int?

      //Cut Out the markup *between* any if statments
      $RecurseCount++;
      $InnerStatements=substr($string, $Condition['Open']['End']+1, $Condition['Close']['Start'] - $Condition['Open']['End']-1);
      if($Condition['result']=="false") $InnerStatements ='';
      else{$InnerStatements=$InnerStatements;}
      $string=str_replace(substr($string, $Condition['Open']['Start'], $Condition['Close']['End'] - $Condition['Open']['Start']+1 ), $InnerStatements, $string);

      if(strpos($InnerStatements, '<?--') != false) $Saved[]=$this->parse($InnerStatements);
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
                        $attribsToString .= $_att . ' ="'.$_val.'" ';
                    }
                    return $this->attributes=$attribsToString;
                }
                else $attribsToString .= $att . ' ="'.$val.'" ';
            }
            return $this->attributes=$attribsToString;
        }
        else if(is_string($this->attributes))
        {
            return $this->attributes;
        }
        else{$this->attributes = ' ERRORCODE="RENDERINGERROR" '; }
        return $this->attributes=$attribsToString;
    }

    public function render()
    {

        $OutputStream='';
        $FrontendMarkup=$this->content; $this->content='';

        $this->buildAttributes();
        $this->buildClasses();
        $this->buildContent();


        if($this->classes == ' class=" " ' || $this->classes == ' class="" ') $this->classes = '';
        if($this->attributes === 0){ $this->attributes = '';     }

        $pageID = ( isset($this->pageID) && ($this->tag != 'html' && $this->tag != 'head' && $this->tag != 'body' && $this->tag != 'script' && $this->tag != 'style' && $this->tag != 'channel' && $this->tag != 'rss' && $this->tag != 'item' ) ) ?  ' id="' . $this->pageID . '" ' : '';
        $this->classes = ( isset($this->classes) && ($this->tag != 'html' && $this->tag != 'head' && $this->tag != 'body' && $this->tag != 'script' && $this->tag != 'style' && $this->tag != 'channel' && $this->tag != 'rss' && $this->tag != 'item' ) ) ?  $this->classes : '';
        $this->attributes = ( isset($this->attributes) && ($this->tag != 'html' && $this->tag != 'head' && $this->tag != 'script' && $this->tag != 'style') ) ?  $this->attributes : '';

            if($this->tag == 'rss') $this->attributes = ' version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" ';

        $OutputStream = '<' . $this->tag . $this->attributes . $this->classes . $pageID ;
        if($this->selfContained) $OutputStream.=' />'."\n\r";
        else $OutputStream.= ' >' . $FrontendMarkup . $this->content . '</' . $this->tag . '>' . "\n\r";


        return $OutputStream;
    }
}



require_once('Utility.php');

?>
