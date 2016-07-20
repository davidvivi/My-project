<?php

namespace Admin\Controller;

use Think\Controller;


    class CommonController extends Controller
    {
        public function _initialize()
        {
            if (empty($_SESSION['admin']['name']))
            {
                redirect(U('Admin/login/login'));
            }
            /* else {
                  if (!IS_AJAX) {
                    $this->display('Common/index');
                  }
            } */

            
            
        }
    }