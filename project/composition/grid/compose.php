<?php
require_once __DIR__.'/../layout.php';

global $RuntimePath;
global $DeployPath;
global $StaticFiles;

foreach(Composition::$Active->Context['traversed'] as $node){ $DeployPath.= '/'.$node['alias'];}

$items = LoadObjects( 'compositions',array( 'method' => ' WHERE parent = '.Composition::$Active->Context['data']['id'] ));
$body->attributes['style']='background-color: #777 !important;';
$options=array();
$options['tag'] = 'li';
$options['template']=$RuntimePath . 'support/templates/AppCard.xml';

$info = new renderable(['tag'=>'li','content'=>'You are here: '.Composition::$Active->Context['data']['title']]);
$Screen->children[]=$info;

foreach($items as $item)
{
/*      $options['AppCard']['MobileApps'] = array(
            'method'=>
                  'WHERE `SourceCategory` = \''.Composition::$Active->Context['data']['alias'].
                  '\' AND `Publisher` = \''.$item->data['alias'].'\' GROUP BY `id` ASC',
            'condition'=>'LIMIT 8',
            );
*/      
      $Label=new renderable(array('tag'=>'li','classes'=>array('Label')));
//      $Container=new Smart($options);
      $Label-> content =
            '<h1 class="title" style="float:left;margin-bottom:11px;"><a href="'
             .'/'.$DeployPath.'/'.$item->data['alias']
             .'" style="display:inline-block; text-decoration: none;" class="round Button"> '
             .$item->data['title'].
            '</a></h1>';

      $Screen-> children[] = $Label;
//      $screen-> children[] = $Container;
}

?>