<?php

namespace System\Data\Entity;

use System\Core\Obj;

class DbSet implements \IteratorAggregate {
    
    protected $dbContext;
    protected $entityName;
    protected $schema;
    protected $entities = [];
    
    public function __construct(DbContext $dbContext, string $entityName){
        $this->dbContext = $dbContext;
        $this->entityName = str_replace('.', '\\',$entityName);
        $this->schema = $this->dbContext->getEntitySchemaCollection()->add($this->entityName);
    }
    
    public function find($condition, bool $default = false){
        if(is_scalar($condition)){
            $params[(string)$this->schema->getKey()] = $condition;
        }elseif(is_object($condition)){
            $schema = $this->dbContext->getEntitySchemaCollection()->add(get_class($condition));
            $params[$schema->getKey()] = Obj::getPropertyValue($condition, $schema->getKey());
        }else{
            $params = $condition;
        }
        
        $query = new QueryBuilder($this->dbContext->getDatabase(), $this->schema->getTableName(), '*');
        $entity = $query->where($params)->toSingle($this->entityName);
        
        if($entity){
            $this->add($entity)->setState(EntityContext::PERSISTED);
            return $entity;
        }

        if($default){
            return Obj::getInstance($this->entityName);
        }
    }
    
    public function findAll($condition){
        if(is_scalar($condition)){
            $params[(string)$this->schema->getKey()] = $condition;
        }elseif(is_object($condition)){
            $schema = $this->dbContext->getEntitySchemaCollection()->add(get_class($condition));
            $params[$schema->getKey()] = Obj::getPropertyValue($condition, $schema->getKey());
        }else{
            $params = $condition;
        }

        $query = new QueryBuilder($this->dbContext->getDatabase(), $this->schema->getTableName(), '*');
        $entities = $query->where($params)->toList($this->entityName);
        
        foreach($entities as $entity){
            $this->add($entity)->setState(EntityContext::PERSISTED);
        }
        
        return new DbResultList($entities);
    }
    
    public function select($fields = '*'){
        return new QueryBuilder($this->dbContext->getDatabase(), $this->schema->getTableName(), $fields);
    }

    public function add($entity){
        if(get_class($entity) != $this->entityName){
            throw new \Exception("Entity must be of type" . $this->entityName);
        }
        $entityContext = new EntityContext($entity);
        $this->entities[$entityContext->getHashCode()] = $entityContext;
        return $entityContext;
    }
    
    public function entry($entity){
        return $this->entities[spl_object_hash($entity)];
    }

    public function getEntities(){
        return $this->entities;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->entities);
    }
}