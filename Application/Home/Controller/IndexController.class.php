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
        

        //轮播图
        $imgturn = M('imgturn');
        $img_list = $imgturn ->field('imgurl')->order('state')->where('state>0')->select();
        //将二维数组转换成一位数组
        $imgurl = array_column($img_list,'imgurl');
        


        $this->assign('img',$imgurl);
        $this->assign('link',$link_list);
		$this->assign('list',$list);
		$this->display('Index/index');
    }
}