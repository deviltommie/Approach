Approach-Core
=============

Flow, Compositing, Components, Services, Live CMS plus more for cloud enabled PHP applications.
Soon coming to Java/Droid, C++, C# and others.

Approach is a life-oriented, organic architecture for software.

Approach has been made with the helpful review of many passing developers who had worked with me on partnership projects with over 50 different companies and non-profits. Due to constant contributions from FlipSide Technologies, or my personal involvement, some developers freely contributed to a past proprietary version of Approach. 

Sincere thanks to all developers who have contributed until now. 

Much of Approach has been dissected and systematically deleted to allow re-implementation of features at intuitive levels delivering a new breed of user, developer, computer and organization friendly workflows all at once.


Competing paradigms abound in our industry:

- Proceedural (considered oldie but a goodie)
- Object Oriented
- Dataflow Oriented & Flow Driven
- Service Oriented Architecture
- Event Driven / Asynchronous
- Parallell Programming is in and out of all of these
- Recursion Oriented (I'm lookin' at you, LISPs)
- Component Oriented
- More being made all the time


Approach allows you to write applications using any mixture of these methods you like, or none at all. Well, except for procedural - technically all programs are. 

A good example: 
Approach does make use of classes of course. 
We mix in application produced and managed scopes.
You -could- very well access these purely from an object oriented methodology. 
You could have getters and setters for each property of each Component you ever create and use Component to enforce strict types on every type.

It's just that you don't have to. If you don't, Approach has some good and improving tricks that may be hard to notice that make the implicit explicit.
Who are we to decide which way you code? Sounds like the extension or mod-pack domain to us.

Approach has an architecture that is collapsible, replaceable, reverse-nesting capable, and embedding-capable. 

Approach enables, it does not orient. Organic systems only orient locally, and this is at the heart of Approach.


Finally, the many other language flavors coming. This is no joke, Approach is more architecture than code. 
We've already made at least 50% of Approach independantly in 4 languages over the last decade. Being the only author of renderable and primary designer for the gist of Approach, I ended up being the guy collecting different things, tearing them apart and putting and putting them back together. There's about 20 extension already planned and partly worked up to add different goals.

Between here and there, I'm very picky that we need to see what the community wants Approach to be and implement the core in a way that encourages novel features to be developed by multiple projects. We need a simple base to improve software ecosystems.

Feel free to ask for others, but if they aren't in that list you should just get my help while you write the fork! Don't worry, it's easy!

You can follow various forms of shameless self promotion as I start this project at 
https://twitter.com/ApproachGaret

Seriously, can't believe I would get a Twitter.. oh boy..
Soon you can also follow opinionated opinions at my blog at the Approach site.

Cheers, -Garet





<pre>

EXAMPLE COMPOSITION BUILDING WITH PLAIN RENDERABLES

<?php

$pub=new Composition($options); //this is an array of options
$pub->init($options); //options are optional. I think there's some issue you want to read the constructor for though


/*        Document Header Section        */

$head = new renderable('head');      //
$head->content='
    <meta http-equiv="content-type" content="text/html; charset=utf-8" >
    <title>Approach - An approach to organic, human oriented software</title>
    <link rel="stylesheet" type="text/css" href="YOUR_STYLESHEET_CHANGE_THIS_AHA.css" />
    <link href="http://fonts.googleapis.com/css?family=Signika:700,600,400,300|Quattrocento+Sans:400,700italic,400italic,700" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="http://static.approachfoundation.org/img/logo.png" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"> </script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"> </script>
    <script type="text/javascript" src="http://static.approachfoundation.org/Scripts/raphael-min.js"></script>
';

/*
 * THANK YOU to jQuery, RaphaelJS plus both the Signika and Quattrocento Sans fonts. 
 * Great work that is helping enable development!
 *
 */



//So I also show that you can add stuff through the pub->DOM. Instead of DOM it might be called "RenderTree"
//Here I just made a renderable and manually add it to the pub. You could do other things like
//$html->children[]=$head; or $APPROACH_DOM_ROOT->children[]=$head;
//Just depends on what you're keeping up with today. =)

$pub->DOM->children[] = $head;
//    Head is ready, 


/*        Body Section        */

//Gotta have a body. 
$body = new renderable('body');
$body->attributes['style']='background-color: #fff;';
$pub->DOM->children[]=$body;

//Ok I want a big page thing..
$canvas = new renderable('ul', 'canvas');

//usually you have one of these
$heading = new renderable('li', 'Navigation'); 

//and one of these, I bet you're used to them being divs though. You can if you want.
$page = new renderable('li', 'page');  

//Oh I'm just showing how lazy we can be here, really should have put this in the renderable options O_~!
$canvas->attributes['style']='background-color: white; position: absolute; left: 50%; margin-left: -540px; width: 1080px; height: 100%; padding-bottom: 22px;';

$theContent = new renderable('ul', 'theContent'); //(left-facing div, 678px wide)
$theSidebar = new renderable('ul', 'theSidebar'); //(right-facing div, (300 + 2x10 padding + 2x1 border) = 322px wide)

//page children can be added to variables you made, notice we haven't been using the $pub->DOM method?
$page->children[] = $theContent;
$page->children[] = $theSidebar;


//footer
$footer = new renderable('li', 'footer');

//PSCH, I dont need no stinkin' structure. Well fine! Have it your way:
$footer->content = '
    
    ANY HTML IN HERE, ALAS GITHUB'S MARKDOWN 
    IS NOT PLAYING NICE WITH EXAMPLE HTML
';



//canvas children, In Garet CSS, sheer just means no padding, no margin, no border.
//I tend to have containers that way and let inner groups have their way with formating
//I'm no style guru! YOU HAVE BEEN DISCLAIMERED
$canvas->classes[] = 'sheer';
$canvas->children[] = $heading;
$canvas->children[] = $page;
$canvas->children[] = $footer;

//Oh, did we forget about the body? Oh well no worries we can add stuff to it whenever.
$body->children[] = $canvas;


//And now you see may layout.php file. I include this at the top of most scripts and then just add stuff to
//$TheContent->children[]= smart or renderable(), bam you've got components.

$pub->publish();
//Options to send this to file, echo or just get components loaded.
//More options on the way for parallel and async stuff




//Did you know if you can understand this and get your database set up (with any database lib you want),
//Service classes can automatically find most anything in these Compositions individually or in mass, and you
//Can control how much data is being loaded or not, all sorts of bananas. 

?>

</pre>



