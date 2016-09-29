<?php

namespace System\Data;

class Database {
    
    public function __construct(string $dsn){
        $d = new ConnectionString($dsn);
        print_R($d->getUser());
    }
    public function query(){
        
    }
}

