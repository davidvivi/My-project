<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		
		$info = D('Information');
		$list = $info ->field('url,contents')->limit(4)->select();
	
		$this->assign('list',$list);
		$this->display('Index/index');
    }
}