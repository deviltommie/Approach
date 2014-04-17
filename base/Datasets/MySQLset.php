<?php

/*
	Title: Dataset Class for Approach


	Copyright 2002-2014 Garet Claborn

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.

*/

/*

NOTICE THIS IS THE MYSQLI RELEASE CANDIDATE OF DATASET

IF YOU NEED PRODUCTION USAGE, USE MSSQL OR THOUROUGHLY TEST MYSQLI VERSION FOR NOW
MONGODB, REDIS and XML FILE CONNECTORS ON THE WAY - DESIGNING CONNECTOR ARCHITECTURE CURRENTLY

Request For Comments:
1. 	The Generator needs to understand connectors somehow, very cleanly.
2. 	Before or after moving data into the profile property, the concept of PrimaryKey/Foreign Keys
	needs to be made generic
3. 	How can we make the search functionality stronger, easier to batch, more intuitive and/or more generic?
 
*/

//global $RuntimePath;
global $db;

//require_once('/../__config_error.php');
//require_once('/../__config_database.php');

if(!isset($db)) die('No database selected');
if(!isset($RuntimePath)) $RuntimePath=$_SERVER['DOCUMENT_ROOT'] .'/';	//Included from core.php?

$tableName='NULL TABLE';
$currentTable;

function fileSave($file, $data)
{
    $handle =fopen($file, 'w+');
    fwrite($handle,$data);
    fclose($handle);
}

function SavePHP($dbo)
{
    global $RuntimePath;
      /*
       *	To Do: Move Variables into a public static Dataset::profile map
       */
    $theOutput = "<?php \nclass " . $dbo->table . " extends Dataset { \n";
    $theOutput .= "\n\tpublic \$p=" . var_export($dbo->Columns, true);
    
    $theOutput .= ";\n\tpublic \$table='$dbo->table';";
    
    if( isset($dbo->PrimaryKey) ) $theOutput .= "\n\tpublic \$PrimaryKey='$dbo->PrimaryKey';";
    else $theOutput .= "\n\tpublic \$PrimaryKey='+++PARENT+++';";
    
    if( isset($dbo->ForeignKey) ) $theOutput .= "\n\tpublic \$ForeignKey='".var_export($dbo->ForeignKey,true).'\';';
    
    $theOutput .= "\n\tpublic \$data;";
    $theOutput .= "\n}\n?>";
    
    fileSave($RuntimePath . 'support/datasets/tmp_' . $dbo->table . '.php', $theOutput);
}

function RevisingSavePHP($dbo)
{
  global $RuntimePath;
    /*
     *	To Do: Move Variables into a public static Dataset::profile map
     */
    
  $LinePrefix="\n\t";
  $theOutput = '<?php '.PHP_EOL.'require_once(__DIR__.\'/../../core.php\');'.PHP_EOL.'class '.$dbo->table . ' extends Dataset { '.PHP_EOL;
  $theOutput .= $LinePrefix.'public static $profile[\'header\']=' . var_export($dbo->Columns, true).';';

  $theOutput .= $LinePrefix.'public static $profile[\'target\']=\''.$dbo->table.'\';';

  if( count($dbo->ForeignKey) > 0 ) $theOutput .= $LinePrefix.'public static $profile[\'Accessor\'][\'ForeignKey\']='.var_export($dbo->ForeignKey,true).';';
  if( isset($dbo->PrimaryKey)) $theOutput .= $LinePrefix.'public static $profile[\'Accessor\'][\'Primary\']=\'<Accessor="Inherited" />\';';

  $theOutput .= $LinePrefix.'public $data;';
  $theOutput .= PHP_EOL.'}'.PHP_EOL.'?>';

//  print_r($theOutput);	$RuntimePath . 'support/datasets/' 
  fileSave($RuntimePath . 'support/datasets/' . $dbo->table . '.php', $theOutput);
}


function ms_escape_string($data)
{
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;
    
    $non_displayables = array(
	'/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
	'/%1[0-9a-f]/',             // url encoded 16-31
	'/[\x00-\x08]/',            // 00-08
	'/\x0b/',                   // 11
	'/\x0c/',                   // 12
	'/[\x0e-\x1f]/'             // 14-31
    );
    foreach ( $non_displayables as $regex )
	$data = preg_replace( $regex, '', $data );
    $data = str_replace("'", "''", $data );
    return $data;
}


function LoadDirect($query)
{
    $connection=new Dataset('information_schema',array('target'=>'information_schema','queryoverride'=>$query));
    $newRow; $Container=array();

    while($newRow=$connection->load()){	$Container[] = $newRow;    }
    return $Container;
}

