<? 
class scopes extends Dataset { 

	public $Columns=array (
  'id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'scopes',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'Parent' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'scopes',
    'COLUMN_NAME' => 'Parent',
    'ORDINAL_POSITION' => '2',
    'COLUMN_DEFAULT' => '0',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'Name' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'scopes',
    'COLUMN_NAME' => 'Name',
    'ORDINAL_POSITION' => '3',
    'COLUMN_DEFAULT' => 'undefined root',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'varchar',
  ),
);
	public $table='scopes';
	public $PrimaryKey='+++PARENT+++';
	public $data;
}
?>