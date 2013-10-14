<?php


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

$NavigationChains=array();
$NavigationChains[]=array('Approach', 'Direction');
/*$NavigationChains[]=array('Approach', 'Profile');
$NavigationChains[]=array('Approach', 'Projects');
$NavigationChains[]=array('Approach', 'Announcements');
$NavigationChains[]=array('Approach', 'Legal Stuff');
$NavigationChains[]=array('Approach', 'Investor Info');
$NavigationChains[]=array('Approach', 'Foundation Contact');
*/


$NavigationChains[]=array('Source++','Download');
/*$NavigationChains[]=array('Source++','Developers','Team++');
$NavigationChains[]=array('Source++','Developers','Tutorials');
$NavigationChains[]=array('Source++','Developers','Examples');
$NavigationChains[]=array('Source++','Developers','Reference');
$NavigationChains[]=array('Source++','Developers','Dev Blog');
$NavigationChains[]=array('Source++','Developers','Code With Us');

$NavigationChains[]=array('Source++','Designers','Team++');
$NavigationChains[]=array('Source++','Designers','Getting Started');
$NavigationChains[]=array('Source++','Designers','Key Concepts');
$NavigationChains[]=array('Source++','Designers','Executives, Nerds, Workflows and You');
$NavigationChains[]=array('Source++','Designers','Design With Us');

$NavigationChains[]=array('Source++','Producers','Team++');
$NavigationChains[]=array('Source++','Producers','Simplify Workflows');
$NavigationChains[]=array('Source++','Producers','Outlines Cross Boundries');
$NavigationChains[]=array('Source++','Producers','New Things You Can Do');
$NavigationChains[]=array('Source++','Producers','Scale & Scalability');
$NavigationChains[]=array('Source++','Producers','Product Design');
$NavigationChains[]=array('Source++','Producers','How To Make Your SLA');

$NavigationChains[]=array('Source++','Marketers','Team++');
$NavigationChains[]=array('Source++','Marketers','Why Your Should Care');
$NavigationChains[]=array('Source++','Marketers','Where You Fit In');
$NavigationChains[]=array('Source++','Marketers','Where We Fit In');
$NavigationChains[]=array('Source++','Marketers','Connecting Users');

$NavigationChains[]=array('Source++','Users','Installing Approach');
$NavigationChains[]=array('Source++','Users','Using The Approach Commander');
$NavigationChains[]=array('Source++','Users','Making a Publication');
$NavigationChains[]=array('Source++','Users','Adding Components');
$NavigationChains[]=array('Source++','Users','Community Plugins');
$NavigationChains[]=array('Source++','Users','Expanding your Services');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Building "Codeless" Apps');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Data Mapping');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Your First Component');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Your First Service');
$NavigationChains[]=array('Source++','Users','Advanced Topics','From HTML To The Cloud');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Service Chains');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Orchestration');
$NavigationChains[]=array('Source++','Users','Advanced Topics','Ascended Transformations');
*/
$NavigationChains[]=array('Cloud++','Marketplace');
/*$NavigationChains[]=array('Cloud++','Cloud Launch');
$NavigationChains[]=array('Cloud++','And.. What\'s So Organic?');
$NavigationChains[]=array('Cloud++','Cool Facts');
$NavigationChains[]=array('Cloud++','Intro Videos');
$NavigationChains[]=array('Cloud++','Apps That Friend');
*/
$NavigationChains[]=array('Team++','Topics');
/*$NavigationChains[]=array('Team++','Forums');
$NavigationChains[]=array('Team++','Projects');
$NavigationChains[]=array('Team++','Blogs');
$NavigationChains[]=array('Team++','Live Conferences');
$NavigationChains[]=array('Team++','Events');
$NavigationChains[]=array('Team++','Help us Grow');
$NavigationChains[]=array('Team++','Join Now!');
*/
$Navigation = array();

foreach($NavigationChains as $set){ treesert($Navigation,$set); }

$nav_it=0;
function PopulateNav(&$container, &$tree)
{
    global $nav_it;
    $nav_it=0;
    $i=$nav_it;
    foreach($tree as &$menu)
    {
        $MenuItem=new renderable('li', 'nav'.$i);
        $MenuLabel=(new renderable('a'));
        $MenuLabel->content=$menu->value;
        $SubMenu=new renderable('ul');
        $SubMenu->classes[]='SubNav';
        $SubMenu->classes[]='local_'.$i;
        $i++;
        $MenuItem->children[]=$MenuLabel;
        $MenuItem->children[]=$SubMenu;
	PopulateNav($SubMenu,$menu->links);
	
	$container->children[]=$MenuItem;
    }
}


?>