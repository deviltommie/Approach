<? 
class components extends Dataset { 

	public $Columns=array (
  'id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'composition' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'composition',
    'ORDINAL_POSITION' => '2',
    'COLUMN_DEFAULT' => '1',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'type' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'type',
    'ORDINAL_POSITION' => '3',
    'COLUMN_DEFAULT' => '2',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'instance' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'instance',
    'ORDINAL_POSITION' => '4',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'content' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'content',
    'ORDINAL_POSITION' => '5',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'meta' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'components',
    'COLUMN_NAME' => 'meta',
    'ORDINAL_POSITION' => '6',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
);
	public $table='components';
	public $PrimaryKey='+++PARENT+++';
	public $data;
}
?>