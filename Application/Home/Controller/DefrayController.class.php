<?php
namespace Home\Controller;

use Think\Controller;

    class DefrayController extends Controller {
     public function index(){
        // 友情链接
        $link = M('link');
        $datalink['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($datalink)->order('state')->select();
        $hot = M('goods')->field('id,goodname,addtime')->order('buy desc')->limit(8)->select();
        foreach($hot as $key =>$val){           
                $gid = $val['id'];          
                $hot[$key]['addtime'] = date('Y-m-d',$val['addtime']);          
                $data = M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
                $hot[$key]['picname'] = $data['picname'];       
            } 
        //dump($hot);
        $this->assign('link',$link_list);
        $this->assign('hot',$hot);
        $this->display('Cart/defray');
        }

    }