function UpdateSchema()
{
  $sql='SELECT TABLE_CATALOG, TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS';

  $spread=array();
  $DataObjects=array();
  $schemainfo=LoadDirect($sql);

  //Sort 'spread' into spread[Table][Column] , for nested try do spread[Collection][property.subdocument.subproperty]
  foreach($schemainfo as $SchemaRow)
  {
    $spread[$SchemaRow->data['TABLE_NAME']][$SchemaRow->data['COLUMN_NAME']]=$SchemaRow->data;
  }  
  
  foreach($spread as $table => $columns)
  {
    //Get all primary and foreign key usage for all tables
    $sql="SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = N'$table';";
    $findKeys=LoadDirect($sql);
    
    //Get all view usage for all tables
    $sql="SELECT * FROM INFORMATION_SCHEMA.VIEW_COLUMN_USAGE WHERE VIEW_NAME = N'$table';";
    $keyProperties=LoadDirect($sql);

    $dObj = new stdClass();

    foreach($findKeys as $row)
    {
        //does this work for MySQL, MSSQL and PostgreSQL? what do we need to change
        //What should we do for mongo?
        $str = explode('_',$row->data['CONSTRAINT_NAME']);
        if($str[0] == 'PK')
            $dObj->PrimaryKey = $row->data['CONSTRAINT_NAME'];
        else
            $dObj->ForeignKey[]=$row->data['CONSTRAINT_NAME'];
    }
    if(!isset($dObj->PrimaryKey)) $dObj->PrimaryKey='id';

    $t=array();
    foreach($keyProperties as $View)
    {
      if($View === reset($keyProperties) )
      {
        $t = $spread[$table];
        $spread[$table]=array();
      }
      $spread[$table][$View->data['TABLE_NAME']][$View->data['COLUMN_NAME']] = array_merge($spread[$View->data['TABLE_NAME']][$View->data['COLUMN_NAME']], $View->data);
    }

    $dObj->Columns = $spread[$table];
    $dObj->table = $table;

    SavePHP($dObj);
  }
}

//UpdateSchema();

class Dataset
{
    public $table, $key, $options, $data,$PrimaryKey;

    function Dataset($t, $options=array())
    {
        global $tableName;
        global $currentTable;
        global $db;
	
        
        $this->table = get_class($this);

        $queryoverride = 'NULL';

        /* Default to selecting top 10 rows of the database */
        /* To Do: Default to all if !$ApproachDebugMode ? */

        $command='SELECT ';
        $range='* ';
        $target= isset($t)? $t : get_class($this);
        $method='';
        $condition='';

        /*  Override All Data Search Options If Available */

        if(isset($options['command'])) $command = $options['command'];
        if(isset($options['range'])) $range = $options['range'];
        if(isset($options['target'])) $target = $options['target'];
        if(isset($options['method'])) $method = $options['method'];
        if(isset($options['condition'])) $condition = $options['condition'];
        if(isset($options['queryoverride'])) $queryoverride = $options['queryoverride'];

        if($condition !== '' && $method === '') $method = 'WHERE';

        /* Set Options Explicitly To Dynamic Commands For Certain Use Cases If They Weren't There Before' */

        $options['command']         = $command ;
        $options['range']           = $range;
        $options['target']          = $target;
        $options['method']          = $method;
        $options['condition']       = $condition;
        $options['queryoverride']   = $queryoverride;

        /* Prepare  SQL Query And Ask The Database */
	
	//operator + properties FROM target + method + condition
	
        $buildQuery = $command .' '. $range .' FROM '. $target .' '. $method .' '. $condition;
        if($queryoverride != 'NULL') $buildQuery = $queryoverride;
        $options['queryoverride']=$buildQuery;
        if(isset($options['debug'])) print_r('<br>'.PHP_EOL.$buildQuery.PHP_EOL.'<br>');
        if($tableName!=$t) //Already on the right table? Don't restart the query! D:
        {
            $currentTable=$db->query($buildQuery);
            $tableName=$t;
        }
        
        $this->table = $t;

        /* Store Options For Context, To Do: Move all $table, $key and $options into $this->___context again */
        $this->options=$options;
    }

