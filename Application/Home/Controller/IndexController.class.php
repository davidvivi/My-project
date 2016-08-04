<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){

        if(!empty($_SESSION['user'])){
			$name = $_SESSION['user']['name'];
			$this->assign('name',$name);
        }

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

        // 商品分类
        $categoryclass = new \Home\Model\IndexModel();
        $categorydata = $categoryclass->cateData();

        // dump($categorydata);
		
		//首页热卖推荐商品 遍历
		$hot = M('goods')->field('id,goodname,addtime')->order('buy desc')->limit(4)->select();
		foreach($hot as $key =>$val){
			$gid = $val['id'];
			$hot[$key]['addtime'] = date('Y-m-d',$val['addtime']);
			$data = M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
			$hot[$key]['picname'] = $data['picname'];
		}
		
		$this->assign('hot',$hot);
        $this->assign('img',$imgurl);
        $this->assign('link',$link_list);
		$this->assign('list',$list);
        $this->assign('categorydata',$categorydata);
		$this->display('Index/index');
    }

	public function logout(){
		
		unset($_SESSION['user']);
		
		$this->ajaxReturn('1');
	}
}
