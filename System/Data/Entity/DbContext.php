<?php

namespace System\Data\Entity;

use System\Core\Obj;
use System\Core\Arr;

abstract class DbContext {
    
    private $db;
    private $config;
    private $query;
    private $schema;
    private $dbSets = [];
    private $entityAttributeData;
    private $entities;
    
    public function __construct(string $dsn = ''){
        if($dsn){
            $this->db = new \System\Data\Database($dsn);
        }
        
        $this->config = new DbConfiguration();
        $this->query = new DbQuery($this->db);
        $this->schema = new Mapping\Schema\Schema($this->db);
        $this->entityAttributeData = new Arr();
        $this->entities = new Arr();
        
        $this->config->setEntityAttributeDriver(new Mapping\Driver\AnnotationDriver());
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
    
    public function getSchema(){
        return $this->schema;
    }
    
    public function getRepository(string $name){
        return Obj::getInstance($name, [$this]);
    }

    public function getEntityAttributeData() : Arr{
        return $this->entityAttributeData;
    }
    
    public function getEntities(){
        return $this->entities;
    }

    public function query(string $sql, array $params = []){
        return $this->query->setQuery($sql, $params);
    }
    
    public function dbSet($name){
        
        if(array_key_exists($name, $this->dbSets)){
            return $this->dbSets[$name];
        }

        $this->entityAttributeData->set($name, $this->config->getEntityAttributeDriver()->read($name));
        $this->dbSets[$name] = new DbSet($this, $name);
        
        return $this->dbSets[$name];
    }
    
    public function saveChanges(){
        
        foreach($this->dbSets as $dbSet){
            foreach($dbSet as $entityContext){
                
                $entityAttributeData = $this->entityAttributeData[$entityContext->getName()];
                $entity = $entityContext->getEntity(); 
                $properties = Obj::getProperties($entity);

                foreach($properties as $property => $value){
                    if(is_object($value)){
                        $objHash = spl_object_hash($value);

                        if($this->entities->hasKey($objHash)){
                            $refObjContext = $this->entities->get($objHash);
                            $refObjAttrData = $this->entityAttributeData->get($refObjContext->getName());
                            $refObjProperties = Obj::getProperties($refObjContext->getEntity());
                            $properties[$property] = $refObjProperties[$refObjAttrData->getKey()];
                        }
                    }
                }
                
                Obj::setProperties($entity, $properties);
                
                switch($entityContext->getState()){
                    case EntityContext::INSERT:

                        $this->db->insert($entityAttributeData->getTableName(), $properties);
                        
                        $insertId = $this->db->getInsertId();
                        
                        if($insertId){
                            Obj::setProperties($entity, [$entityAttributeData->getKey() => $insertId]);
                        }
                        $entityContext->setState(EntityContext::UPDATE);
                        $this->entities->add($entityContext->getHash(), $entityContext);
                        break;
                        
                    case EntityContext::UPDATE:
                        $keyName = $entityAttributeData->getKey();
                        if(array_key_exists($keyName, $properties)){
                            $this->db->update($entityAttributeData->getTableName(), $properties, [$keyName => $properties[$keyName]]);
                        }else{
                            throw new EntityException(sprintf("Failed to update entity '%s'. Entity does not contain '%s' property.", $entityContext->getName(), $keyName));
                        }
                        
                        break;
                }
            }
        }
    }
}
