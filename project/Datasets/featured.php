<? 
class featured extends Dataset { 

	public $Columns=array (
  'id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bigint',
  ),
  'img' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'img',
    'ORDINAL_POSITION' => '2',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'varchar',
  ),
  'headline' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'headline',
    'ORDINAL_POSITION' => '3',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'varchar',
  ),
  'content' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'content',
    'ORDINAL_POSITION' => '4',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'text',
  ),
  'active' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'active',
    'ORDINAL_POSITION' => '5',
    'COLUMN_DEFAULT' => 'b\'1\'',
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'bit',
  ),
  'node' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'node',
    'ORDINAL_POSITION' => '6',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
  'self_id' => 
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'approach',
    'TABLE_NAME' => 'featured',
    'COLUMN_NAME' => 'self_id',
    'ORDINAL_POSITION' => '7',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'YES',
    'DATA_TYPE' => 'bigint',
  ),
);
	public $table='featured';
	public $PrimaryKey='+++PARENT+++';
	public $data;
}
?>