<?php

namespace Admin\Controller;

use Think\Controller;

class InfoController extends CommonController 
{
	public function index(){
		$info = D('Information');
		$count = $info -> count();
		$Page = new \Think\Page($count,3);//实例化分页类传入总记录数和每页显示的记录数(3)
		$show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $info->order('addtime')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		//格式化时间戳
		foreach($list as $key=>$value){
			$list[$key]['addtime'] = date("Y-m-d H:i:s",$list[$key]['addtime']);
			
		}
		
		
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);  //总条数
		$this->display('Info/index'); // 输出模板
	}
	
	/*public function add(){
		if(IS_POST){
			echo 1;exit;
		}
		$info['contents'] = I('post.title');
		$info['url'] = I('post.url');
		$info['state'] = I['post.state'];
		$info['addtime'] = time();
		$map = $info;
		
		$s = D('information')->add($map);
		//echo D('information')->getLastsql();exit;
		if($s){
			$this->success('插入成功',U('__URL__/index'));
		}else{
			$this->error();
		}
	}*/
	
	public function infoEditForm()
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
}