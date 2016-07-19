<?php
    namespace Admin\Controller;
    use Think\Controller;

    class LoginController extends Controller
    {
        public function login ()
        {
            $this->display();
        }

        /**
            登录检查
        **/

        public function checkLogin()
        {
            $Admin = M('admin');
            //$arr['name'] = I('post.name');
            //$arr['password'] = I('post.password');
            $map['name'] = I('post.name');
            //$arr['password'] = password_hash(I('post.password'),PASSWORD_DEFAULT);
            $Verify = new \Think\Verify();
            
            $verifyCode = $Verify->check(I('post.checkCode'));
            
            if(!$verifyCode){    
                $this->error('验证失败');
            }

            $password=$Admin->field('password')->where($map)->find();

            //dump($password);
            //echo $Admin->getLastSql();
            //exit;

            $bool = password_verify(I('post.password'),$password['password']);

            if(!$bool){ 
                $this->error('登陆失败');
            }else{  
                $this->success('登陆成功',U('index/index'),2);
            }

            





            
        }
        
            



        /**
            生成验证码
        **/
        public function verify() {
            $Verify = new \Think\Verify(array(   
            'length' => 4,
            //'useNoise' => FALSE,
            'fontSize' => 14,
            'imageW'   =>100,
            'imageH'    => 35,
            //'useCurve'  =>false,
            'fontttf' => '5.ttf',
            ));
            $Verify->entry();
        }

        /**
            ajax 验证用户名
        **/
        public function ajaxCheckName()
        {
            if (IS_AJAX)
            {
                $Admin = M('user');

                $count = $Admin->where($_POST)->count();

                if ($count) {
                    $this->ajaxReturn(1);
                } else{
                    $this->ajaxReturn(0);
                }
            }
        }


        /**
            注销
        **/
        public function lyout ()
        {
            unset($_SESSION['admin']);
            header('Location:'.U('Admin/login/login'));
        }


    }