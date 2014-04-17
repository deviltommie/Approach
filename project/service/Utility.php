<?php

require_once(__DIR__.'/../core.php');
require_once($InstallPath.'/core/Service.php');
require_once($RuntimePath .'/service/Registrar.php');

class Utility extends Service
{
    public $options=array();
    public $User;

    public function ProcessJSON($activity)
    {
        global $ApproachRegisteredService;
        $success=false;
        $Component = false;
        $support = array();
        $arguments= array();
        $WorkingSet=array();
        $response = array();
        $response['APPEND']['#Dynamics']='';

//        if(isset($activity['incoming']['command']['ACTION'])) $arguments=$activity['incoming']['command']['ACTION'];
        if(isset($activity['incoming']['support'])) $support = $activity['incoming']['support'];
        
        foreach($activity['incoming']['command'] as $Request => $Instructions)
        {
            $success=false;
            $arguments[$Request]=$Instructions;
            foreach($Instructions as $Domain => $Command)
            {
                switch($Domain)
                {
                    case 'Composition':   $support['context']=$activity['context']; $support['ActiveComponent']=$Component; break;
                    default:              break;
                }
                //{'$Request':[{'$Selector':'$Markup'}]}
                if(!is_array($Command))
                {
                    $WorkingSet[$Domain][$Command]= $ApproachRegisteredService[$Domain][$Command]($arguments, $support);
                    $response[$Request]['#Dynamics'] .= $WorkingSet[$Domain][$Command]['render'];                    
                }
                foreach($Command as $action => $data)
                {
                    $WorkingSet[$Domain][$action]= $ApproachRegisteredService[$Domain][$action]($arguments, $support);
                    $response[$Request]['#Dynamics'] .= $WorkingSet[$Domain][$action]['render'];
                }
                $success = true;
            }
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