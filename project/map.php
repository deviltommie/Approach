<?


require_once( '../../approach/core.php');

$DefaultRoot = 9;
global $ActiveComposition;
global $ActiveCompositionContext;
global $ApproachServiceCall;

function RouteFromURL($url, $silent=false)
{
    global $SiteRoot;
    global $DefaultRoot;
    global $ActiveComposition;
    global $ActiveCompositionContext;

    /* Set up environment */

    $ActiveComposition = array();
    $ActiveCompositionContext['path'] = '';
    $ActiveCompositionContext['id']= array();
    $ActiveCompositionContext['type']= array();
    $ActiveCompositionContext['typeid']= array();
    
    $options = array();

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

    /* URI has been transformed to an array structure */
    /*  Root Level Detection  */
    if($url == '/' ||$url == '/home' || $url == '/index' || $url == '/default' )
    {
        $options=array();
        //$options['condition']= '[id] = 1 AND [root] = 1';
        $options['command']= 'SELECT * ';
        
        $Composition = LoadObject('compositions', $options);

        $ActiveCompositionContext['path'] .= 'compose.php';        
        //$ActiveCompositionContext['data'] = $Composition->data;

        $Composing = new Composition();
        
        require_once(__DIR__ . '/'.$ActiveCompositionContext['path']);
        $Composing->publish($silent);
        
        $ActiveComposition = $Composing;
        $ActiveCompositionContext['self'] = $ActiveComposition;
        return $Composing;  //Done
    }

    /*  Dynamic Nested Compositions */
    
    
    /*   Get Root Type   */

    $options['method']= 'WHERE alias LIKE \'' . $AppPath[0] . '\' AND (parent = 1 OR parent = '.$DefaultRoot.')';
    $options['condition']= 'ORDER BY self LIMIT 1';
    
    $RootSearch = LoadObject('compositions', $options);

    //print_r($RootSearch);
    if(count($RootSearch) < 1 )
    {
        exit("FAILED TO ROUTE Composition: PRIMARY TYPE SEARCH FAILURE. ");
    }
    else
    {
        $options['method']= 'WHERE id='.$RootSearch->data['scope'];
        $options['condition']= 'ORDER BY id LIMIT 1';
        $Type = LoadObject('types', $options);
    }

    $ActiveCompositionContext['id'][] = $RootSearch->data['id'];
    $ActiveCompositionContext['type'][]=$Type->data['Name'];
    $ActiveCompositionContext['typeid'][]= $Type->data['id'];
    $ActiveCompositionContext['path'] .=  $Type->data['Name'] . '/';
    
    
    /*  Get Root Composition For Next Type  */
  for($i=1, $L=count($AppPath); $i<$L; $i++)
  {
      /*    Match Currrent Type Root Composition    */
        $options['method']= 'WHERE alias LIKE \'' . $AppPath[$i] . '\' AND parent = '.$RootSearch->data['id'];
        $options['condition']= 'ORDER BY self LIMIT 1';
        
        $RootSearch = LoadObject('compositions', $options);

        if(count($RootSearch) < 1 )
        {
            exit("FAILED TO ROUTE Composition: PRIMARY TYPE SEARCH FAILURE. ");
        }
        else
        {
            $options['method']= 'WHERE id='.$RootSearch->data['scope'];
            $options['condition']= 'ORDER BY id LIMIT 1';
            $Type = LoadObject('types', $options);
        }
        
        $ActiveCompositionContext['id'][] = $RootSearch->data['id'];
        $ActiveCompositionContext['type'][]=$Type->data['Name'];
        $ActiveCompositionContext['typeid'][]= $Type->data['id'];
        $ActiveCompositionContext['traversed'][]=$RootSearch->data;
        
        if($RootSearch->data['root']==1) $ActiveCompositionContext['path'] .= $Type->data['Name'] .'/';
        else $ActiveCompositionContext['path'] .= 'compose.php';
  }

  $ActiveCompositionContext['apppath'] = $AppPath;
  $ActiveCompositionContext['data'] = $RootSearch->data;
  if($RootSearch->data['root']==1 || count($AppPath) ==1) $ActiveCompositionContext['path'] .= 'compose.php';

  $Composing = new Composition();
  $ActiveCompositionContext['self'] = $RootSearch;
  require_once(__DIR__ . '/'.$ActiveCompositionContext['path']);
  $Composing->publish($silent);

  $ActiveComposition = $Composing;
  $ActiveCompositionContext['self'] = $ActiveComposition;
  return $Composing;

 // $RoutedComposition = new Composition();
}


if(!$ApproachServiceCall){  $ActiveComposition = RouteFromURL($_SERVER['REQUEST_URI']);  }

?>