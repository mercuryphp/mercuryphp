<?php

namespace System\Data;

use System\Core\Str;
use System\Core\Obj;

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
    public function fetch(string $sql, array $params = [], int $fetchSytle = \PDO::FETCH_OBJ){
        $stm = $this->query($sql, $params);
        return $stm->fetch($fetchSytle);
    }
    
    /**
     * Executes an SQL query and returns an array of rows.
     * Throws QueryException if an SQL exception occurs.
     */
    public function fetchAll(string $sql, array $params = [], int $fetchSytle = \PDO::FETCH_OBJ){
        $stm = $this->query($sql, $params);
        return $stm->fetchAll($fetchSytle);
    }
    
    public function fetchRecordSet(string $sql, array $params = []) : array{
        $rows = $this->fetchAll($sql, $params, \PDO::FETCH_ASSOC);
        
        foreach($rows as $idx => $row){
            $rows[$idx] = new RecordSet($row);
        }
        return $rows;
    }
    
    /**
     * Executes an SQL query and returns a single row.
     * Throws QueryException if an SQL exception occurs.
     */
    public function table(string $tableName, array $params = [], int $fetchSytle = \PDO::FETCH_OBJ){
        $conditions = '';
        foreach($params as $name=>$value){
            $conditions .= ' AND ' . $name . '=:' . $name; 
        }
        $sql = Str::set("SELECT * FROM {table} WHERE {conditions}")->tokens(['table' => $tableName, 'conditions' => trim($conditions, ' AND')]);

        return $this->fetch($sql, $params, $fetchSytle);
    }
    
    /**
     * Inserts data into a table specified by $table and returns the number 
     * of rows affected.
     * Throws QueryException if an SQL exception occurs.
     */
    public function insert(string $table, $values) : int {
        
        if(is_object($values)){
            $values = Obj::getProperties($values);
        }
        
        $fields = array_keys($values);
        $sql = Str::set('INSERT INTO {table} ({fields}) VALUES (')->tokens(['table' => $table, 'fields' => join(',', $fields)]);

        $params = [];
        foreach($fields as $field){
            if($values[$field] instanceof DbFunction){
                $sql = $sql->append($values[$field]->toString())->append(',');
            }else{
                $sql = $sql->append(':'.$field)->append(',');
                $params[':'.$field] = $values[$field];
            }
        } 
        $sql = $sql->trim(',')->append(')');

        return $this->query($sql, $params)->rowCount();
    }
    
    /**
     * Inserts data into a table specified by $table and returns the number 
     * of rows affected.
     * Throws QueryException if an SQL exception occurs.
     */
    public function multiInsert(string $table, $rows) : int {
        
        $sb = new \System\Core\StrBuilder();
        $params = [];
        
        foreach($rows as $idx=>$values){

            if(is_object($values)){
                $values = Obj::getProperties($values);
            }

            $fields = array_keys($values);
            $sql = Str::set('INSERT INTO {table} ({fields}) VALUES (')->tokens(['table' => $table, 'fields' => join(',', $fields)]);

            foreach($fields as $field){
                if($values[$field] instanceof DbFunction){
                    $sql = $sql->append($values[$field]->toString())->append(',');
                }else{
                    $sql = $sql->append(':'.$field.'_'.$idx)->append(',');
                    $params[':'.$field.'_'.$idx] = $values[$field];
                }
            } 
            $sb->append($sql->trim(',')->append(');'))->appendLine();
        }

        return $this->query($sb, $params)->rowCount();
    }
    
    /**
     * Updates data specified by $table and returns the number 
     * of rows affected.
     * Throws QueryException if an SQL exception occurs.
     */
    public function update(string $table, $values, array $conditions) : int {
        
        if(is_object($values)){
            $values = Obj::getProperties($values);
        }

        $sql = Str::set('UPDATE {table} SET ')->tokens(['table' => $table]);

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