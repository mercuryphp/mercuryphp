<?php

namespace System\Data\Entity;

class DbSet implements \IteratorAggregate{
    
    protected $db;
    protected $entityName;
    protected $entityAttributeData;
    protected $entities = [];
    
    public function __construct(DbContext $db, string $entityName){
        $this->db = $db;
        $this->entityName = $entityName;
        $this->entityAttributeData = $this->db->getEntityAttributeData()->get($this->entityName);
    }
    
    public function add($entity){
        $entityContext = new EntityContext($entity,1);
        $this->entities[] = $entityContext;
        return $entityContext;
    }
    
    public function find($params){
        
        if(is_scalar($params)){
            $params = [$this->entityAttributeData->getKey() => $params];
        }
        
        $entity = $this->getQueryBuilder($params)->single($this->entityName, $params);
        
        if($entity){
            $entityContext = $this->add($entity)->setState(EntityContext::UPDATE);
            $this->db->getEntities()->add($entityContext->getHash(),$entityContext);
            return $entity;
        }
        return false;
    }

    public function getValidators(){
        
        $fields = $this->entityAttributeData->getFields();
        $validators = [];
        
        foreach($fields as $field => $attributes){
            foreach($attributes as $instance){
                if($instance instanceof \System\Data\Validation\Validator){
                    $validators[$field][] = $instance;
                }
            }
        }
        return $validators;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->entities);
    }
    
    protected function getQueryBuilder($params){
        $qb = $this->db->getQueryBuilder()
            ->select()
            ->from($this->entityAttributeData->getEntityName());
            
        foreach($params as $field => $value){
            $qb->where($field, $value);
        }
        return $qb;
    }
}