<?php

namespace System\Data\Entity;

use System\Core\Str;

class Schema {
    
    protected $db;
    protected $path;
    
    public function __construct($db, $path){
        $this->db = $db;
        $this->path = $path;
    }
    
    public function export($modelName, $tableName){

        $columns = $this->db->fetchAll("SELECT c.COLUMN_NAME, c.COLUMN_DEFAULT, c.IS_NULLABLE, c.DATA_TYPE, c.CHARACTER_MAXIMUM_LENGTH, c.COLUMN_KEY FROM information_schema.TABLES t
            INNER JOIN information_schema.COLUMNS c ON c.TABLE_NAME = t.TABLE_NAME
            WHERE t.TABLE_NAME = :table_name AND t.TABLE_SCHEMA = :db_name AND c.TABLE_SCHEMA = :db_name", ['table_name' => $tableName, 'db_name' => 'plex']);
        
        $strProperties = new Str();
        $strMethods = Str::set('');
        $primaryKey = '';
        
        foreach($columns as $column){
        
            if($column->COLUMN_KEY == 'PRI'){
                $primaryKey = $column->COLUMN_NAME;
            }
            
            $strProperties = $strProperties->append('    //@System.Data.Entity.DataType("' . $column->DATA_TYPE . '")')->appendLine();
            
            if($column->CHARACTER_MAXIMUM_LENGTH){
                $strProperties = $strProperties->append('    //@System.Data.Entity.StringLength("' . $column->CHARACTER_MAXIMUM_LENGTH . '")')->appendLine();
            }
            $strProperties = $strProperties->append('    protected $' . $column->COLUMN_NAME.';')->appendLine(2);
            
            $strMethods = $strMethods->append('    public function set'.$this->formatFunctionName($column->COLUMN_NAME))->append('('.$this->formatArgName($column->COLUMN_NAME).'){')->appendLine();
            $strMethods = $strMethods->append('        $this->'.$column->COLUMN_NAME)->append(' = ')->append($this->formatArgName($column->COLUMN_NAME).';')->appendLine();
            $strMethods = $strMethods->append('    }')->appendLine(2);
            $strMethods = $strMethods->append('    public function get'.$this->formatFunctionName($column->COLUMN_NAME))->append('(){')->appendLine();
            $strMethods = $strMethods->append('        return $this->'.$column->COLUMN_NAME.';')->appendLine();
            $strMethods = $strMethods->append('    }')->appendLine(2);
        }
        
        $className = Str::set($modelName)->split('\.')->last();
        $file = \System\Core\Str::set('<?php');
        $file = $file->appendLine(2)->append('namespace ' . Str::set($modelName)->getLastIndexOf('.') . ';')->appendLine(2)
            ->append('//@System.Data.Entity.TableName("' . $tableName . '")')->appendLine() 
            ->append('//@System.Data.Entity.Key("' . $primaryKey . '")')->appendLine() 
            ->append('class ' . $className . ' {')->appendLine(2);
        
        $file = $file->append($strProperties)->append($strMethods)->append('}');

        try{
            $modelPath = rtrim($this->path, '/').'/'.$className.'.php';
            file_put_contents($modelPath, $file);
        }catch(\Exception $ioe){
            throw new SchemaExportException($ioe->getMessage());
        }
    }
    
    protected function formatFunctionName($columnName){
        $segments = explode('_', $columnName);
        $segments = array_map('ucfirst', $segments);
        return join('',$segments);
    }
    
    protected function formatArgName($columnName){
        $segments = explode('_', $columnName);
        $segments = array_map('ucfirst', $segments);
        return '$'.lcfirst(join('',$segments));
    }
}