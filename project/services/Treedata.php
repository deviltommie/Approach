<?php

/* 	Not an Approach service. Not really a real service at all ha.
	Just for testing my array to trie and quick validating of 
	client messages 
	
	-GC
*/

header('Access-Control-Allow-Origin: *');

$input=array();


$input[]=array('Community','Organic Cloud','Service Lifecycle');
$input[]=array('Community','Organic Cloud','Public Cloud Resources');
$input[]=array('Community','Cloud Collab');
$input[]=array('Community','Community Government','Active Rulings');
$input[]=array('Community','Sponsorship');



$input[]=array('Prosperity','Enterprise','Platform Building');
$input[]=array('Prosperity','Enterprise','Advanced APIs');
$input[]=array('Prosperity','Enterprise','Team++');


$input[]=array('Prosperity','Marketplace','Value Proposition');
$input[]=array('Prosperity','Marketplace','Extensions');
$input[]=array('Prosperity','Marketplace','Addons + Plugins');
$input[]=array('Prosperity','Marketplace','Layouts');
$input[]=array('Prosperity','Marketplace','Components');


$input[]=array('Prosperity','Infrastructure','Organic Modeling');
$input[]=array('Prosperity','Infrastructure','Cloud Anything');
$input[]=array('Prosperity','Infrastructure','Deploy Anywhere');



$input[]=array('Ingenuity','Developers','Documentation');
$input[]=array('Ingenuity','Developers','Tutorials');
$input[]=array('Ingenuity','Developers','Deeper Approach');

$input[]=array('Ingenuity','Designers','Get Interactive');
$input[]=array('Ingenuity','Designers','Circulate Designs');

$input[]=array('Ingenuity','Architects','Metasystems');
$input[]=array('Ingenuity','Architects','Optimizers');

$input[]=array('Ingenuity','Research Group','Active Research');
$input[]=array('Ingenuity','Research Group','Standards To Go');
$input[]=array('Ingenuity','Open Ventures');



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