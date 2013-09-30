<?

/*************************************************************************

 APPROACH 
 Organic, human driven software.

*************************************************************************/


/*

This file helps import youtube channels from users who authorize api access

TODO: 
Now that I have removed all of a particular client's logic from this file, I can keep the youtube part which is owned and licensed by youtube anyway.
So it would be useful to clean it back up and make it generic for everyone. 

This integrates an import wizard into your Live Console

You should note this is not only for importing. 
If we made it generic, just delete a few parts of the wizard with API calls and it is an "add new composition wizard".

Leave the API calls and change them slightly to have your publishing servers add things by communicating to -your- services.
Then when this sends requests to a service that doesn't exist yet; go catch it using Service::Process()

Easy, peasy. Now your site isn't so tangled up, but it can reflect itself all overs. oh potatos.

*/

require_once(__DIR__ . '/../Service.php');
require_once(__DIR__ . '/../Render.php');
require_once(__DIR__ . '/../DataObject.php');

require_once(__DIR__ . '/../Renderables/DisplayUnits.php');
require_once(__DIR__ . '/../Generator/DataObject/Compositions.php');
require_once(__DIR__ . '/../Generator/DataObject/Corporate_Users.php');

global $ApproachDisplayUnit;

$register = array( );
$register['Composition']=array();
$register['Component']=array();
$register['Asset']=array();
$register['Authorize']=array();
$register['Moderate']=array();
$register['Admin']=array();
$ID=0;

$register['Wizard']['Insert'] = function($arguments,$support)
{
  global $ApproachDisplayUnit;
  global $register;
  $DBO;

  foreach($arguments['Autoform']['Insert'] as $name => $database)
  {
    $name = str_replace('http://service.approachfoundation.org/','',$name);
     $DBO = new $name($name);

     foreach($database as $column => $entry)
     {
        $DBO->data[$column] = $entry;
     }

     $ID = $DBO->Save();
  }

  $opts = array('http' =>array(
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => 'apples'
  ));

$context  = stream_context_create($opts);
$result = file_get_contents('http://service.approachfoundation.org/Extension/Service/YouTube.php?youtubeid='.$DBO->data['archiveid'] .'&channelid='.$ID, false, $context);

  $WorkData['result'] = $result;
  $WorkData['render'] = '<h2>Status OK!</h2>';
  return $WorkData;
};

$register['Composition']['NewWizard'] = function($arguments, $support)
{
  global $ApproachDisplayUnit;
  global $register;

  $Wizard = $ApproachDisplayUnit['Composition']['NewWizard'];
  $ContentArea=$Wizard->children[0]->children[0];                 //Where A Wizard Unit's Content Markup Is By Default
  $Browser=$register['Composition']['Browser']($arguments, $support);


  $Slide = new renderable(array('tag'=>'li','classes'=>'Slide InputRegion'));
  $Slide->content = 'First Slide'/*$Browser['render']*/;
  $Pages[] = $Slide;

  $Slide = new renderable(array('tag'=>'li','classes'=>'Slide InputRegion'));
  $forms = new renderable(array(    'tag'=>'form',
                                    'classes'=> array(),
                                    'attributes'=>array('method'=>'post', 'action'=>'Corporate_Users') )
  );

  //creating inputs for form

  $spantainer2 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer2->content = '<br />Organization:';
  $spantainer3 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer3->content = '<br />First Name:';
  $spantainer4 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer4->content = '<br />Last Name:';
  $spantainer5 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer5->content = '<br />E-mail:';
  $spantainer6 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer6->content = '<br />Phone:';
  $spantainer7 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer7->content = '<br />Title:';

  //contacts


  $organization = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'organization')));
  $firstName = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'primaryfname')));
  $lastName = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'primarylname')));
  $email = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'primaryemail')));
  $phone = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'primaryphone')));
  $title = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'primarytitle')));


  $spantainer2->children[] = $organization;
  $spantainer3->children[] = $firstName;
  $spantainer4->children[] = $lastName;
  $spantainer5->children[] = $email;
  $spantainer6->children[] = $phone;
  $spantainer7->children[] = $title;

  $forms->children[] = $spantainer2;
  $forms->children[] = $spantainer3;
  $forms->children[] = $spantainer4;
  $forms->children[] = $spantainer5;
  $forms->children[] = $spantainer6;
  $forms->children[] = $spantainer7;
  //Get Settings Template for Compositions

  $Slide->children[] = $forms;
  $Pages[]=$Slide;

  $forms = new renderable(array(    'tag'=>'form',
                                    'classes'=> array(),
                                    'attributes'=>array('method'=>'post', 'action'=>'Compositions') )
  );
  $Slide = new renderable(array('tag'=>'li','classes'=>'Slide InputRegion'));

  $spantainer1 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer1->content = '<br /> Channel Name:';
  $spantainer2 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer2->content = '<br />Channel Description:';
  $spantainer3 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer3->content = '<br />Channel Tagline:';
  $spantainer4 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer4->content = '<br />Blurb:';
  $spantainer5 = new renderable(array('tag'=>'span','classes'=>array('spantainer')));
  $spantainer5->content = '<br />Youtube ID:';

  $channelName = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'alias')));
  $channelDescription = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'description')));
  $channelTitle = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'title')));
  $blurb = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'blurb')));
  $youtubeID = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'archiveid')));

  $spantainer1->children[] = $channelDescription;
  $spantainer2->children[] = $channelName;
  $spantainer3->children[] = $channelTitle;
  $spantainer4->children[] = $blurb;
  $spantainer5->children[] = $youtubeID;

  $forms->children[] = $spantainer1;
  $forms->children[] = $spantainer2;
  $forms->children[] = $spantainer3;
  $forms->children[] = $spantainer4;
  $forms->children[] = $spantainer5;

  $Slide->children[] = $forms;
  $Pages[]=$Slide;

  $channelDescription = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'channelDescription')));
  $channelName = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'channelName')));
  $channelTitle = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'channelTitle')));
  $blurb = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'blurb')));
  $youtubeID = new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'youtubeID')));

  $ContentArea->children = $Pages;
  $Wizard->children[0]->children[0] = $ContentArea;

  $WorkData['render'] = $Wizard->render();

  return $WorkData;
};



