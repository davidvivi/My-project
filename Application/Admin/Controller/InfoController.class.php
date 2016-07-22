<?php

namespace Admin\Controller;

use Think\Controller;

class InfoController extends CommonController 
{
	public function index(){
		$info = D('Information');
		$count = $info -> count();
		$Page = new \Think\Page($count,3);//实例化分页类传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $info->order('addtime')->limit($Page->firstRow.','.$Page->listRows)->select();
		/*for($i=0;$i<$count;$i++){
			$list[$i]['addtime'] = date("Y-m-d H:i:s",$list[$i]['addtime']);
			
		}*/
		
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);  //总条数
		$this->display('Info/index'); // 输出模板
	}
}