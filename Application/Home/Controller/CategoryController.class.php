<?php

namespace Home\Controller;

use Think\Controller;

class CategoryController extends Controller 
{
    public function index(){
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $this->assign('name',$name);
        }
        $category = M('category');
        // 得到一级分类
        $firstcate = $category->field('id,name')->where('pid=0')->select();
        foreach($firstcate as $key => $val){ 
            $pidtwo = $val['id']; 
            // 得到二级分类
            $data = $category->field('id,pid,name')->where("pid='{$pidtwo}'")->select();
            $secondcate[] = $data; 
            foreach($data as $k => $v){ 
                $pidthird = $v['id'];
                //得到三级分类
                $thirddata = $category->field('id,pid,name')->where("pid='{$pidthird}'")->select();
                // 把三级分类放到二级的son里
                $secondcate[$key][$k]['son'] = $thirddata;
            }
        }
        //dump($thirdcate);
        //dump($secondcate);
        //echo '-----';
        //dump($firstcate);

        // 友情链接
        $link = M('link');
        $datalink['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($datalink)->select();
        

        // 得到传过来的id
        // $id = I('id');

        // 假设传来的ID一级分类id

        $id = 1; // 一级id
        //$firstcate1 = $category->field('id,name')->where("id='{$id}'")->find();
        //dump($firstcate1);
        
        // 根据一级id得到一级分类
        //dump($secondcate);
        echo '121111111111111';
        foreach($firstcate as $k1 => $v1){ 
            if($v1['id'] == $id){ 
                $firstname = $v1['name'];
            }
        }
        // 得到二级分类
        $secondca = $category->field('id,pid,name')->where("pid='{$id}'")->select();
        //dump($secondca);
        foreach($secondca as $k2 => $v2){
            $secondid = $v2['id'];
            $thirdca = $category->field('id,pid,name')->where("pid='{$secondid}'")->select();
            $secondca[$k2]['third'] = $thirdca;
        }
        dump($secondca);
        //$second

        $this->assign('link',$link_list);
        $this->assign('firstcate',$firstcate);
        $this->assign('secondcate',$secondcate);
        $this->assign('firstname',$firstname);
        $this->assign('secondca',$secondca);
        //$this->assign('thirdcate',$thirdcate);
        $this->display('category/category');
        
    }

    
}