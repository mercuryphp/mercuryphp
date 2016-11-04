<?php

namespace System\Data\Entity;

use System\Core\Obj;
use System\Core\Date;
use System\Data\Database;
use System\Collections\Dictionary;

abstract class DbContext {
    
    private $db;
    private $dbSets;
    private $entitySchemaCollection;

    public function __construct(string $connectionString = null){
        
        if(null !== $connectionString){
            $this->db = new Database($connectionString);
        }

        $this->dbSets = new Dictionary();
        $this->entitySchemaCollection = new EntitySchemaCollection(new AttributeReader());
    }
    
    /**
     * Gets the database object for this context.
     */
    public function getDatabase() : Database {
        return $this->db;
    }

    /**
     * Sets or gets a DbSet using the specified $entityName.
     */
    public function dbSet(string $entityName){
        $entityName = str_replace('.', '\\', $entityName);
        if(!$this->dbSets->hasKey($entityName)){
            $this->dbSets->set($entityName, new DbSet($this, $entityName));
        }
        return $this->dbSets->get($entityName);
    }
    
    public function query($mql){
        return new Query($this->getDatabase(), $this->entitySchemaCollection, $mql);
    }

    public function getEntitySchemaCollection() : EntitySchemaCollection {
        return $this->entitySchemaCollection;
    }

    public function saveChanges(){
        $logs = [];
        foreach($this->dbSets as $dbSet){ 
            foreach($dbSet as $entityContext){

                $entityData  = Obj::getProperties($entityContext->getEntity());
                $schema = $this->entitySchemaCollection->getSchema($entityContext->getEntityName());

                foreach($entityData as $property => $value){
                    $propertySchemaCollection = $schema->getProperty($property);
                    
                    if(is_object($value)){
                        if($value instanceof Date){
                            $entityData[$property] = $value->toString();
                        }else{
                            if($this->dbSets->hasKey(get_class($value))){
                                $associatedEntityContext = $this->dbSets->get(get_class($value))->entry($value);
                                $associatedEntitySchema = $this->entitySchemaCollection->getSchema($associatedEntityContext->getEntity());
                                $entityData[$property] = Obj::getPropertyValue($associatedEntityContext->getEntity(), $associatedEntitySchema->getKey());
                            }
                        }
                    }
                    
                    if($propertySchemaCollection){
                        foreach($propertySchemaCollection as $propertySchema){
                            if($propertySchema instanceof DefaultValue){
                                if(null == $value){
                                    Obj::setPropertyValue($entityContext->getEntity(), $property, $propertySchema->getValue());
                                    $entityData[$property] = $propertySchema->getValue();
                                }
                            }
                        }
                    }
                }

                switch($entityContext->getState()){
                    case EntityContext::PERSIST:

                        $logs[] = $this->db->insert($schema->getTableName(), $entityData);
                        $id = $this->db->getInsertId($schema->getKey());
                        
                        if($id){
                            Obj::setPropertyValue($entityContext->getEntity(), $schema->getKey(),$id);
                        }
                        $entityContext->setState(EntityContext::PERSISTED);
                        break;
                        
                    case EntityContext::PERSISTED:
                        $params = [$schema->getKey() => $entityData[$schema->getKey()]];
                        $logs[] = $this->db->update($schema->getTableName(), $entityData, $params);
                        break;
                }
            }
        }
        return array_sum($logs);
    }
}

