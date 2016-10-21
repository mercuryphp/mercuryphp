<?php

namespace System\Data\Entity;

abstract class DbContext {
    
    private $dbSets;
    
    public function __construct(){
        $this->dbSets = new \System\Collections\Dictionary();
    }

    /**
     * Registers an entity type in the DbSet collection.
     * 
     * @param   string $entityName
     * @return  System.Data.Entity.DbSet
     */
    public function dbSet($entityName){
        if(!$this->dbSets->hasKey($entityName)){
            //$metaData = $this->metaCollection->get($entityName);
            $this->dbSets->set($entityName, new DbSet($entityName, $this));
        }
        return $this->dbSets->get($entityName);
    }
    
    public function saveChanges(){
        
        foreach($this->dbSets as $dbSet){
            print_R($dbSet); exit;
        }
    }
}

