<?php

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller {

    //初始化操作
    public function _initialize()
    {
         if( !$_SESSION['admin_name'] ){

            $this->error('请登录', U('Login/index'));
        }
    }
}