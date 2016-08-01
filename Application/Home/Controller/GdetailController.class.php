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
       $goodsdata = $detail->hostShop();
       $GoodsDetail = $detail ->shows($goodsId); 
       $assess = $detail->assess();
       $this->assign('assess',$assess);
       $this->assign('GoodsDetail', $GoodsDetail);
       $this->assign('goodsdata',$goodsdata);
	   $this->display();
   }


}
