<?php

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
	public function _initialize()
        {
			if (empty($_SESSION['user']['name']))
            {
                redirect(U('Home/index/login'));
				exit();
            }
		}
	
}