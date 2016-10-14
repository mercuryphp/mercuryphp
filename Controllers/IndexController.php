<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    //@System.Web.Http.HttpMethod("get")
    public function index(\System\Web\Mvc\ $date){
        print_R($date); exit;
       return  "ff";
    }
}
