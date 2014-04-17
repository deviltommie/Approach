<?

require_once('../Service.php');

class Upload extends Service
{
    public $options=array();
    public $User;

    public function ProcessJSON($activity)
    {
        $success=false;

        $req=$activity['incoming']['request'];
        $WorkingSet=array();
        $response = array();
        $UploadID = 10;

        if( isset($req['FILE']) )             //Servable Action  'SERVE'
        {
          $allowedExts = array('mp4','webm','ogg','jpg', 'mpeg', 'gif', 'png');
          $extension = end(explode('.', $_FILES['file']['name']));
          if(in_array($extension, $allowedExts))
          {
            if ($_FILES['file']['error'] > 0) break;

            echo 'Upload: ' . $_FILES['file']['name'] . '<br />';
            echo 'Type: ' . $_FILES['file']['type'] . '<br />';
            echo 'Size: ' . ($_FILES['file']['size'] / 1024) . ' Kb<br />';
            echo 'Temp file: ' . $_FILES['file']['tmp_name'] . '<br />';

            if (file_exists('/var/www/html/debug/MyDamnChannel/Uploads/'.$activity['authorize']['Username'] . '/uploads/'.date('d-m-Y__D-N-W').'/'. $_FILES['file']['name']))
            {
              echo $_FILES['file']['name'] . ' already exists. ';
              break;
            }
            else
            {
              if(!is_dir('/var/www/html/debug/MyDamnChannel/Uploads/'.$activity['authorize']['Username']))                              mkdir('/var/www/html/debug/MyDamnChannel/Uploads/'.$activity['authorize']['Username'], 0755, true);
              if(!is_dir('/var/www/html/debug/MyDamnChannel/Uploads/'.$activity['authorize']['Username'] . '/'.date('d-m-Y__D-N-W')))   mkdir('/var/www/html/debug/MyDamnChannel/Uploads/'.$activity['authorize']['Username'] . '/'.date('d-m-Y__D-N-W'), 0755, true);
              move_uploaded_file($_FILES['file']['tmp_name'],
              '/var/www/html/debug/MyDamnChannel/Uploads/'. $activity['authorize']['Username'] . '/'.date('d-m-Y__D-N-W').'/'. $_FILES['file']['name']);
              echo 'Stored in: ' . $activity['authorize']['Username'] . '/uploads/'.date('d-m-Y__D-N-W').'/'. $_FILES['file']['name'];
            }
          }
          else{ echo 'Invalid file'; break; }

           $response['status'][$UploadID] = $UploadStatus();
           $success = true;
        }

        $response['success'] = $success;

        $activity['outgoing']=$response;    //hurray, processing!
        return $activity['outgoing'];   //Return value should be a nested array that will be json_encode into a JSON object
    }
}

$s = new Upload();
$s->Receive();
$s->Process();
$s->Respond();


?>