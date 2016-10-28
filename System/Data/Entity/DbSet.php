<?php

namespace System\Data\Entity;

use System\Core\Obj;
use System\Data\QueryBuilder;

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
        
        if(!is_array($condition)){
            $params[(string)$this->schema->getKey()] = $condition;
        }else{
            $params = $condition;
        }
        
        $query = new QueryBuilder($this->dbContext->getDatabase());
        $data = $query->select($this->schema->getTableName(), '*')->where($params)->toSingle([], $this->entityName);
        
        if($data){
            $entity = $this->toEntity($data);
            $entityContext = new EntityContext($entity);
            $entityContext->setState(EntityContext::PERSISTED);

            $this->add($entity)->setState(EntityContext::PERSISTED);
            $this->dbContext->getEntities()->add($entityContext);

            return $entity;
        }

        if($default){
            return Obj::getInstance($this->entityName);
        }
    }
    
    public function select(){
        $query = new QueryBuilder($this->dbContext->getDatabase());
        return $query->select($this->schema->getTableName(), '*');
    }

    public function add($entity){
        if(get_class($entity) != $this->entityName){
            print "error";
        }
        $entityContext = new EntityContext($entity);
        $this->entities[] = $entityContext;
        return $entityContext;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->entities);
    }
}