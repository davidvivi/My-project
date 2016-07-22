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
        //var_dump($userdata);exit;
        $userclass = new \Admin\Model\UserModel();
        $userdata = $userclass->userInfo($userdata);
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
        
       

        $this->assign('act',1);
        $this->assign('formdata',$data);
        $this->display('user/user-edit');
        
    }
    public function userEditForm()
    { 
        $id = I('id');
        $grade = I('grade');
        if($id){ 
            $userdetail = M('user_detail');
            $detaildata = $userdetail->field('grade')->where("uid='{$id}'")->select();
            $data['grade'] = $grade;
            $du = $userdetail->where("uid='{$id}'")->save($data);
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
    public function userSearch()
    { 
        $data = I('text');
        $sid = I('id'); //  区别是哪个页面
        
        $user = M('user');
        $whereuser['username'] = array('like',"%{$data}%");

        $whereuser['status'] = $sid;


        $userdata = $user->field('id,username,addtime,tel,status')->where($whereuser)->find();
        if(!$userdata)
        { 
            $wheretel['tel'] = array('like',"%{$data}%");
            $wheretel['status'] = $sid;
            $userdata = $user->field('id,username,addtime,tel,status')->where($wheretel)->find();
        }
        
        if($userdata){ 
            $this->ajaxReturn($userdata);
        }else{ 
            $this->ajaxReturn('失败');
        }
    }

    public function userFind(){ 

        $text = I('text'); // 搜索框里的内容
        $user = M('user');

        $sid = I('id');

        //$userdetail = M('user_detail');
        $whereuser['username'] = array('like',"%{$text}%");
        $whereuser['status'] = $sid;
        $wheretel['tel'] = array('like',"%{$text}%");
        $wheretel['status'] = $sid;

        $countuser = $user->where($whereuser)->count();
        $counttel = $user->where($wheretel)->count();
        $count = $countuser + $counttel;
        // $Page = new \Think\Page($count,5);
        // $show = $Page->show();



        $useruser = $user->field('id,username,addtime,tel,status')->where($whereuser)->select();
        $usertel = $user->field('id,username,addtime,tel,status')->where($wheretel)->select();
        $userdata = array_merge($useruser,$usertel);
        //dump($userdata);exit;   
        $userclass = new \Admin\Model\UserModel();
        $userdata = $userclass->userInfo($userdata);
        $userdata = $userclass->userSwitch($userdata);
        $this->assign('count', $count);
        $this->assign('act', 1);
        $this->assign('userdata',$userdata);
        //dump($userdata);exit;
        if($sid == 1){
            $this->display('user/user-list');
        }else{ 
            $this->display('user/user-del');
        }
    }
    
}