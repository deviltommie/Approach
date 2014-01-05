<?php

//require_once('../Service.php');
require_once(__DIR__ . '/../Render.php');
//require_once(__DIR__ . '/../DataObject.php');

$ApproachDisplayUnit = array();

$AppWizard = new renderable('div','',array('classes'=>'Wizard Interface') );
$AppWizard->children[0]=new renderable('div','',array('classes'=>'InterfaceLayout') );
$AppWizard->children[0]->children[0]= new renderable('ul','',array('classes'=>array('Header','controls')));
$AppWizard->children[0]->children[1]=new renderable('ul','',array('classes'=>array('Content','controls')));
$AppWizard->children[0]->children[2]=new renderable('ul','',array('classes'=>array('Footer','controls')));

$AppWizard->children[0]->children[0]->children[] = new renderable(array('tag'=>'li',
                                          'classes'=>array('Header','controls')
                                          )
                                    );
$AppWizard->children[0]->children[0]->children[0]->content='Complete action by following steps.';

$AppWizard->children[0]->children[2]->children[0]=new renderable('li','',array('classes'=>array('Cancel','DarkRed','Button')));
$AppWizard->children[0]->children[2]->children[0]->content='Cancel';

$AppWizard->children[0]->children[2]->children[1]=new renderable('li','',array('classes'=>array('Back','DarkGreen','Button'))   );
$AppWizard->children[0]->children[2]->children[1]->content='Back';

$AppWizard->children[0]->children[2]->children[2]=new renderable('li','',array('classes'=>array('Next','DarkGreen','Button'))   );
$AppWizard->children[0]->children[2]->children[2]->content='Next';

$AppWizard->children[0]->children[2]->children[3]=new renderable('li','',array('classes'=>array('Finish','DarkBlue','Button','Autoform','Insert','ACTION')) );
$AppWizard->children[0]->children[2]->children[3]->content='Finish';

$ApproachDisplayUnit['Publication']['NewWizard'] = $AppWizard;




$ApproachDisplayUnit['User']['Browser'] = new renderable('div');

?>