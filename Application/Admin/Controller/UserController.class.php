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


        $grade_arr = array('','青铜用户','白银用户','黄金用户','白金用户','王者用户');
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
                $userdata[$i]['grade'] = $grade_arr[$detaildata[0]['grade']];
            }
            $userdata[$i]['addtime'] = date('Y-m-d H:i:s',$userdata[$i]['addtime']);
            if($userdata[$i]['status'] == 0){ 
                $userdata[$i]['status'] = '已删除';
            }else{ 
                $userdata[$i]['status'] = '已启用';
            }
            if($userdata[$i]['sex'] == 0){ 
                $userdata[$i]['sex'] = '女';
            }else{ 
                $userdata[$i]['sex'] = '男';
            }
        }  


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

        $user = M('user');
        if($id){ 
            $data['status'] = '1';
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
        $this->display('user/user-edit');
        
    }
}