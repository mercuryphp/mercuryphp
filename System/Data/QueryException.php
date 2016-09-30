<?php

namespace System\Data;

/**
 * Thrown when an SQL occurs while executing a query.
 */
class QueryException extends \PDOException {
    
    protected $sql;
    protected $params;
    
    /**
     * Initializes an instance of QueryException.
     */
    public function __construct($message, $sql, $params) {
        parent::__construct($message);
        $this->sql = $sql;
        $this->params = $params;
    }

    /**
     * Gets the SQL string.
     */
    public function getSql() : string {
        return $this->sql;
    }
    
    /**
     * Gets the SQL binding parameters.
     */
    public function getParams() : array {
        return $this->params;
    }
}