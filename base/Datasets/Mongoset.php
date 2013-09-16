<?php

/*
This is a mongo version of Dataset.
When we can get it, MySQL and SQL server all working off similar options we'll: 

- Merge them 
- Have multiple connections to the same data source
- Have multiple connections to multiple data sources
- Provide Data Sources instead of hard coding it into Dataset

*/


require_once('_config_error.php');
require_once('_config_database.php');

$tableName="NULL TABLE";
$currentTable;  //just a global, should call ApproachCurrentTable or start using a namespace.............>>>>>>>>>......yeah...



function fileSave($file, $data)
{
    $handle =fopen($file, 'w+');
    fwrite($handle,$data);
    fclose($handle);
}

function SavePHP($dbo)
{
  $theOutput = "<? \nclass " . $dbo->source . " extends DataObject { \n";
  $theOutput .= "\n\tpublic static \$Columns=" . var_export($dbo->Columns, true);

  $theOutput .= ";\n\tpublic static \$source='$dbo->source';";

  if( isset($dbo->PrimaryKey) ) $theOutput .= "\n\tpublic \$PrimaryKey='$dbo->PrimaryKey';";
  else $theOutput .= "\n\tpublic \$PrimaryKey='+++PARENT+++';";

  $theOutput .= "\n\tpublic \$data;";
  $theOutput .= "\n\tpublic \$instantiator;";
  $theOutput .= "\n}\n?>";

//  print_r($theOutput);
  fileSave($_SERVER['DOCUMENT_ROOT'] . "/Approach/Generator/DataObject/" . $dbo->table . '.php', $theOutput);
}

function ms_escape_string($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                       // 11
            '/\x0c/',                       // 12
            '/[\x0e-\x1f]/'             //14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
            $data = str_replace("'", "''", $data );
        return $data;
    }

function UpdateSchema()
{
    global $db;
    $DatabaseIndices=$db->system->indexes->find();
    $dObj=0;

    var_dump(iterator_to_array($DatabaseIndices));
    print('<br />');




//    SavePHP($dObj);
}

//UpdateSchema();

class DataObject
{
    public $name, $key, $options, $data, $collection;

    function DataObject($options=array())
    {
        global $db;

        if(isset($options['target'])) $this->name = $options['target'];
        if(isset($options['target'])) $t=$options['target'];

        $queryoverride = 'NULL';
        $buildChain='';
        $buildQuery='';

        /* Default to selecting top 10 rows of the database */
        /* To Do: Default to all if !$ApproachDebugMode ? */

        $operation='find';                          //find,findOne,getIndexes,command,...
        $range=false;                               //goes into method[i](range[i])
        $target= isset($options['target'])? $options['target'] : get_class($this);  //Collection
        $method=false;                              //can be count()
        $condition=array('_id'=>'*');              //Object filter & selector

        $condition='';          //filter applied to results
        $method='';             //Means of aggregating results if required

        /*  Override All Data Search Options If Available */

        if(isset($options['operation'])) $operation = $options['operation'];
        if(isset($options['range'])) $range = $options['range'];
        if(isset($options['target'])) $target = $options['target'];
        if(isset($options['method'])) $method = $options['method'];
        if(isset($options['condition'])) $condition = $options['condition'];
        if(isset($options['queryoverride'])) $queryoverride = $options['queryoverride'];

        if($condition !== '' && $method === '') $method = 'WHERE';

        /* Set Options Explicitly To Dynamic Commands For Certain Use Cases If They Weren't There Before' */

        $options['operation']       = $operation ;
        $options['range']           = $range;
        $options['target']          = $target;
        $options['method']          = $method;
        $options['condition']       = $condition;
        $options['queryoverride']   = $queryoverride;

        /* Prepare Query And Ask For Object Data */

        $target=$db->selectCollection($target);
        $buildQuery = $operation;


        if(gettype($method) === gettype(array() ))
        {
            $buildChain = $method[0].'('.$range[0].')';
            for($i=1,$L=count($method); $i<$L; $i++)
            {
                $buildChain.= '->'.$method[$i].'('.$range[$i].')';
            }
        }
        else if($range!==false && method !==false)
        {
            $buildChain = $method .'('. $range.')' ;

        }
        else
        {
            $buildChain=function(){ };
        }

        if($queryoverride != 'NULL') $buildQuery = $queryoverride;
        $options['queryoverride']=$buildQuery;

        if(isset($options['debug'])) print_r('\n\r<br>\n\r'.$buildQuery.'\n\r<br>\n\r')->buildChain();



        $this->collection=call_user_func_array(array($target, $buildQuery), array($condition));

        /* Store Options For Context, To Do: Move all $table, $key and $options into $this->___context again */
        $this->options=$options;
    }

