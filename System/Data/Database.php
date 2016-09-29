<?php

namespace System\Data;

class Database {
    
    protected $connectionString;
    protected $pdo;
    
    public function __construct(string $dsn, array $options = []){
        $this->connectionString = new ConnectionString($dsn);
        
        try{
            $this->pdo = new \PDO(
                $this->connectionString->getDsn(), 
                $this->connectionString->getUser(), 
                $this->connectionString->getPassword(), 
                $options
            );
        } catch (\PDOException $poe){ print $poe->getCode();
            throw new DatabaseException($poe->getMessage());
        }
    }
    
    public function query(){
        
    }
}

