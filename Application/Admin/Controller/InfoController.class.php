<?php

namespace Admin\Controller;

use Think\Controller;

class InfoController extends CommonController 
{
	public function index(){
		$this->display('Info/index');
	}
}