    function load() //Individual MySQLset->load() will set that MySQLset to last result of current query when $newRow is replaced with $this
    {
        global $currentTable;
        global $tableName;

        if($currentTable)
        {
            $data = mysqli_fetch_assoc($currentTable);

            $newRow = new Dataset($tableName, $this->options);   //To Do: Move to Load Objects
            if(is_array($data))
            {
                $newRow->data = $data; 
                return $newRow;
            } else{    return false;    }
        }else{ return false; }
    }
    function save($primaryValue=NULL)  //call this function after using the new update() function. it will save changes on the php object to database.
    {
        global $RuntimePath;
	global $db;
	
        if($this->PrimaryKey == '<Accessor="Inherited" />')
        {
            foreach($this->Columns as $tableName => $table)
            {
                require_once($RuntimePath . 'support/datasets/' . $tableName . '.php');
                $AbstractedOrigin = new $tableName($tableName);
                foreach($table as $Column => $Properties)
                {
                    if(isset($this->data[$Column]))
                    $AbstractedOrigin->data[$Column] = $this->data[$Column];
                }
                $AbstractedOrigin->save($primaryValue);
            }
        }
        else
        {
          $valuePairs ='';
          $SerializedProperties ='';
          $SerializedValues ='';

          if(isset($primaryValue)) $this->data[$this->PrimaryKey] = $primaryValue;
          foreach($this->data as $key => $value)
          {
              if($key != $this->PrimaryKey && $value != '' && isset($value) )
              {
		  $val=(is_string($value) ? '\'' . ms_escape_string($value) . '\', ' : $value.', ');
    
		  $valuePairs .= ' '. $key .' = '.$val;
                  $SerializedProperties .= $key .', ';
                  $SerializedValues .= $val;
              }
          }
          $valuePairs=substr($valuePairs, 0, -2);
          $SerializedProperties=substr($SerializedProperties, 0, -2);
          $SerializedValues=substr($SerializedValues, 0, -2);

          $result = mysqli_query($db,'INSERT INTO '. $this->table . ' ( ' . $SerializedProperties . ') VALUES( ' . $SerializedValues . ') ON DUPLICATE KEY UPDATE '. $valuePairs. ';' );
	  if($result) $this->data[$this->PrimaryKey]=mysqli_insert_id($db);
	}
/*	print_r('<pre>'.
		'INSERT INTO '. $this->table . ' ( ' . $SerializedProperties . ') VALUES( ' . $SerializedValues . ') ON DUPLICATE KEY UPDATE '. $valuePairs. ';' 
		.'</pre><hr>'.mysqli_error($db).'<hr>');
        */
	
        return $this->data;
    }
    function toPHP()
    {
	global $RuntimePath;
        $theOutput = "<? \nclass " . $this->table . " extends MySQLset { ";
        foreach($this->data as $key => $value)
        {
            if($key != 'table' && $key !='key') $theOutput .= "\n\tpublic \$this->data['$key'];";
        }
        $theOutput .= "\n}\n?>";

        fileSave($RuntimePath . 'support/datasets/' . $this->table . '.php', $theOutput);
    }

}

function DataclassError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    if (0 === error_reporting()) {        return false;    }
    else throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

function LoadObjects($table, $options=Array())
{
    global $RuntimePath;
    global $DatasetMissing;
    $Container=Array();
    $currentRow;

    //Look For Generated DataBase Object File, If Not There Try To Make One

    try
    {
        if(!include_once $RuntimePath . 'support/datasets/' . $table . '.php') throw new ErrorException("Data missing");
        else $currentRow = new $table($table, $options);
    }
    catch(ErrorException $e)
    {
	try
	{
	    UpdateSchema();
	    if(!include_once $RuntimePath . 'support/datasets/' . $table . '.php') throw new ErrorException("Data missing");
	    else $currentRow = new $table($table, $options);
	}
	catch(ErrorException $e){ exit("SCHEMA FAIL");	}
    }

    //Get That Data !! This Where 3/5 The Magic Happens! =D
    $newRow;
    
    while($newRow=$currentRow->load())
    {
        $Container[] = $newRow;
    }

    global $tableName;
    $tableName = 'NULL TABLE';
    return $Container;
}

function LoadObject($table, $options=Array())
{
    global $RuntimePath;
    global $DatasetMissing;
    $Container=Array();
    $currentRow;

    $originalHandler=set_error_handler('DataclassError');
        
    //Look For Generated DataBase Object File, If Not There Try To Make One
    try
    {
        if(!include_once $RuntimePath . 'support/datasets/' . $table . '.php') throw $DatasetMissing;
        $currentRow = new $table($table, $options);
    }
    catch(ErrorException $e)
    {
	exit("SCHEMA FAIL");
        UpdateSchema();
        require_once $RuntimePath . 'support/datasets/' . $table . '.php';
    }

    //Get That Data !! This Where 3/5 The Magic Happens! =D
    $newRow;
    
    if($newRow=$currentRow->load())
    {
        $Container = $newRow;
    }

    global $tableName;
    $tableName = 'NULL TABLE';
    return $Container;
}



?>