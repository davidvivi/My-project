<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		// 公告
		$info = D('Information');
		$list = $info->field('url,contents')->where('state=1')->order('addtime desc')->limit(6)->select();
		// 友情链接
        $link = M('link');
        $data['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($data)->select();
        $this->assign('link',$link_list);

		if($_SESSION['user']){
			$name = $_SESSION['user']['name'];
			$this->assign('name',$name);
		}
		$this->assign('list',$list);
		$this->display('Index/index');
    }
}