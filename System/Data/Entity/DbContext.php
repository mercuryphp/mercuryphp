<?php

namespace System\Data\Entity;

abstract class DbContext {
    
    protected $db;
    protected $config;
    protected $query;
    protected $dbSets = [];
    protected $entityAttributeData = [];
    
    public function __construct($dsn = '', DbConfiguration $config = null){
        if($dsn){
            $this->db = new \System\Data\Database($dsn);
        }
        if(null == $config){
            $this->config = new DbConfiguration();
        }
        $this->query = new DbQuery($this->db);
    }
    
    public function getConfiguration() : DbConfiguration{
        return $this->config;
    }
    
    public function getDatabase() : \System\Data\Database{
        return $this->db;
    }
    
    public function getQueryBuilder(){
        return new DbQueryBuilder($this);
    }

    public function query(string $sql, array $params = []){
        return $this->query->setQuery($sql, $params);
    }
    
    public function dbSet($name){
        
        if(array_key_exists($name, $this->dbSets)){
            return $this->dbSets[$name];
        }
        
        $this->entityAttributeData[$name] = $this->config->getEntityAttributeDriver()->read($name);
        $this->dbSets[$name] = new DbSet($this);
        
        return $this->dbSets[$name];
    }
    
    public function saveChanges(){
        
        foreach($this->dbSets as $dbSet){
            foreach($dbSet as $entityContext){
                
                switch($entityContext->getState()){
                    case EntityContext::INSERT:
                        $entityAttributeData = $this->entityAttributeData[$entityContext->getName()];
                        $properties = \System\Core\Obj::getProperties($entityContext->getEntity());
                        
                        print_R($entityAttributeData); exit;
                        
                        break;
                }
            }
        }
    }
}
