<? 
class nodes extends Dataset { 

	public $Columns=array (
  'id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'owner' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'owner',
    'ORDINAL_POSITION' => '2',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'meta' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'meta',
    'ORDINAL_POSITION' => '3',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'parent' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'parent',
    'ORDINAL_POSITION' => '4',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'scope' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'scope',
    'ORDINAL_POSITION' => '5',
    'COLUMN_DEFAULT' => '1',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'self' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'self',
    'ORDINAL_POSITION' => '6',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'int',
  ),
  'root' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'root',
    'ORDINAL_POSITION' => '7',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'active' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'active',
    'ORDINAL_POSITION' => '8',
    'COLUMN_DEFAULT' => 'b\'1\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'error' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'error',
    'ORDINAL_POSITION' => '9',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'update' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'update',
    'ORDINAL_POSITION' => '10',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'privacy' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'privacy',
    'ORDINAL_POSITION' => '11',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'cache' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'cache',
    'ORDINAL_POSITION' => '12',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'migrate' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'migrate',
    'ORDINAL_POSITION' => '13',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'lock' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'lock',
    'ORDINAL_POSITION' => '14',
    'COLUMN_DEFAULT' => 'b\'0\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'title' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'title',
    'ORDINAL_POSITION' => '15',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'varchar',
  ),
  'tags' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'nodes',
    'COLUMN_NAME' => 'tags',
    'ORDINAL_POSITION' => '16',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'varchar',
  ),
);
	public $table='nodes';
	public $PrimaryKey='+++PARENT+++';
	public $data;
}
?>