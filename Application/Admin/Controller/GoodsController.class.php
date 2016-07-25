<?php

namespace Admin\Controller;

use Think\Controller;

// 模型的名字与表名要一致
class GoodsController extends CommonController
{
	public function index(){
		$model = M();
        $list = $model->field('a.id,goodname,price,state,discribe,addtime,picname')
        ->where('a.id=ud.gid')
        //可以使用table()指明你要查询的表
        ->table( array('think_goods'=>'a','think_goods_pic'=>'ud') )
        ->select();
		$this->assign('list',$list);
		$this->display('Goods/index');
	}
	
	public function add(){
		$model = D('goods');
		$post = I('post');
		
	}
}