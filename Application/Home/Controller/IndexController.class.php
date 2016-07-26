<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		
		$info = D('Information');
		$list = $info ->field('url','contents')->limit(4)->select();
		//友情链接
        $link = M('link');
        $data['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($data)->select();
        $this->assign('link',$link_list);

		$this->assign('list',$list);
		$this->display('Index/index');
    }
}