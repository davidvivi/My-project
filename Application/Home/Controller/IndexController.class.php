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
        $link_list = $link ->field('contents,url')->where($data)->order('state')->select();
        
        //轮播图
        $imgturn = M('imgturn');
        $img_list = $imgturn ->field('imgurl,addtime')->order('state')->where('state>0')->select();
        foreach($img_list as $key =>$val){
            $img_list[$key]['addtime']= date('Y-m-d',$val['addtime']);
        }
        //dump($img_list);
        //exit;
        //将二维数组转换成一位数组
        $imgurl = array_column($img_list,'imgurl');
        $addtime  = array_column($img_list,'addtime');
        //dump($imgurl);
        //dump($addtime);
        //exit;

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
		
		//首页人气推荐商品 遍历		
			$loved = M('goods')->field('id,goodname,addtime')->order('view desc')->limit(4)->select();		
			
			foreach($loved as $key =>$val){			
				$gid = $val['id'];			
				$loved[$key]['addtime'] = date('Y-m-d',$val['addtime']);			
				$data = M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
				$loved[$key]['picname'] = $data['picname'];		
			}
		
		//首页手机遍历
		$a = '2,4,';
		$map['typeid'] = array('like',"{$a}%");
		$map['state'] = '1';
		$phone = M('goods')->where($map)->field('id,goodname,addtime')->limit(8)->select();
		foreach($phone as $key =>$val){			
			$gid = $val['id'];			
			$phone[$key]['addtime'] = date('Y-m-d',$val['addtime']);			
			$data1= M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
			$phone[$key]['picname'] = $data1['picname'];		
		}
		
		//首页电脑遍历
		$a = '2,5,';
		$map['typeid'] = array('like',"{$a}%");
		$map['state'] = '1';
		$pc = M('goods')->where($map)->field('id,goodname,addtime')->limit(8)->select();
		foreach($pc as $key =>$val){			
			$gid = $val['id'];			
			$pc[$key]['addtime'] = date('Y-m-d',$val['addtime']);			
			$data1= M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
			$pc[$key]['picname'] = $data1['picname'];		
		}
		
		//首页服装遍历
		$a = '1,68,';
		$map['typeid'] = array('like',"{$a}%");
		$map['state'] = '1';
		$clo = M('goods')->where($map)->field('id,goodname,addtime')->limit(8)->select();
		foreach($clo as $key =>$val){			
			$gid = $val['id'];			
			$clo[$key]['addtime'] = date('Y-m-d',$val['addtime']);			
			$data1= M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
			$clo[$key]['picname'] = $data1['picname'];		
		}
		
		if($_SESSION['user']){
				
			
			$count = count(M('cart')->where('user_id='.$_SESSION['user']['id'])->select());
		}else{
			$count = 0;
		}
		$this->assign('count',$count);
		$this->assign('hot',$hot);
		$this->assign('pc',$pc);
		$this->assign('clo',$clo);
		$this->assign('phone',$phone);
		$this->assign('loved',$loved);        
		$this->assign('imgurl',$imgurl);
        $this->assign('addtime',$addtime);
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