$register['Composition']['Browser'] = function($arguments, $support)
{
  global $ApproachDisplayUnit;

  $tempchild = new renderable('li');
  $tempchild->content = 'Sorry, there was an error.';

  $categories = LoadObjects('Categories', $options=Array('range'=>'*'));
  $CompositionCategories =array();

  $MiniDOM = new renderable('ul');

  if(isset($arguments['target']))
  {
  $Compositions = LoadObjects('Compositions', $options=Array('range'=>'*','condition'=>' Category EQUALS ' . $arguments['target']['category'] . ' ' ));
  foreach($Compositions as $Composition)
  {
    $nestedCompositions = LoadObjects('Compositions', $options=Array('range'=>'*','condition'=>' [Parent] EQUALS ' . $Composition['id'] . ' '));

    foreach($nestedCompositions as $childPub)
    {
        $nestlevel[$childPub['title']] = $childPub;
    }

    $ta->attributes['category'] =  $Composition['category'];
    $ta->attributes['publish']  =  $Composition['id'];
    $ta->attributes['title']    =  $Composition['title'];

    $tempchild=new renderable('li', '', array('classes'=>'Composition Browser ACTION', 'attributes'=>$ta) );

    $childContent = new renderable('input', 'Interface1'.$support['NestedLevel'], array('attribute'=>array('type'=>'checkbox')) );
    $childContent->children = new renderable('label','',array('attribute'=>array('for'=>'hmmmmmmmmmmmmmmmmmmm')));
    $tempchild->children[] = $childContent;
  }
  }
  else
  foreach($categories as $category)
  {
    if($category->data['id'] == 2)
    $CompositionCategories[]=$category->data;

    $ta =  $support['target']['category'];
    $ta =  $support['target']['publish'];
    $ta =  $support['target']['title'];

    $tempchild=new renderable('li', '', array('classes'=>'Composition Browser ACTION', 'attributes'=>$ta) );
  }

  $MiniDOM->children[]= $tempchild;

  $WorkData['render'] = $MiniDOM->render();
  return $WorkData;
};




