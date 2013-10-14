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

*/


require_once('/../__config_error.php');
require_once('/../__config_database.php');

$tableName="NULL TABLE";
$currentTable;

if(!isset($RuntimePath)) $RuntimePath=$_SERVER['DOCUMENT_ROOT'] .'/';

function fileSave($file, $data)
{
    $handle =fopen($file, 'w+');
    fwrite($handle,$data);
    fclose($handle);
}

function SavePHP($dbo)
{
  global $RuntimePath;
  $theOutput = "<? \nclass " . $dbo->table . " extends Dataset { \n";
  $theOutput .= "\n\tpublic \$Columns=" . var_export($dbo->Columns, true);

  $theOutput .= ";\n\tpublic \$table='$dbo->table';";

  if( isset($dbo->PrimaryKey) ) $theOutput .= "\n\tpublic \$PrimaryKey='$dbo->PrimaryKey';";
  else $theOutput .= "\n\tpublic \$PrimaryKey='+++PARENT+++';";

  $theOutput .= "\n\tpublic \$data;";
  $theOutput .= "\n}\n?>";

//  print_r($theOutput);	$RuntimePath . 'Datasets/' 
  fileSave($RuntimePath . "Datasets/" . $dbo->table . '.php', $theOutput);
}

function ms_escape_string($data) {
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
    while($newRow=$connection->load())
    {
	$Container[] = $newRow;
    }
    return $Container;
}

function UpdateSchema()
{
  $sql='SELECT TABLE_CATALOG, TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE FROM `INFORMATION_SCHEMA.COLUMNS`';

  $spread=array();
  $DataObjects=array();
  $schemainfo=LoadDirect($sql);

  foreach($schemainfo as $SchemaRow)
  {
    $spread[$SchemaRow->data['TABLE_NAME']][$SchemaRow->data['COLUMN_NAME']]=$SchemaRow->data;
  }
  
  foreach($spread as $table => $columns)
  {
    $sql="SELECT * FROM `INFORMATION_SCHEMA.KEY_COLUMN_USAGE` WHERE TABLE_NAME = N'$table';";
    $findKeys=LoadDirect($sql);

    $sql="SELECT * FROM `INFORMATION_SCHEMA.VIEW_COLUMN_USAGE` WHERE VIEW_NAME = N'$table';";
    $keyProperties=LoadDirect($sql);

    $dObj = new stdClass();

    foreach($findKeys as $row)
    {
        $str = explode('_',$row->data['CONSTRAINT_NAME']);
        if($str[0] == "PK")
            $dObj->PrimaryKey = $row->data['CONSTRAINT_NAME'];
        else
            $dObj->ForeignKey[]=$row->data['CONSTRAINT_NAME'];
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

/*
    print_r('<br><br><br><br><br>');
    print_r($dObj->PrimaryKey);
    print_r('<br><br><br><br><br>');
    print_r($dObj);
    print_r('<br><br><br><br><br>');
*/
    SavePHP($dObj);
  }
  
}

UpdateSchema();

class Dataset
{
    public $table, $key, $options, $data;

    function Dataset($t, $options=array())
    {
        global $tableName;
        global $currentTable;
        global $db;
        
        $this->table = get_class($this);

        $queryoverride = 'NULL';

        /* Default to selecting top 10 rows of the database */
        /* To Do: Default to all if !$ApproachDebugMode ? */

        $command='SELECT * ';
        $range='';
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
	
        $buildQuery = $command .' '. $range .' FROM `'. $target .'` '. $method .' '. $condition;
        if($queryoverride != 'NULL') $buildQuery = $queryoverride;
        $options['queryoverride']=$buildQuery;
        if(isset($options['debug'])) print_r('\n\r<br>\n\r'.$buildQuery.'\n\r<br>\n\r');
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
	
        if($this->PrimaryKey == '+++PARENT+++')
        {
            foreach($this->Columns as $tableName => $table)
            {
                require_once($RuntimePath . 'Datasets/' . $tableName . '.php');
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
          $insertCols ='';
          $insertVals ='';

          if(isset($primaryValue)) $this->data[$this->PrimaryKey] = $primaryValue;
          foreach($this->data as $key => $value)
          {
              if($key != $this->PrimaryKey && $value != '' && isset($value) )
              {
                  $valuePairs .= ' '. $key .' = ';
                  $valuePairs .= (is_string($value) ? "'" . ms_escape_string($value) . "', " : $value.', ');

                  $insertCols .= ' '. $key .', ';
                  $insertVals .= (is_string($value) ? "'" . ms_escape_string($value) . "', " : $value.', ');
              }
          }
          $valuePairs=substr($valuePairs, 0, -2);
          $insertCols=substr($insertCols, 0, -2);
          $insertVals=substr($insertVals, 0, -2);

          if( isset($primaryValue) )
          {
            $data = mysql_query(
            'IF( EXISTS( SELECT * FROM '.$this->table.' WHERE ' . $this->PrimaryKey . ' = ' . $this->data[$this->PrimaryKey] .' ) )
                    BEGIN UPDATE '. $this->table . ' SET ' . $valuePairs . ' WHERE ' . $this->PrimaryKey . ' = ' . $this->data[$this->PrimaryKey] .' END
             ELSE   BEGIN INSERT INTO '. $this->table . ' ( ' . $this->PrimaryKey .' '. $insertCols . ') VALUES (' . $primaryValue . ', '. $insertVals . ') END; SELECT SCOPE_IDENTITY()'
            );
          }
          else{ $result = mysql_query('BEGIN INSERT INTO '. $this->table . ' ( ' . $insertCols . ') VALUES ( ' . $insertVals . ') END; SELECT SCOPE_IDENTITY()' ); }
         $data=mysql_fetch_array($result);

        }

        return $data;
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

        fileSave($RuntimePath . 'Datasets/' . $this->table . '.php', $theOutput);
    }

}

$DatasetMissing = new Exception('Dataset Class Definition is Missing',7);
function LoadObjects($table, $options=Array())
{
    global $RuntimePath;
    global $DatasetMissing;
    $Container=Array();
    $currentRow;

    //Look For Generated DataBase Object File, If Not There Try To Make One

    try
    {
        if(!include_once $RuntimePath . 'Datasets/' . $table . '.php') throw $DatasetMissing;
        else $currentRow = new $table($table, $options);
    }
    catch(Exception $e)
    {
        UpdateSchema();
        require_once $RuntimePath . 'Datasets/' . $table . '.php';
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

    //Look For Generated DataBase Object File, If Not There Try To Make One
    try
    {
        if(!include_once $RuntimePath . 'Datasets/' . $table . '.php') throw $DatasetMissing;
        $currentRow = new $table($table, $options);
    }
    catch(Exception $e)
    {
        UpdateSchema();
        require_once $RuntimePath . 'Datasets/' . $table . '.php';
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

//UpdateSchema();

?>