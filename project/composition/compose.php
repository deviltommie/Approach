<?php
require_once __DIR__.'/layout.php';

global $RuntimePath;
global $DeployPath;
global $StaticFiles;
global $APPROACH_EDITMODE;
$APPROACH_EDITMODE=false;

$StaticMarkupPath = $RuntimePath.'/support/templates/static/';
$TemplatePath = $RuntimePath.'/support/templates/';

if(Composition::$Active->Context['data']['id'] == 1)
      $Screen->classes[]='homepage';
$Screen -> children[] = new renderable(['tag'=>'li',
                                        'pageID'=>'MainHomeSlider',
                                        'attributes'=>['data-ride'=>'carousel'],
                                        'template'=>$StaticMarkupPath.'home_slider.htm']);

$Navigation->classes[]='HomeNav';

$Content -> children[] = new renderable(['tag'=>'li','pageID'=>'HomePageFeatures','template'=>$StaticMarkupPath.'frontpage-row.html']);



/*    Comment out after here  */
$options['tag']='li';
$options['ChildTag']='ol';
$options['classes']=['media-list'];
$options['template']=$TemplatePath.'MediaList.xml';
$options['MediaList']['target'] = 'types';

$Content -> children[] = $QuickContainer = new Smart($options);

/***/

?>