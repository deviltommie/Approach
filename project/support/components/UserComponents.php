<?php
require_once(__DIR__.'/../../core.php');

class AppCard extends Component
{
	public static $ComponentName='AppCard';
	public $RenderType = 'Smart';
	public $ChildTag = 'ul';
	public $ChildClasses=array('round','AppCard');

	public $ContainerClasses = array('HorizontalList');
}

class Massive extends Component
{
	public static $ComponentName='Massive';
	public $RenderType = 'Smart';
	public $ChildTag = 'li';

	public $ChildClasses = array
	(
		'SlideMarkup' => array
		(
			'Slide'
		),
		'ControlMarkup' => array
		(
			'ControlButton'
		)
	);

	public $ContainerClasses = array('Massive');
}

class Post extends Component
{
	public static $ComponentName='Post';
	public $RenderType = 'Smart';
	public $ChildTag = 'li';
	public $ChildClasses = array('nav','nav-pills', 'nav-stacked');
}

class BootstrapTable extends Component
{
	public static $ComponentName='BootstrapTable';
	public $RenderType = 'Smart';
	public $ChildTag = 'tr';
	public $ContainerClasses = array('table','table-striped','table-bordered');

}
class BootstrapList extends Component
{
	public static $ComponentName='BootstrapList';
	public $RenderType = 'Smart';
	public $ChildTag = 'li';
	public $ContainerClasses = array('nav','nav-pills', 'nav-stacked');

}

//null

class Player extends Component
{
	public static $ComponentName='Player';
	function Player()
	{	 global $ApproachHTML5;
		if($ApproachHTML5) // isHTML5 call in root.php sets this var. this var also goes to client side.
		{
			$this->ChildTag = array( 'video' , 'ul');
			$this->ChildAttributes = array
			(
				 'VideoMarkup' =>array
				 (
						 'width'=>'640',
						 'height'=>'480',
						 'align'=>'middle'

				 ),
				 'ControlMarkup' => array()
			);
		}
		else
		{
			$this->ChildTag = array( 'object', 'ul');
			$this->ChildAttributes = array
			(
				 'VideoMarkup' =>array
				 (
						 'width'=>'640',
						 'height'=>'480',
						 'align'=>'middle',
						 'classid'=>'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'

				 ),
				 'ControlMarkup' => array()
			);
		}
	}
	public $RenderType = 'Smart';
	public $ChildClasses = array
			(
				'VideoMarkup' => array
				(
					'video'
				),
				'ControlMarkup' => array
				(
					'controls'
				)
			);
	public $ContainerClasses = array('Player');
	public $ScriptPlacement = true;


	function HandleChildScripts($child)
	{
		if(isset($child->Scripts['head']))
		{
			foreach($child->Scripts['head'] as $Name => $Script)
			{
				RegisterScript($Script, true, $Name);
			}
		}
		if(isset($child->Scripts['tail']))
		{
			foreach($child->Scripts['tail'] as $Name => $Script)
			{
				RegisterScript($Script, false, $Name);
			}
		}
	}
	function HandleScripts($wrapper)
	{

	}

	function PostProcess(&$BuildData){	}
}

class HomeDisplay extends Component
{
	public static $ComponentName='HomeDisplay';
	public $ChildTag='li';
	public $RenderType = 'Smart';
	public $ChildClasses = array('nav','nav-pills', 'nav-stacked');

}

class mRSS_Item extends Component
{
	public static $ComponentName='mRSS_Item';
	 public $ChildTag='item';
}

class MediaList extends Component
{
	public static $ComponentName='MediaList';
	public $ChildTag='ul';
	public $RenderType = 'Smart';

	public $ContainerClasses = array('MediaList', 'media-list');
}

?>