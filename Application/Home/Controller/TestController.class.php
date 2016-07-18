<?php

namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
		$this->display('Base/index');
		
    }
}