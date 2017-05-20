<?php

namespace System\Data\Entity\Mapping\Schema;

class Schema{
    
    protected $db;

    public function __construct(\System\Data\Database $db){
        $this->db = $db;
    }

    public function createEntities(array $config = []){

        $rows = $this->db->fetchRecordSet('SELECT 
            t.TABLE_NAME,
            COLUMN_NAME,
            COLUMN_DEFAULT,
            IS_NULLABLE,
            DATA_TYPE,
            CHARACTER_MAXIMUM_LENGTH,
            COLUMN_KEY
            FROM information_schema.TABLES AS t
            INNER JOIN information_schema.COLUMNS c ON c.TABLE_NAME = t.TABLE_NAME
            WHERE t.TABLE_SCHEMA=:dbname
            AND c.TABLE_SCHEMA=:dbname',['dbname' => $this->db->getConnectionString()->getDbName()]);

        $tables = [];
        foreach($rows as $row){
            $tables[$row->get('TABLE_NAME')][] = $row;
        }
        unset($rows);
                    
        foreach($tables as $name => $fields){
            $entityGenerator = new EntityGenerator(new \System\Core\Arr($config));
            $entityGenerator->generate($name, $fields);
        }
    }
}

