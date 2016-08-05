<?php

namespace Admin\Controller;

use Think\Controller;


    class CommonController extends Controller
    {
		protected $auth = null;
		
		
        public function _initialize()
        {
            if (empty($_SESSION['admin']['name']))
            {
                redirect(U('Admin/login/login'));
				exit();
            }
			
			$this->auth = new \Think\Auth();
			
			//登录成功后，获取到用户ID
			$name = $_SESSION['admin']['name'];
			$id = M('admin')->where("name='$name'")->getField('id');
			
			//调用权限验证方法，因为不同的用户有不用的权限
			$bool = $this->checkAuth($id);
			
			if( !$bool ){
            $this->error("抱歉！你没有此操作权限！");
            exit();
			}
		}
        
		protected function checkAuth( $id )
		{

			//特殊用户
			
			if($_SESSION['admin']['name']=="admin"){
				return true;
			}
			//dump($id);
			dump($this->auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $id));
			//判断普通管理员ID=1的有没有Admin/Article/Add模块的权限
			// dump( $this->auth->check('Admin/Article/Add', 1) );
			return $this->auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $id) ;

		}    
			
			
            /* else {
                  if (!IS_AJAX) {
                    $this->display('Common/index');
                  }
            } */

            
            
      
    }
