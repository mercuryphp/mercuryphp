<?php

namespace System\Data\Entity;

use System\Core\Obj;
use System\Core\Arr;

abstract class DbContext {
    
    protected $db;
    protected $config;
    protected $query;
    protected $dbSets = [];
    protected $entityAttributeData;
    protected $entities;
    
    public function __construct($dsn = '', DbConfiguration $config = null){
        if($dsn){
            $this->db = new \System\Data\Database($dsn);
        }
        if(null == $config){
            $this->config = new DbConfiguration();
        }
        $this->entityAttributeData = new Arr();
        $this->entities = new Arr();
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
    
    public function getEntityAttributeData(){
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
        
        $this->entityAttributeData->add($this->config->getEntityAttributeDriver()->read($name), $name);
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
                            $refObjProperties = Obj::getProperties($refObjContext->getEntity());

                            if(array_key_exists($property, $refObjProperties)){
                                $properties[$property] = $refObjProperties[$property];
                            }else{
                                throw new EntityRelationshipException(sprintf("One to one relationship between '%s' and '%s' failed because '%s' does not contain '%s' property.", get_class($entity), get_class($refObj), get_class($refObj), $property));
                            }
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
                        $this->entities->add($entityContext, $entityContext->getHash());
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