$register['User']['Browser'] = function($arguments, $support)
{
  global $ApproachDisplayUnit;

  $child = new renderable('li');
  $child->content = 'Sorry, there was an error.';

  $categories = LoadObjects('Categories', $options=Array('range'=>'*'));
  $CompositionCategories =array();

  $MiniDOM = new renderable('ul');

  $data=$arguments['target']['owner'];

  $nestlevel = array();
  $rootPub;

  $OwnedProperty = explode(',',$data['owns']);
  $OwnedProperty = array_values($OwnedProperty);
  $Compositions = array();
  foreach($OwnedProperty as $Property)
  {
    $Property = $Property + 0;
    if($Property != 0) $Compositions[] = LoadObject('Compositions', array('condition'=>' [id] = '. $Property .' '));
  }

  foreach($Compositions as $Composition)
  {
    $ChildCompositions = LoadObjects('Compositions', array('range'=>'*','condition'=>' [Parent] = ' . $Composition->data['id'] . ' '));

    $attr['category'] =  $Composition->data['category'];
    $attr['publish']  =  $Composition->data['id'];
    $attr['title']    =  $Composition->data['title'];
    $attr['isroot']    = $Composition->data['root'];


    $rootPub=new renderable(array('tag'=>'li','classes'=>'Composition Browser ACTION', 'attributes'=>$attr) );
    $rootContent = new renderable(array('tag'=>'input','selfcontained'=>true,'attributes'=>array('type'=>'checkbox')) );
    $rootPub->children[] = $rootContent;
    $rootContent = new renderable(array('tag'=>'label','content'=>$Composition->data['alias'],'attributes'=>array('for'=>$rootContent->pageID)));
    $rootPub->children[] = $rootContent;

    if(count($ChildCompositions)> 0 )
    {
      $branch = new renderable('ul');
      foreach($ChildCompositions as $ChildPub)
      {
          $GrandchildCompositions = LoadObjects('Compositions', array('range'=>'*','condition'=>' [Parent] = ' . $ChildPub->data['id'] . ' '));

          $attr['category'] =  $ChildPub->data['category'];
          $attr['publish']  =  $ChildPub->data['id'];
          $attr['title']    =  $ChildPub->data['title'];

          $child=new renderable(array('tag'=>'li','classes'=>'Composition Browser ACTION', 'attributes'=>$attr) );
          $childContent = new renderable(array('tag'=>'input','selfcontained'=>true,'attributes'=>array('type'=>'checkbox')) );
          $child->children[] = $childContent;
          $childContent = new renderable(array('tag'=>'label','content'=>$ChildPub->data['alias'],'attributes'=>array('for'=>$childContent->pageID)));
          $child->children[] = $childContent;



          if(count($GrandchildCompositions)> 0 )
          {
            $branch2 = new renderable('ul');
            foreach($GrandchildCompositions as $GrandchildPub)
            {
                $attr['category'] =  $GrandchildPub->data['category'];
                $attr['publish']  =  $GrandchildPub->data['id'];
                $attr['title']    =  $GrandchildPub->data['title'];

                $grandchild=new renderable(array('tag'=>'li','classes'=>'Composition Browser ACTION', 'attributes'=>$attr) );
                $grandchildContent = new renderable(array('tag'=>'input','selfcontained'=>true,'attributes'=>array('type'=>'checkbox')) );
                $grandchild->children[] = $grandchildContent;
                $grandchildContent = new renderable(array('tag'=>'label','content'=>$GrandchildPub->data['alias'],'attributes'=>array('for'=>$grandchildContent->pageID)));
                $grandchild->children[] = $grandchildContent;
                $branch2->children[]=$grandchild;
            }
            $child->children[]=$branch2;
          }
          $branch->children[]=$child;
        }
        $rootPub->children[]=$branch;
      }
      $MiniDOM->children[]= $rootPub;
  }

  $WorkData['render'] = $MiniDOM->render();
  return $WorkData;
};








$register['Composition']['FeedWizard'] = function($arguments, $support)
{
  global $ApproachDisplayUnit;
  return $ApproachDisplayUnit["Composition"]["FeedWizard"];
};

$register['Composition']['NewsLetterWizard'] = function($arguments, $support)
{
  global $ApproachDisplayUnit;
  return $ApproachDisplayUnit["Composition"]["NewWizard"];
};



//-----------------------------------------------------------------------------------
$register['Component']['Update']= function($arguments, $support)
{
  $WorkingData['container'] = GetRenderableByPageID($support['context']['Composition']->DOM, $arguments['PageID']);

  foreach($WorkingData['container']->children as $Individual)
  {
      if($Individual->tokens['__self_index'] == $arguments['ChildRef'])
          $WorkingData['render'] = $Individual;
  }

  $WorkingData['render']->markup = GetFile($this->LiveComponent['template']);
  $dataSet = explode("•••••••••••••••••••••\r\n",$WorkingSet['render']->markup);
  $WorkingData['render']->TemplateBinding = json_decode($dataSet[0], true);
  array_shift($dataSet);
  $WorkingData['render']->markup = $dataSet;
  $WorkingData['render']->BindContext();
  $WorkingData['render']->buildContent();

  foreach($arguments['tokens'] as $token => $newValue)
  {
      $WorkingData['render']->tokens[substr($token,1)] = $newValue;
  }

  $feedback = $support['ActiveComponent']->Save($arguments['tokens'], $WorkingData['container']->TemplateBinding);

  if($feedback = 'CLEAR') $success = true;
  $response['refresh'][$WorkingData['render']->pageID] = $WorkingData['render']->render();

  return $WorkData;
};


$ApproachRegisteredService = $register;
?>