<?php

namespace Models;

class DataContext extends \System\Data\Entity\DbContext {
    
    public function getUsers(){
        return $this->dbSet('Models.User');
    }
}