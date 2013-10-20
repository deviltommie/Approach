<?php

/*
	Title: Renderale Utility Functions for Approach


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

$html = new renderable('html');
$html->tag='html';
$APPROACH_DOM_ROOT = 'html';    //Create better mechanism.


/*
$rss = new renderable('rss');
$rss->tag='rss';




These functions let you primarily search through types of class renderable by
common CSS selectors such as ID, Class, Attribute and Tag. 

Also the JavaScript Events have a require listed at the bottom of this source
JavaScript events need to look for your </head> element *or* the  </body> elemenet
and dynamically place event bindings, script linking or direct code at these 
locations.


Use 

$Collection = RenderSearch($anyRenderable,'.Buttons'); 

Or Directly


$SingleTag=function GetRenderable($SearchRoot, 1908);                       //System side render ID $renderable->id;
$SingleTag=function GetRenderableByPageID($root,'MainContent');             //Client side page ID

$MultiElements=function GetRenderablesByClass($root, 'Buttons');
$MultiElements=function GetRenderablesByTag($root, 'div');


*/


function filter( $tag, $content, $styles, $properties)
{
    $output="<" . $tag;
    foreach($this->$properties as $property => $value)
    {
        $output .= " $property=\"$value\"";
    }
    $output .= " class=\"";
    foreach($this->$styles as $class)
    {
        $output .= $class. " ";
    }
    $output .= "\" id=\"$tag\"" . $this->$id . '">';
    $output .=$content . "</$tag>";
}





//function _($root, $search){    return RenderSearch($root, $search); }
function RenderSearch($root, $search)
{
    $scope = $search[0];
    $search = substr($search, 1);
    $renderObject;
    switch($scope)
    {
        case '$': $renderObject=GetRenderable($root, $search); break;
        case '#': $renderObject=GetRenderableByPageID($root, $search); break;
        case '.': $renderObject=GetRenderablesByClass($root, $search); break;
        default:  $renderObject=GetRenderableByTag($root, $search); break;
    }

    return $renderObject;
}

function GetRenderable($SearchRoot, $SearchID)
{
    if($SearchRoot->id == $SearchID) return $SearchRoot;

    foreach($SearchRoot->children as $renderObject)
    {
            $result = GetRenderable($renderObject,$SearchID);
            if($result instanceof renderable)
            {
                if($result->id == $SearchID) return $result;
            }
    }
}



function GetRenderablesByTag($root, $tag)
{
    $Store=Array();

    foreach($root->children as $child)   //Get Head
    {
        if($child->tag == $tag)
        {
            $Store[]=$child;
        }
        foreach($child->$children as $children)
        {
            $Store = array_merge($Store, GetRenderablesByTag($children, $tag));
        }
    }
    return $Store;
}

function GetRenderablesByClass($root, $class)
{
    $Store = array();

    foreach($root->children as $child)   //Get Head
    {
        $t=$child->classes;
        $child->buildClasses();

        if(strpos($child->classes,$class))
        {
            $Store[]=$child;
        }
        foreach($child->children as $children)
        {
            $Store = array_merge($Store, GetRenderablesByClass($children, $class));
        }
        $child->classes=$t;
    }
    return $Store;
}

function GetRenderableByPageID($root,$PageID)
{
    $Store = new renderable('div');
    $Store->pageID = 'DEFAULT_ID___ELEMENT_NOT_FOUND';
    foreach($root->children as $child)   //Get Head
    {
        if($child->pageID == $PageID)
        {
            $Store = $child;
            return $child;
        }
        foreach($child->children as $children)
        {
            $Store = GetRenderableByPageID($children, $PageID);
            if($Store->pageID == $PageID) return $Store;
        }
    }
    return $Store;
}



function GetHeadFromDOM()
{
  global $APPROACH_DOM_ROOT;
  global $$APPROACH_DOM_ROOT;

  foreach($$APPROACH_DOM_ROOT->children as $child)   //Get Head
  {
      if($child->tag == 'head')
      {
          return $child;
      }
  }
}

function GetBodyFromDOM()
{
  global $APPROACH_DOM_ROOT;
  global $$APPROACH_DOM_ROOT;

  foreach($$APPROACH_DOM_ROOT->children as $child)   //Get Body
  {
      if($child->tag == 'body')
      {
          return $child;
      }
  }
}

$ApproachDebugConsole = new renderable('div', 'ApproachDebugConsole');
$ApproachDebugMode = false;

require_once('ClientEvents.php');

?>
