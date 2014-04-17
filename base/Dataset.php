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
1. 	How can we make the search functionality stronger, easier to batch, more intuitive and/or more generic?
 
*/

global $db;
global $RuntimePath;

if(!isset($db))
{
	include_once(__DIR__.'/../__config_error.php');
	include_once(__DIR__.'/../__config_database.php');
	if(!isset($db))
	{
		include_once('__config_error.php');
		include_once('__config_database.php');
		if(!isset($db)) die('No database selected');
	}
}
if(!isset($RuntimePath)) $RuntimePath=$_SERVER['DOCUMENT_ROOT'];	//Included from core.php?

$tableName='NULL TABLE';
$currentTable;


//UpdateSchema();

class Dataset
{
    public $data,$options;

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

			$this->options['debug']=null;
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
	
		//TO DO: Refactor into disassemble() & save() --or-- save() & commit() --or-- something.
        if(isset($this->profile['Accessor']['Reference'])) //PrimaryKey == '<Accessor="Inherited" />')
        {
			$SubsetsSaved=array();
            foreach($this->profile['header'] as $Proprety => &$Aspects)
            {
				$SubsetName =($Aspects['TABLE_SCHEMA']=='information_schema'? 'schema/':'').$Aspects['TABLE_NAME'];
				if(!in_array($SubsetName,$SubsetsSaved))	$SubsetsSaved[$SubsetName][]=$Aspects[''];

				/*
				 *TO DO: Unify key associations to a standard mapping via connectors
				 *Example: require_once('/.../support/datasets/mongodb3_01/geoDB_local/Countries.php');

				$SubsetName = $Aspects['DATABASE_TECH'].'/'.$Aspects['DATABASE_CONTAINER'].'/'. $Aspects['DATASET'];
				if(!in_array($SubsetName,$SubsetsSaved))	$SubsetsSaved[]=$SubsetName;
				 */
			}

			foreach($SubsetsSaved as $SubsetName)
			{
				//Just noticing, $RuntimePath . '/support/datasets/' is used enough it should prolly be an environment variable
				//Should probably collect all environment values into some structure and get some thread-safety and context sharing
				require_once($RuntimePath . '/support/datasets/'. $SubsetName . '.php');
				$SubsetOrigin = new $Aspects['TABLE_NAME']($Aspects['TABLE_NAME']);

				foreach($this->data as $Proprety => &$Value)
					if($this->profile['header'][$Property]['TABLE_NAME'])	$Subset->data[$Property] = $this->data[$Property];
				$SubsetOrigin->save($primaryValue); //Down the pipe you go
            }
        }
        else
        {
          $valuePairs ='';
          $SerializedProperties ='';
          $SerializedValues ='';

		  $primarykey=$this::$profile['Accessor']['Primary'];
          if(isset($primaryValue)) $this->data[$this->profile['Accessor']['Primary']] = $primaryValue;
          foreach($this->data as $key => $value)
          {
              if($key != $this::$profile['Accessor']['Primary'] && $value != '' && isset($value) )
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
		  $query='INSERT INTO '. $this->table . ' ( ' . $SerializedProperties . ') VALUES( ' . $SerializedValues . ') ON DUPLICATE KEY UPDATE '. $valuePairs. ';' ;
          $result = mysqli_query($db,$query);
		  if($this->options['debug']) print_r($query);
		  if($result) $this->data[$this::$profile['Accessor']['Primary']]=mysqli_insert_id($db);
		}
        return $this->data;
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
        if(!include_once $RuntimePath . '/support/datasets/' . $table . '.php') throw new ErrorException("Data missing");
        else $currentRow = new $table($table, $options);
    }
    catch(ErrorException $e)
    {
	try
	{
	    UpdateSchema();
	    if(!include_once $RuntimePath . '/support/datasets/' . $table . '.php') throw new ErrorException("Data missing");
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
        if(!include_once $RuntimePath . '/support/datasets/' . $table . '.php') throw $DatasetMissing;
        $currentRow = new $table($table, $options);
    }
    catch(ErrorException $e)
    {
		exit("SCHEMA FAIL");
        //UpdateSchema();
        //require_once $RuntimePath . '/support/datasets/' . $table . '.php';
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










function fileSave($file, $data)
{
    $handle =fopen($file, 'w+');
    fwrite($handle,$data);
    fclose($handle);
}

function SavePHP($dbo,$classpath='')
{
  global $RuntimePath;
    /*
     *	To Do: Move Variables into a public static Dataset::profile map
     */
  $RefersExist=isset($dbo->ForeignKey);

  $LinePrefix="\n\t";
  $theOutput = '<?php '.PHP_EOL.'require_once(__DIR__.\'/../../core.php\');'.PHP_EOL.'class '.$dbo->table . ' extends Dataset'.PHP_EOL.'{';

  //TO DO: In C++ this would be public static const, but in PHP we will need to make it protected
  //First will need to make read-only accessor/get function in Dataset and ensure other classes are using it
  
  $theOutput .= $LinePrefix.'public static $profile= array(' ;
  $theOutput .= $LinePrefix."\t'target' =>'".$dbo->table.'\',';
  if( isset($dbo->PrimaryKey))
  {
	$theOutput .= $LinePrefix."\t'Accessor'=>array( ".($RefersExist?$LinePrefix."\t\t":'').'\'Primary\' => \''.$dbo->PrimaryKey.'\'';
	if($RefersExist)
	{
		$theOutput .= ','.$LinePrefix."\t\t'Reference'=>array( ";//implode('\', \'',$dbo->ForeignKey).'\')';
		foreach($dbo->ForeignKey as $k => $v)
		{
			$theOutput .=' array(\'';
			foreach($v as $v2)
			{
				$theOutput .=implode('\',\'',$v2);
			}
			$theOutput .='\'),';//\''.$k.'\' => array(\''.$a.'\')';
		}
		rtrim($theOutput,',');
		$theOutput .= ')';
	}
	$theOutput .= '),';
  }
  elseif($RefersExist)
	$theOutput .= $LinePrefix."\t'Accessor'=>array( 'Reference'=>array( '".implode(', ',$dbo->ForeignKey).'\'),';

  $theOutput .= $LinePrefix."\t'header'=>array( ";
  foreach($dbo->Columns as $col => $aspect)
  {
	$theOutput.=$LinePrefix."\t\t'".$col.'\' => array( ';
	foreach($aspect as $k => $v)
	{
		$theOutput.=' \''.$k.'\' => \''.str_replace('\'','\\\'',$v).'\',';
	}
	rtrim($theOutput,',');
	$theOutput.='),';
  }
  rtrim($theOutput,',');
  $theOutput.=$LinePrefix."\t".')'.$LinePrefix.');';

  $theOutput .= $LinePrefix.'public $data;';
  $theOutput .= PHP_EOL.'}'.PHP_EOL.'?>';

//  print_r($theOutput);	$RuntimePath . '/support/datasets/' 
  fileSave($RuntimePath . 'support/datasets/' .$classpath.'/'. $dbo->table . '.php', $theOutput);
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


function LoadDirect($query,$t='information_schema')
{
    $connection=new Dataset($t,array('queryoverride'=>$query));
    $newRow; $Container=array();

    while($newRow=$connection->load()){	$Container[] = $newRow;    }

    return $Container;
}

function UpdateSchema()
{
  //need switch() case: for database type [MySQL, MSSQL, Mongo, Redis, Parsyph, Hadoop, Cassandra]
  $InfoSchemaDatabaseColumn='TABLE_SCHEMA';
	
  $sql='SELECT TABLE_CATALOG, TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS';

  $spread=array();
  $DataObjects=array();
  $schemainfo=LoadDirect($sql,'INFORMATION_SCHEMA.COLUMNS');

  foreach($schemainfo as $SchemaRow)
  {
    $spread[$SchemaRow->data['TABLE_NAME']][$SchemaRow->data['COLUMN_NAME']]=$SchemaRow->data;  
  }  
  
  foreach($spread as $table => $columns)
  {
	//Cross-Database Discrepency : MySQL uses quotes, MSSQL uses N
    $sql='SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE `TABLE_NAME` = "'.$table.'";';
    $findKeys=LoadDirect($sql,'INFORMATION_SCHEMA.KEY_COLUMN_USAGE');

    $sql='SELECT * FROM INFORMATION_SCHEMA.VIEW_COLUMN_USAGE WHERE `VIEW_NAME` = "'.$table.'";';
    $keyProperties=LoadDirect($sql,'INFORMATION_SCHEMA.VIEW_COLUMN_USAGE');

    $dObj = new stdClass();

//	var_dump($keyProperties);
    foreach($findKeys as $row)
    {
        $str = explode('_',$row->data['CONSTRAINT_NAME']);
//		if($table == 'compositions'){ var_export($row); }
        if($str[0] == 'PRIMARY')
            $dObj->PrimaryKey = $row->data['COLUMN_NAME'];
        else
            $dObj->ForeignKey[]=array($row->data['COLUMN_NAME']=>array($row->data['REFERENCED_TABLE_SCHEMA'],$row->data['REFERENCED_TABLE_NAME'],$row->data['REFERENCED_COLUMN_NAME']));
    }

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

	$classpath='';
	foreach($spread[$table] as $column )
	{
		$classpath=(strtolower($column[$InfoSchemaDatabaseColumn])=='information_schema') ? 'schema':'';
		break;
	}

    SavePHP($dObj,  $classpath);
  }
}


?>