    function load() //Individual DataObject->load() will set that DataObject to last result of current query when $newRow is replaced with $this
    {

        $data = $this->collection->getNext();
        if(!$data) return false;

        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                if(!is_int($key)){ $this->data[$key] = $value; }  //Only get the Associative keys, not the indexed array
            }
            return $this->data;
        } else{    return false;    }

    }

    function save($primaryValue=NULL)  //call this function after using the new update() function. it will save changes on the php object to database.
    {
        foreach($this->Properties as $AbstractedOrigin => $tableName)
            {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/Approach/DataObjects/' . $tableName . '.php');
                $AbstractedOrigin = new $tableName($tableName);
                foreach($table as $Column => $Properties)
                {
                    if(isset($this->data[$Column]))
                    $AbstractedOrigin->data[$Column] = $this->data[$Column];
                }
                $AbstractedOrigin->save($primaryValue);
            }
        return $data;
    }
    function toPHP()
    {
        $theOutput = "<? \nclass " . $this->table . " extends DataObject { ";
        foreach($this->data as $key => $value)
        {
            if($key != 'table' && $key !='key') $theOutput .= "\n\tpublic \$this->data['$key'];";
        }
        $theOutput .= "\n}\n?>";

        fileSave($_SERVER['DOCUMENT_ROOT'] . "/Approach/Generator/DataObject/" . $this->table . '.php', $theOutput);
    }

}

function LoadObjects($table, $options=Array())
{
    $Container=Array();
    $currentRow;

    //Look For Generated DataBase Object File, If Not There Try To Make One
    if(!isset( $options['target']) ) $options['target']=$table;
    if(!isset($options['collection']) ) $options['collection']=$table;
    try
    {
        $currentRow = new DataObject($options);
    }
    catch(Exception $e)
    {
        try
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/Generator/DataObject/' . $table . '.php';
            $option['collection']=$table;
            $currentRow = new $table($options);
        }
        catch(Exception $e_ii)
        {
            UpdateSchema();
            try
            {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/Generator/DataObject/' . $table . '.php';
                $currentRow = new $table($table, $options);
            }
            catch(Exception $e_iii)
            {
                return 'Problem Encountered: This Data Is Missing ';
            }
        }
    }

    //Get That Data !! This Where 3/5 The Magic Happens! =D
    while($currentRow->load())
    {
        $Container[] = $currentRow->data;
    }

    return $Container;
}

function LoadObject($options)
{
    $Container=Array();
    $currentRow;

    if(isset($options['target']) && !isset($options['collection']) ) $options['collection']=$options['target'];
    if(!isset($options['collection']) ) return 'Encountered Problem: No Collection Set, $options: ' . var_export($options,true);

    try
    {
        $currentRow = new DataObject($options);
    }
    catch(Exception $e)
    {
        UpdateSchema();
        try
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/Approach/DataObjects/' . $options['collection'] . '.php';
            $currentRow = new $options['collection']($options['collection'], $options);
        }
        catch(Exception $e_ii)
        {
            return 'Problem Encountered: This Data Is Missing ';
        }
    }

    //Get That Data !! This Where 3/5 The Magic Happens! =D

    $Container = $currentRow->load();

    return $Container;
}

//UpdateSchema();

?>
