<?php

namespace Admin\Controller;

use Think\Controller;

class UserController extends CommonController {
    // 会员列表  status 0代表已删除，1代表启用
    public function index()
    {   

        $choose = I('get.choose');
        $user = M('user');
        $userdetail = M('user_detail');

        if($choose == 1){ 
            $count = $user->where('status=1')->count();
        }elseif($chose == 0){
            $count = $user->where('status=0')->count();
        }else{ 
            $this->error('操作失败！');
        }
        $Page = new \Think\Page($count,8);
        $show = $Page->show();


        
        if($choose == 1){
            $userdata = $user->field('id,username,addtime,tel,status')->where('status=1')->limit($Page->firstRow.','.$Page->listRows)->select();
        }elseif($choose == 0){ 
            $userdata = $user->field('id,username,addtime,tel,status')->where('status=0')->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{ 
            $this->error('操作失败！');
        }
        for($i=0;$i<count($userdata);$i++)
        {
            $userid = $userdata[$i]['id'];              
            $detaildata = $userdetail->field('sex,email,address,grade')->where("uid='{$userid}'")->select();
            if($detaildata){ 
                $userdata[$i]['sex'] = $detaildata[0]['sex'];
                $userdata[$i]['email'] = $detaildata[0]['email'];
                $userdata[$i]['address'] = $detaildata[0]['address'];
                $userdata[$i]['grade'] = $detaildata[0]['grade'];
            }
            
        }  

        //var_dump($userdata);exit;
        $userclass = new \Admin\Model\UserModel();
        $userdata = $userclass->userSwitch($userdata);

        $this->assign('count', $count);
        $this->assign('page', $show);
        
        $this->assign('userdata',$userdata);
        if($choose == 1){
            $this->display('user/user-list');
        }else{ 
            $this->display('user/user-del');
        }
    }
    
    public function userDelete()
    { 
        $id = I('id');
        $user = M('user');
        $userdetail = M('user_detail');
        if($id){ 
            $du = $user->where("id='{$id}'")->delete();
            $dd = $userdetail->where("uid='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
    public function userReturn()
    { 
        $id = I('id');
        $i = I('i');

        $user = M('user');
        if($id){ 
            if($i == 1){ 
                $data['status'] = '0';
            }else{
                $data['status'] = '1';
            }
            $du = $user->where("id='{$id}'")->save($data);
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }
    }
    public function userEdit()
    { 
        $id = I('id');
        $user = M('user');
        $userdetail = M('user_detail');
        $userdata = $user->field('id,username,addtime,tel,status')->where("status=1 AND id='{$id}'")->select();
       
        $userid = $userdata[0]['id'];              
        $detaildata = $userdetail->field('sex,email,address,grade')->where("uid='{$userid}'")->select();
        
        $data1 = array_pop($userdata);
        $data2 = array_pop($detaildata);
        $data = array_merge($data1,$data2);
        
       


        $this->assign('formdata',$data);
        $this->display('user/user-edit');
        
    }
    public function userEditForm()
    { 
        $id = I('id');

        $userdetail = M('user_detail');

        if(ture){ 

        $detaildata = $userdetail->field('grade')->where("uid='{$id}'")->select();
  
            $data['grade'] = '2';
            $du = $userdetail->where("uid='{$id}'")->save($data);
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }

    }

   
}