<?php

namespace Home\Controller;

use Think\Controller;

class CartController extends CommonController {
    
   public function index(){
	   $this->assign('name',$_SESSION['user']['name']);
	   $this->display('cart/index');
   }
   
   public function logout(){
	   
	   unset($_SESSION['user']);
	   $this->ajaxReturn('1');
	   
   }
}
