<? 
class sets extends Dataset { 

	public $Columns=array (
  'id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'sets',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'pointer' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'sets',
    'COLUMN_NAME' => 'pointer',
    'ORDINAL_POSITION' => '2',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'type' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'sets',
    'COLUMN_NAME' => 'type',
    'ORDINAL_POSITION' => '3',
    'COLUMN_DEFAULT' => '1024',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
);
	public $table='sets';
	public $PrimaryKey='+++PARENT+++';
	public $data;
}
?>