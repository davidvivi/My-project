<?php

namespace Admin\Controller;

use Think\Controller;

class IndexController extends CommonController {

    public function index()
    {
        $name = $_SESSION['admin']['name'];
        
        $this->assign('name',$name);

        $this->display('Index/index');
    }
}