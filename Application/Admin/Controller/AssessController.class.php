<?php

    namespace Admin\Controller;

    use Think\Controller;

    class AssessController extends CommonController 
    { 
        public function index(){
            $Assess = M('assess');
            

            $count = $Assess->count();

            $Page = new \Think\Page($count,5);

            $show = $Page->show();

            $list = $Assess->field('id,uid,odid,grade,contents,addtime')->limit($Page->firstRow.','.$Page->listRows)->select();
            //dump($list);
            //dump($count);
            $this->assign('list',$list);// 赋值数据集
            $this->assign('count',$count);// 
            $this->assign('page',$show);// 赋值分页输出
            $this->display('index/assess-list'); // 输出模板
		
        }

        public function linkDelete()
    { 
        $id = I('id');
        $assess = M('assess');
        if($id){ 
            $du = $assess->where("id='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
}