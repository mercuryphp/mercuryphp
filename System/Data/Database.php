<?php

namespace System\Data;

/**
 * An instance of this class can be used to perform CRUD operations on a database.
 */
class Database {
    
    protected $connectionString;
    protected $pdo;
    protected $profiler;
    
    /**
     * Initializes an instance of Database with a DSN string.
     */
    public function __construct(string $dsn, array $options = []){
        $this->connectionString = new ConnectionString($dsn);
        $this->profiler = new Profiler();
        
        try{
            $this->profiler->start();
            $this->pdo = new \PDO(
                $this->connectionString->getDsn(), 
                $this->connectionString->getUser(), 
                $this->connectionString->getPassword(), 
                $options
            );
            $this->profiler->log('Connected to database', ['dsn' => $this->connectionString->getDsn()], 'CONNECT');
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
        } catch (\PDOException $poe){
            throw new DatabaseException($poe->getMessage());
        }
    }
    
    /**
     * Executes an SQL query and returns a PDOStatement.
     */
    public function query(string $sql, array $params = []) : \PDOStatement {

        try{
            $this->profiler->start();
            $stm = $this->pdo->prepare($sql);
            $stm->execute($params);
            $this->profiler->log($sql, $params);
            return $stm;
        }catch (\PDOException $e){
            throw new QueryException($e->getMessage(), $sql, $params, $e->getCode(), $e);
        }
    }
    
    /**
     * Executes an SQL query and returns a single row.
     * Throws QueryException if an SQL exception occurs.
     */
    public function fetch(string $sql, array $params = array(), int $fetchSytle = \PDO::FETCH_OBJ){
        $stm = $this->query($sql, $params);
        return $stm->fetch($fetchSytle);
    }
    
    /**
     * Executes an SQL query and returns an array of rows.
     * Throws QueryException if an SQL exception occurs.
     */
    public function fetchAll(string $sql, array $params = array(), int $fetchSytle = \PDO::FETCH_OBJ){
        $stm = $this->query($sql, $params);
        return $stm->fetchAll($fetchSytle);
    }
    
    /**
     * Inserts data into a table specified by $table and returns the number 
     * of rows affected.
     * Throws QueryException if an SQL exception occurs.
     */
    public function insert(string $table, array $values) : int {
        
        if(is_object($values)){
            $values = \System\Core\Obj::getProperties($values);
        }
        
        $fields = array_keys($values);
        $sql = \System\Core\Str::set('INSERT INTO {table} ({fields}) ')->template(['table' => $table, 'fields' => join(',', $fields)]);

        $params = [];
        foreach($fields as $field){
            if($values[$field] instanceof DbFunction){
                $params['@'.$field] = $values[$field]->toString();
            }else{
                $params[':'.$field] = $values[$field];
            }
        } 
        $sql = $sql->append('VALUES ({values})')->template(['values' => join(',', array_keys($params))]);

        foreach($params as $param => $value){
            if(substr($param, 0,1) == '@'){
                $sql = $sql->replace($param, $value);
                unset($params[$param]);
            }
        }
        return $this->query($sql, $params)->rowCount();
    }
    
    /**
     * Updates data specified by $table and returns the number 
     * of rows affected.
     * Throws QueryException if an SQL exception occurs.
     */
    public function update(string $table, array $values, array $conditions) : int {
        
        if(is_object($values)){
            $values = \System\Core\Obj::getProperties($values);
        }

        $sql = \System\Core\Str::set('UPDATE {table} SET ')->template(['table' => $table]);

        $params = [];
        foreach($values as $field => $value){
            if($value instanceof DbFunction){
                $sql = $sql->append($field.'='.$value->toString().', ');
            }else{
                $params[':'.$field] = $value;
                $sql = $sql->append($field.'=:'.$field.', ');
            }
        }

        $sql = $sql->trim(', ')->append(' WHERE ');
        
        $idx=0;
        foreach($conditions as $field=>$value){
            if($idx > 0){
                $sql = $sql->append(' AND ');
            }

            $sql = $sql->append($field.'=:c_'.$field);
            $params[':c_'.$field] = $value;
            ++$idx;
        }

        return $this->query($sql, $params)->rowCount();
    }
    
    /**
     * Initiates a transaction.
     */
    public function beginTransaction() : bool {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commits a transaction.
     */
    public function commit() : bool {
        return $this->pdo->commit();
    }
    
    /**
     * Rolls back a transaction.
     */
    public function rollBack() : bool {
        return $this->pdo->rollBack();
    }
    
    /**
     * Checks if inside a transaction.
     */
    public function inTransaction() : bool {
        return $this->pdo->inTransaction();
    }
	
    /**
     * Gets the ID of the last inserted row or sequence value.
     */
    public function getInsertId($field = null){
        return $this->pdo->lastInsertId($field);
    }
    
    /**
     * Gets a database connection attribute.
     */
    public function getAttribute($attribute){
        return $this->pdo->getAttribute($attribute);
    }

    /**
     * Gets the connection string used to connect to the database.
     */
    public function getConnectionString() : ConnectionString {
        return $this->connectionString;
    }
    
    public function getProfiler() : Profiler {
        return $this->profiler;
    }
}

