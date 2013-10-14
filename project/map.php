<?


require_once( '../../approach/core.php');

$DefaultCategory = 'learn';
global $ActivePublication;
global $ActivePublicationContext;
global $ApproachServiceCall;

function RouteFromURL($url, $silent=false)
{
    global $SiteRoot;
    global $DefaultCategory;
    global $ActivePublication;
    global $ActivePublicationContext;

    /* Set up environment */

    $ActivePublication = array();
    $ActivePublicationContext['path'] = '';

    $found = false;
    $Category;
    $Possible;
    $CategorySearch;
    $CategoriesSearch;
    $Publication;


    if(!isset($url)) $url = $_SERVER['REQUEST_URI'];
    $url = urldecode($url);

    $exts=array('.aspx','.asp','.jsp','.php','.html','.htm','.rhtml','.py','.cfm','.cfml', '.cpp', '.c', '.ruby','.dll', '.asm');
    $url = str_replace($exts, '', $url);
    $AppPath = explode('/',$url);

    for($i=0, $L=count($AppPath); $i<$L; $i++)
    {
        if($AppPath[$i] == '' || empty($AppPath[$i])){ unset($AppPath[$i]); continue; }
        else $AppPath[$i] = strtolower($AppPath[$i]);
    }

    $AppPath = array_values($AppPath);

    




    /*  Home Page   */
    if($url == '/' ||$url == '/home' || $url == '/index' || $url == '/default' )
    {
        $options=array();
        //$options['condition']= '[id] = 1 AND [root] = 1';
        $options['command']= 'SELECT * ';
        
        $Publication = LoadObject('nodes', $options);

        $ActivePublicationContext['path'] .= 'compose.php';        
        //$ActivePublicationContext['data'] = $Publication->data;

        $Active_Publication = new Composition();
        
        require_once(__DIR__ . '/'.$ActivePublicationContext['path']);
        $Active_Publication->publish($silent);
        
        $ActivePublication = $Active_Publication;
        $ActivePublicationContext['self'] = $ActivePublication;
        return $Active_Publication;
    }

    /*  Dynamic Nested Publications */
    $options = array();
    
     /* User Page */
     if($AppPath[0] == 'users' )
    {
        $options['range']= 'TOP 1 *';
        $options['condition']= ' [account] = \''.$AppPath[1].'\' ';
        $User = LoadObject('Corporate_Users', $options);
        $Publication = $User;
        $ActivePublicationContext['path'] .= 'Users/root.php';
        $ActivePublicationContext['data'] = $User->data;
        $ActivePublicationContext['data']['title'] = 'Control Panel - ' . $User->data['primaryfname'] . '\'s Command Console';

        $Active_Publication = new Publication();
        require_once(__DIR__ . '/'.$ActivePublicationContext['path']);
        $Active_Publication->publish($silent);
        $ActivePublication = $Active_Publication;
        $ActivePublicationContext['self'] = $ActivePublication;
        return $Active_Publication;
    }

    /*   Get Root Category   */

    $options['range']= 'TOP 1 *';
    $options['condition']= '[Name] LIKE \'' . $AppPath[0] . '\' AND Parent = 2';
    $CategorySearch = LoadObject('Categories', $options);

    if(count($CategorySearch) < 1 )
    {
        $options['condition']= '[Name] LIKE "yourmom"  ESCAPE \'\\\' AND Parent = 2 ';
        $CategorySearch = LoadObject('Categories', $options);
        if(count($CategorySearch) > 0 ){            $Category = $CategorySearch;     }
        else{ exit("FAILED TO ROUTE PUBLICATION: PRIMARY CATEGORY SEARCH FAILURE. "); }
    }   else{ $Category = $CategorySearch;    }



    $ActivePublicationContext['id'][$Category->data['Name']] = $Category->data['id'];
    $ActivePublicationContext['path'] .= $Category->data['Name'] . '/';

    $options['condition']= '[alias] LIKE \'' . $AppPath[0] . '\' AND [category] = ' . $Category->data['id'] . ' AND [root] = 1';
    $Publication = LoadObject('Publications', $options);

    /*  Get Root Publication For Next Category  */

  for($i=1, $L=count($AppPath); $i<$L; $i++)
  {
      /*    Match Currrent Category Root Publication    */
      $options['range']= '*';
      $options['condition']= '[Parent] = ' . $Category->data['id'];
      $Subcategories = LoadObjects('Categories', $options);

      $options['condition']= '[alias] LIKE \'' . $AppPath[$i] . '\' AND ( ';
      foreach($Subcategories as $subcat)
      {
          $options['condition'] =$options['condition'] . ' [category] = ' . $subcat->data['id'] .' OR';
      }
      $options['condition'] = substr($options['condition'],0, -3);
      $options['condition'] = $options['condition'] . ') AND [parent] = '.$Publication->data['id'] ;

      $Publication = LoadObject('Publications', $options);

      $ActivePublicationContext['id'][$Category->data['Name']] = $Category->data['id'];
      if($Category->data['Name'] != $DefaultCategory) $ActivePublicationContext['path'] .= $Category->data['Name'] . '/';

        /*    Next Category Search    */
        $options['range']= 'TOP 1 *';
        $options['condition']= '[id] = \'' . $Publication->data['category'] . '\' AND [Parent] = ' . $Category->data['id'];
        $Category = LoadObject('Categories', $options);
  }

  if(count($AppPath) >1) $ActivePublicationContext['path'] .= $Category->data['Name'] . '/';
  $ActivePublicationContext['data'] = $Publication->data;
  $ActivePublicationContext['path'] .= 'root.php';

  $Active_Publication = new Publication();
  require_once(__DIR__ . '/'.$ActivePublicationContext['path']);
  $Active_Publication->publish($silent);

  $ActivePublication = $Active_Publication;
  $ActivePublicationContext['self'] = $ActivePublication;
  return $Active_Publication;

 // $RoutedPublication = new Publication();
}


if(!$ApproachServiceCall){  $ActivePublication = RouteFromURL($_SERVER['REQUEST_URI']);  }

?>