<?php

namespace System\Data\Entity\Mapping\Schema;

class EntityGenerator{
    
    protected $config;
    
    public function __construct(\System\Core\Arr $config){
        $this->config = $config;
    }
    
    public function generate($tableName, array $fields){
        $sb = new \System\Core\StrBuilder();
        
        $sb->append('<?php')
            ->appendLine(2)
            ->append('namespace {namespace};')
            ->appendLine(2)
            ->append('/**')
            ->appendLine()
            ->append(' * @System.Data.Entity.Mapping.Attributes.Table("{table}")')
            ->appendLine()
            ->append(' * @System.Data.Entity.Mapping.Attributes.Key("{key}")')
            ->appendLine()
            ->append(' */')
            ->appendLine()
            ->append('class {class}{')
            ->appendLine(2);
       
        $strProperties = new \System\Core\StrBuilder();
        $strMethods = new \System\Core\StrBuilder();
        $keyField = '';
        
        foreach($fields as $field){ 
            
            if($field->getString('COLUMN_KEY')->equals('PRI')){
                $keyField = $field->get('COLUMN_NAME');
            }
            
            $strProperties->appendTab()
                ->append('protected $')
                ->append($field->get('COLUMN_NAME'))
                ->append(';')->appendLine(2);
           
            $strMethods->appendTab()
                ->append('public function ')
                ->append($this->getName($field->get('COLUMN_NAME'), 'set'))
                ->append('($')
                ->append(lcfirst($this->getName($field->get('COLUMN_NAME'))))
                ->append('){')->appendLine()
                ->appendTab(2)
                ->append('$this->')
                ->append($field->get('COLUMN_NAME'))
                ->append(' = ')
                ->append('$')
                ->append(lcfirst($this->getName($field->get('COLUMN_NAME'))))
                ->append(';')
                ->appendLine()
                ->appendTab(1)
                ->append('}')
                ->appendLine(2)
                ->appendTab()
                ->append('public function ')
                ->append($this->getName($field->get('COLUMN_NAME'), 'get'))
                ->append('(){')
                ->appendLine()
                ->appendTab(2)
                ->append('return $this->')
                ->append($field->get('COLUMN_NAME'))
                ->append(';')
                ->appendLine()
                ->appendTab(1)
                ->append('}')
                ->appendLine(2);
        }
        
        $sb->append($strProperties)
            ->append($strMethods)
            ->trim()->appendLine()
            ->append('}')
            ->tokens([
                'namespace' => $this->config->get('namespace'),
                'class' => $this->getName($tableName),
                'table' => $tableName,
                'key' => $keyField
            ]);

        $filename = $this->config->get('path') . '/' . $this->getName($tableName) . '.php';
        file_put_contents($filename, $sb);
    }

    protected function getName(string $name, string $type = ''){
        $segments = explode('_', $name);
        return $type . join(array_map('ucfirst', $segments));
    }
}

