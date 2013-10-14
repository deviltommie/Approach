<?

require_once('../Service.php');
require_once('../Services/Registrar.php');

global $ApproachRegisteredService;

class Utility extends Service
{
    public $options=array();
    public $User;

    public function ProcessJSON($activity)
    {
        global $ApproachRegisteredService;
        $success=false;
        $Component = 0;
        $req=$activity['incoming']['support'];
        $support = array();
        $arguments= array();
        $WorkingSet=array();
        $response = array();
        $response['APPEND']['#Dynamics']='';

        if(isset($activity['incoming']['command']['ACTION'])) $arguments=$activity['incoming']['command']['ACTION'];
        if(isset($activity['incoming']['support'])) $support = $activity['incoming']['support'];

          foreach($arguments as $domain => $actions)
          {
              switch($domain)
              {
                  case 'Component': break;
                  case 'Publication': $support['context']=$activity['context']; $support['ActiveComponent']=$Component;
                  break;
                  case 'Media':
                  break;
                  default: break;
              }
              if(is_array($actions))
              {
                foreach($actions as $action => $data)
                {
                    $WorkingSet[$domain][$action]= $ApproachRegisteredService[$domain][$action]($arguments, $support);
                    $response['APPEND']['#Dynamics'] .= $WorkingSet[$domain][$action]['render'];
                }
              }
              else
              {
                $WorkingSet[$domain][$actions]= $ApproachRegisteredService[$domain][$actions]($arguments, $support);
                $response['APPEND']['#Dynamics'] .= $WorkingSet[$domain][$actions]['render'];
              }
              $success = true;
          }
        $response['success'] = $success;

        $activity['outgoing']=$response;    //hurray, processing!
        return $activity['outgoing'];   //Return value should be a nested array that will be json_encode into a JSON object
    }
}

$s = new Utility();
$s->Receive();
$s->Process();
$s->Respond();

?>