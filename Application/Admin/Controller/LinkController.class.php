<?php

namespace Admin\Controller;

use Think\Controller;

class LinkController extends CommonController {
    
    public function index()
    {   
        $link = M('link');
        $count = $link->count();
        $Page = new \Think\Page($count,8);
        $show = $Page->show();

        $list = $link->field('id,contents,url,state')->limit($Page->firstRow.','.$Page->listRows)->select();
        $linkclass = new \Admin\Model\LinkModel();
        $linklist = $linkclass->stateSwitch($list);
        $this->assign('count', $count);
        $this->assign('page', $show);
        $this->assign('list',$linklist);
        $this->display('link/link-list');
    }
    public function linkEdit()
    { 
        $id = I('id');
      

        
        $list = M('link');
        
        $linklist = $list->field('id,contents,url,state')->where("id='{$id}'")->select();        
        $data = array_pop($linklist);
        
       

        //$this->assign('act',1);
        $this->assign('formdata',$data);
        $this->display('link/link-edit');
        
    }
    public function linkEditForm()
    { 
        $id = I('id');
        $url = I('url');
        $contents = I('contents');
        $state = I('state');
        $link = M('link');
        // 如果之前有同样的状态存在，把它变为0
        if($state != 0){
            $linklist = $link->field('id')->where("state='{$state}'")->select();
           
            $linkid = $linklist[0]['id'];
            $da['state'] = 0;
            $dl = $link->where("id='{$linkid}'")->save($da);
        }
              
        $data['url'] = $url;
        $data['contents'] = $contents;
        $data['state'] = $state;
        // 如果有id，则是编辑否则是添加
        if($id){
            $du = $link->where("id='{$id}'")->save($data);
        }else{ 
            $du = $link->add($data);
        }
        if($du){
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
    public function linkDelete()
    { 
        $id = I('id');
        $link = M('link');
        if($id){ 
            $du = $link->where("id='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
    public function linkAdd()
    { 

        $this->display('link/link-add');
    }
}