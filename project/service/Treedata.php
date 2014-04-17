<?php

header('Access-Control-Allow-Origin: *');




$input=array();

/*/**/
$input[]=array('Renderable','Variables','id','(integer)');
$input[]=array('Renderable','Variables','pageID','(string)');
$input[]=array('Renderable','Variables','tag','(string)');
$input[]=array('Renderable','Variables','attributes ','(paired strings)');
$input[]=array('Renderable','Variables','classes ','(paired strings)');
$input[]=array('Renderable','Variables','content ','(string)');
$input[]=array('Renderable','Variables','prefix ','(string)');
$input[]=array('Renderable','Variables','infix ','(string)');
$input[]=array('Renderable','Variables','postfix ','(string)');
$input[]=array('Renderable','Variables','children','(renderable array)');

$input[]=array('Renderable','Rendering','BuildAttributes','No Input');
$input[]=array('Renderable','Rendering','BuildClasses','No Input');
$input[]=array('Renderable','Rendering','BuildContent','No Input');
$input[]=array('Renderable','Rendering','Render','Input=Options');



$input[]=array('Dataset','Variables', 'Profile ','(string map)');
$input[]=array('Dataset','Variables', 'Primary Keys','(string)');
$input[]=array('Dataset','Variables', 'Linked Keys','(string)');
$input[]=array('Dataset','Variables', 'data','(keyed map matching profile');
$input[]=array('Dataset','Variables', 'options','(paired strings)');

$input[]=array('Dataset','Manage', 'global UpdateSchema','No Input, makes classfiles');
$input[]=array('Dataset','Manage', 'global LoadObject','Input=options');
$input[]=array('Dataset','Manage', 'global LoadObject','Input=options');
$input[]=array('Dataset','Manage', 'Dataset.load','Input=options');
$input[]=array('Dataset','Manage', 'Dataset.save','Input=constrained options');


$input[]=array('Smart','Variables','data','(key-Dataset)');
$input[]=array('Smart','Variables','context','(map)');
$input[]=array('Smart','Variables','tokens','(key-value)');
$input[]=array('Smart','Variables','binding','(key-value)');
$input[]=array('Smart','Variables','scripts','(string pairs)');
$input[]=array('Smart','Variables','options','(component options)');

$input[]=array('Smart','Smart Template','Constructor','Input=options');
$input[]=array('Smart','Smart Template','BindContext','No Input');
$input[]=array('Smart','Smart Template','Tokenize','No Input');
$input[]=array('Smart','Smart Template','BuildContent','No Input');
$input[]=array('Smart','Smart Template','Render','No Input');


$input[]=array('Component','Interface','CreateContext','Input=renderable,data,template');
$input[]=array('Component','Interface','HandleChildScripts','Input=child');
$input[]=array('Component','Interface','Consolidate','No Input');
$input[]=array('Component','Interface','Preprocess','Input=BuildData,ParentContainer');
$input[]=array('Component','Interface','Process','No Input');
$input[]=array('Component','Interface','Postprocess','Input=BuildData,ParentContainer');
$input[]=array('Component','Interface','Save','Input=IncomingTokens, TemplateBinding');
$input[]=array('Component','Interface','Edit','No Input');

$input[]=array('Component','Variables','RenderType ','(string)');
$input[]=array('Component','Variables','ChildTag ','(string)');
$input[]=array('Component','Variables','ContainerClasses ','(paired strings)');
$input[]=array('Component','Variables','ChildClasses','(paired strings)');
$input[]=array('Component','Variables','ChildAttributes','(paired strings)');
$input[]=array('Component','Variables','Scripts','(paired strings)');
$input[]=array('Component','Variables','ScriptPlacement','(boolean)');
$input[]=array('Component','Variables','ParentContainer','(renderable reference)');
$input[]=array('Component','Variables','Items','(Dataset Array)');
$input[]=array('Component','Variables','BuildData','(Items Map)');


$input[]=array('Composition','Composing','init','Input=options');
$input[]=array('Composition','Composing','ResolveComponents','No Input');
$input[]=array('Composition','Composing','Prepublish','Input=Root,Depth');
$input[]=array('Composition','Composing','Publish','Input=options');
$input[]=array('Composition','Variables','DOM','(renderable tree)');
$input[]=array('Composition','Variables','ComponentList','(component tree)');
$input[]=array('Composition','Variables','options','(paired strings)');
$input[]=array('Composition','Variables','PublicationID','(string)');
$input[]=array('Composition','Variables','Title','(string)');
$input[]=array('Composition','Variables','Blurb','(string)');
$input[]=array('Composition','Variables','Description','(string)');
$input[]=array('Composition','Variables','Thumbnail','(string)');



