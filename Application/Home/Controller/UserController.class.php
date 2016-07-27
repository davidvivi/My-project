<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends CommonController 
{
    public function index(){
        
        $this->display('user/index');
        
    }

    
}