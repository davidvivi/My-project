<?php

namespace Home\Controller;

use Think\Controller;

class GdetailController extends Controller {
    
   public function index(){


	   $goodsId = I('goodsId');
       if(!$goodsId){   
            $goodsId = 25;
       }
       $detail = new \Home\Model\GdetailModel();
       //推荐商品
       $goodsdata = $detail->hostShop();
       //商品详细信息
       $GoodsDetail = $detail ->shows($goodsId); 
       //友情链接
       $link = M('link');
        $datalink['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($datalink)->select();
        $this->assign('link',$link_list);
       //dump($GoodsDetail);
       $goodsimg = $this -> geipic($goodsId);
       $assess = $this->assess($goodsId);
       $this->assign('geipic',$goodsimg);
       $this->assign('assess',$assess);
       $this->assign('GoodsDetail', $GoodsDetail);
       $this->assign('goodsdata',$goodsdata);
	   $this->display();
   }
   //评论
   public function assess($data)
   {    
        $Assess = D('assess');
        $User = D('user');
        $assessInfo['count'] = $Assess -> where('goodsid ='.$data) -> count();
        //$assessinfo = $Assess -> where('goodsid = '.$data)->select();
        foreach($assessinfo as $k => $v){ 
            $assessinfo[$k]['addtime'] = date('Y-m-d',$assessinfo[$k]['addtime']);
            $username = $User -> field('username') -> where('id ='.$v['uid']) -> select();
            $assessinfo[$k]['username'] = $username[0]['username'];
        }
        
        $data = $assessinfo;
        return $data; 
   }
   //商品图
   public function geipic($data)
   {    
        $Goodspic = D('goods_pic');
        $Goods = D('goods');

        $goodsimg = $Goodspic -> field('picname') -> where('gid='.$data) -> select();
        $goodstime = $Goods -> field('addtime') -> where('id='.$data)->find()['addtime'];
        $goodstime = date('Y-m-d',$goodstime);
        foreach ($goodsimg as $key => $value) {
            $goodsimg[$key] = $goodstime.'/'.$value['picname'];
        }
        //$goodsimg = $goodstime.$goodsimg;
        //dump($goodsimg);
        return $goodsimg;

   }
}
