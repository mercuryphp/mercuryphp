<?php

namespace Models;


//@System.Data.Entity.TableName("user")
//@System.Data.Entity.Key("id")
class User {
    
    public $id;
    
    //@System.Data.Entity.RandomString("12")
    public $username;
    
    //@System.Data.Entity.DataType("datetime")
    public $created_date;
    
    public $parent_id;
}