$input[]=array('Service','Variables','Directive','(string)');
$input[]=array('Service','Variables','Message','(map)');
$input[]=array('Service','Variables','Activity','(map)');
$input[]=array('Service','Variables','Response (xml,json,bin or string)');
$input[]=array('Service','Variables','Issues ','(paired strings)');
$input[]=array('Service','Variables','LiveComponent','(Component)');

$input[]=array('Service','Activity',' Constructor','Finds Message for Input');
$input[]=array('Service','Activity',' Authorize','Input=authorization, optional');
$input[]=array('Service','Activity',' Verify','Input=authorization, integrity check)');
$input[]=array('Service','Activity',' Receive','No Input, gets an Approach scope');
$input[]=array('Service','Activity',' Process','Activty (YOU MAKE THIS!)');
$input[]=array('Service','Activity',' Respond()','No Input, auto-encodes activity response');




/**//*

$input[]=array('Community','Cloud','Lifecycle');
$input[]=array('Community','Cloud','Resources');
$input[]=array('Community','Collab','Forums');
$input[]=array('Community','Collab','Chat');
$input[]=array('Community','Collab','Newsletter');
$input[]=array('Community','Collab','Contact Us');



$input[]=array('Community','Government','Rules');
$input[]=array('Community','Government','Elections');
$input[]=array('Community','Government','Contestants');
$input[]=array('Community','Government','Cabinet');
$input[]=array('Community','Government','Projects');


$input[]=array('Community','Sponsorship','Funding');
$input[]=array('Community','Sponsorship','Cloud OS');
$input[]=array('Community','Sponsorship','Market++');
$input[]=array('Community','Sponsorship','Co-op');
$input[]=array('Community','Sponsorship','Open Royalties');


$input[]=array('Prosperity','Enterprise','Platform Building');
$input[]=array('Prosperity','Enterprise','Advanced APIs');
$input[]=array('Prosperity','Enterprise','Team++');
$input[]=array('Prosperity','Enterprise','Reliable Crowdsource');
$input[]=array('Prosperity','Enterprise','Partnerships');
$input[]=array('Prosperity','Enterprise','Orchestration');
$input[]=array('Prosperity','Enterprise','Advanced Licensing');




$input[]=array('Prosperity','Marketplace','Apps!');
$input[]=array('Prosperity','Marketplace','Layouts');
$input[]=array('Prosperity','Marketplace','Components');
$input[]=array('Prosperity','Marketplace','Plugins');
$input[]=array('Prosperity','Marketplace','Utilities');
$input[]=array('Prosperity','Marketplace','Extensions');
$input[]=array('Prosperity','Marketplace','Mods');


$input[]=array('Prosperity','Infrastructure','Organic Modeling');
$input[]=array('Prosperity','Infrastructure','Cloud Anything');
$input[]=array('Prosperity','Infrastructure','Deploy Anywhere');



$input[]=array('Ingenuity','Developers','Join the Project!');
$input[]=array('Ingenuity','Developers','Docs');
$input[]=array('Ingenuity','Developers','Tutorials');
$input[]=array('Ingenuity','Developers','Blogs');
$input[]=array('Ingenuity','Developers','Ascending Approach');
$input[]=array('Ingenuity','Developers','Ascended Approach');

$input[]=array('Ingenuity','Developers','Join the Project!');
$input[]=array('Ingenuity','Designers','Layout');
$input[]=array('Ingenuity','Designers','Components');
$input[]=array('Ingenuity','Designers','Activities');
$input[]=array('Ingenuity','Designers','Cooperative');

$input[]=array('Ingenuity','Architects','Join the Project!');
$input[]=array('Ingenuity','Architects','Metasystems');
$input[]=array('Ingenuity','Architects','Optimizations');
$input[]=array('Ingenuity','Architects','Orchestration');
$input[]=array('Ingenuity','Architects','Domain Protocol');



$input[]=array('Ingenuity','Research','Standards To Go');
$input[]=array('Ingenuity','Research','Join the Group');
$input[]=array('Ingenuity','Research','The Sandbox');
$input[]=array('Ingenuity','Research','Join the Group');


*/


$TotalNodes =0;
class treenode
{
    public $id;
    public $value;
    public $links=array();
    public $count=0;

    function __construct()
    {
        global $TotalNodes;
        $this->id=$TotalNodes;
        $TotalNodes++;
    }
}

function treesert(&$branch, $values) //@arg links,values
{
    $head = array_shift($values);
    $result = false;
    foreach($branch as $leaf)
    {
        if($leaf->value == $head)
        {
            $result=true;
            $leaf->count++;
            if(count($leaf->links) > 0) treesert($leaf->links, $values);
        }
    }
    if(!$result)
    {
        $n=new treenode();
        $n->value=$head;
        if(count($values) > 0)
        treesert($n->links, $values);
        $branch[]=$n;
        $result=true;
    }
    return $result;
}

$trunk = array();
foreach($input as $i)
{
    treesert($trunk,$i);
}
print_r( json_encode($trunk) );



?>
