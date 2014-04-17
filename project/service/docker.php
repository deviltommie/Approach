<?php


require_once(__DIR__ .'/../core.php');
require_once($InstallPath.'/core/Service.php');
require_once($RuntimePath.'service/Registrar.php');

error_reporting(E_ALL);

global $ApproachRegisteredService;
global $ApproachDisplayUnit;
$SiteRoot = $InstallPath;

//Manually setting properties from DisplayUnits, not best practice - but fair and much faster than lookups.
//Use any key, I used a Component name so I can reflect on Components which in turn know their Datasets. (safety)
//This DisplayUnit's "Finish" button will trigger a service reaching the following function:
$ApproachDisplayUnit['Instance']['Server.Wizard.New'] = clone $AppWizard;
//0                 InterfaceLayout
//0->1,0->2,0->3    Header, Content, Footer
//
$ApproachDisplayUnit['Instance']['Server.Wizard.New']->children[0]->children[2]->children[3]->classes[4]='Platform_Orchestration_Docker'; 
$ApproachRegisteredService['Autoform']['Platform.Orchestration.Docker']= function($arguments, $support)
{
    
    $opt = array();
    $opt['tag'] = 'div';
    $opt['content'] = '<pre>'.var_export(array_merge($arguments,$support), true) .'</pre>';
    
    $r['render'] = (new renderable( $opt ))->render();
    $r['APPEND']['#Dynamics'] = $r['render'];
    
    return $r;
};



//Now I link the Server.Wizard.New unit to the responder for 

$ApproachRegisteredService['Instance']['Instance.New']= function($arguments, $support)
{
 global $ApproachDisplayUnit;
 $result=array();
 $r = new renderable('div');
 $r->content = '<hr noshade /> running ls -ln <br /><pre>' . `ls -ln` . '</pre><br /> process successful <hr noshade />';
 
 

  $Wizard = $ApproachDisplayUnit['Instance']['Server.Wizard.New'];
  $ContentArea=$Wizard->children[0]->children[1];                 //Where A Wizard Unit's Content Markup Is By Default
  //$Browser=$register['Instance']['Browser']($arguments, $support);


  $Slide = new renderable(array('tag'=>'li','classes'=>'active Slide InputRegion', 'content'=>'First Slide') );   /*$Browser['render']*/
  $Pages[] = $Slide;

  $Slide = new renderable(array('tag'=>'li','classes'=>'Slide InputRegion'));
  $forms = new renderable(array(    'tag'=>'form',
                                    'classes'=> array(),
                                    'attributes'=>array('method'=>'post', 'action'=>'DataStack') )
  );

  //creating inputs for form

  //Data Driver?
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Data Driver:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'select','classes'=>array(),'attributes'=>array('type'=>'text','name'=>'db_driver_class')));
  
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'oracle_jdbc_driver_OracleDriver'),'content'=>'Oracle 9'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_mysql_jdbc_Driver'),'content'=>'MySQL 5'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_mssql_jdbc_Driver'),'content'=>'SQL Server 2008+'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'none'),'content'=>'none'));
  
  //Data Dialect?
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Data Dialect:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'select','classes'=>array(),'attributes'=>array('name'=>'db_dialect_class')));
  
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_sustain_util_hibernate_dialect_Oracle9iDialectExt'),'content'=>'Oracle 9i'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_sustain_util_hibernate_dialect_MySQL5InnoDBDialectExt'),'content'=>'MySQL5 + InnoDB'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'none'),'content'=>'none'));
  
  //Audit Prefix?
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Audit Prefix:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','size'=>'32','name'=>'db_auditTablePrefix','value'=>'none')) );
  
  //Database Schema?
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Data Schema:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'select','classes'=>array(),'attributes'=>array('name'=>'db_schema')));
  
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_sustain_util_hibernate_dialect_Oracle9iDialectExt'),'content'=>'LACONFIG'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com_sustain_util_hibernate_dialect_MySQL5InnoDBDialectExt'),'content'=>'Default'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'attachment'),'content'=>'Attached'));
        //Database Schema >> Upload Schema (Format Exception)
        end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array('upload','mediabrowse'),'attributes'=>array('type'=>'file','size'=>'32','name'=>'db_schema_attachment0')) );
    
  //Naming Strategy?
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content'=>'<br />Naming Strategy: ') );
  end($forms->children)->children[]=new renderable(array('tag'=>'select','classes'=>array(),'attributes'=>array('name'=>'db_naming_strategy_class')));
  
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com.sustain.util.hibernate.OracleNamingStrategy'),'content'=>'Hibernate + Oracle Conventions'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'com.sustain.util.hibernate.MySQLNamingStrategy'),'content'=>'Hibernate + MySQL Conventions'));
  end(end($forms->children)->children)->children[]=new renderable(array('tag'=>'option','attributes'=>array('value'=>'none'),'content'=>'none'));
  
  //Server URL?
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content'=>'<br />Database Address: ') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','size'=>'28','value'=>'localhost','name'=>'db_url')));
    
/*  ^^^ ToDo: Convert Each Slide to a Display Unit and/or Template File vvv    */    
    
  $Slide->children[] = new renderable(array('tag'=>'h2','content'=>'Database Settings'));
  $Slide->children[] = $forms;
  $Pages[]=$Slide;

/*   Next Slide       */

  $Slide = new renderable(array('tag'=>'li',    'classes'=>'Slide InputRegion'));
  $forms = new renderable(array(    'tag'=>'form',
                                    'classes'=> array(),
                                    'attributes'=>array('method'=>'post', 'action'=>'Environment') )
  );
  
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Admin Password:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'password','size'=>'32','name'=>'admin_password')));
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Environment Name:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','size'=>'32','value'=>'balling-out-on-docker','name'=>'env_name')));
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Environment Color:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array('color'),'attributes'=>array('type'=>'text','name'=>'env_color','value'=>'lakers')));
  
  $Slide->children[] = new renderable(array('tag'=>'h2','content'=>'Account and Environment Settings'));
  $Slide->children[] = $forms;
  $Pages[]=$Slide;
  
/*   Next Slide       */

  $Slide = new renderable(array('tag'=>'li',    'classes'=>'Slide InputRegion'));
  $forms = new renderable(array(    'tag'=>'form',
                                    'classes'=> array(),
                                    'attributes'=>array('method'=>'post', 'action'=>'Monitor') )
  );
  
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Status Service Host:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','size'=>'32','name'=>'statsd_host','value'=>'cloudcity.rileytg.com')));
  
  $forms->children[] = new renderable(array('tag'=>'span','classes'=>array('autoform'), 'content' => '<br />Status Service Port:') );
  end($forms->children)->children[]=new renderable(array('tag'=>'input','classes'=>array(),'attributes'=>array('type'=>'text','size'=>'32','name'=>'statsd_port','value'=>'8125')));
  
  $Slide->children[] = new renderable(array('tag'=>'h2','content'=>'Application Monitoring'));
  $Slide->children[] = $forms;
  $Pages[]=$Slide;
  
 
  
 $ContentArea->children = $Pages;
 $Wizard->children[0]->children[1] = $ContentArea;
 
 $result['render']=$r->render();
 $result['APPEND']['body']=$Wizard->render();
 return $result;
};



$ApproachRegisteredService['Instance']['Template.New']= function($arguments, $support)
{

 $result=array();
 $r = new renderable('div');
 $r->content = '<hr noshade /> running ls <br /><pre>' . `ls ` . '</pre><br /> process successful <hr noshade />';


 $result['render']=$r->render();
 return $result;
};



?>