<?php

namespace Admin\Controller;

use Think\Controller;

class ImgturnController extends CommonController {
    
    public function index()
    {   
        $imgturn = M('imgturn');
        $count = $imgturn->count();
        $Page = new \Think\Page($count,2);
        $show = $Page->show();
        $img = $imgturn->field('id,state,imgurl,category_id,imgname')->order('state')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count', $count);
        $this->assign('page', $show);
        
       
        
        
            
        $this->assign('data',$img);
        $this->display('imgturn/imgturn-list');
      
    }
    public function imgturnEdit()
    { 
        $id = I('id');
        $imgturn = M('imgturn');
        $imgturnlist = $imgturn->field('id,state')->where("id='{$id}'")->select();
        $data = array_pop($imgturnlist);
        $this->assign('formdata',$data);
        $this->display('imgturn/imgturn-edit');
    }
    public function imgturnEditForm()
    { 
        $id = I('id');
        //$url = I('url');
        //$contents = I('contents');
        $state = I('state');
        $imgturn = M('imgturn');
        // 如果之前有同样的状态存在，把它变为0
        if($state != 0){
            $imgturnlist = $imgturn->field('id')->where("state='{$state}'")->select();
           
            $imgturnid = $imgturnlist[0]['id'];
            $da['state'] = 0;
            $dl = $imgturn->where("id='{$imgturnid}'")->save($da);
        }
        $data['state'] = $state;
        // 如果有id，则是编辑否则是添加
        if($id){
            $du = $imgturn->where("id='{$id}'")->save($data);
        }else{ 
            $du = $imgturn->add($data);
        }
        if($du){
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }

    public function imgturnDelete()
    { 
        $id = I('id');
        $imgturn = M('imgturn');
        if($id){ 
            $du = $imgturn->where("id='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
    
    public function add(){
        
        $this->display('imgturn/picture-add');
        
    }

    public function imgAdd()
    { 
        $imgturn = M('imgturn');
                
        $category = I('category_id');
        $imgname = I('imgname');
        $state = I('state');
        $imgurl = I('imgurl');
              
        $data['category_id'] = $category;
        $data['imgname'] = $imgname;
        $data['state'] = $state;
        $data['imgurl'] = $imgurl;
        
        $du = $imgturn->add($data);
                   
        if($du){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  

}