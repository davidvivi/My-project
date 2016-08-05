<?php

namespace Home\Model;

use Think\Model;

// 模型的名字与表名要一致
class CategoryModel extends Model
{

    public function idHandle($getid)
    {

        $category = M('category');
        $data = $category->field('pid,path,name')->where("id='{$getid}'")->find();
        // 计算路径中的逗号有几个，可以得知几级分类
        $num = substr_count($data['path'],',');
        $cate['num'] = $num;
        // 根据几级分类得到它的一级分类id
        if($num == 3){ 
            // 得到它的父类id 即二级分类id  secondid
            $secondid = $data['pid'];
            // 得到二级分类名
            $secondname = $category->where("id='{$secondid}'")->getField('name');
            // 得到它的一级分类id  firstid 
            $firstid = $category->where("id='{$secondid}'")->getField('pid');
            // 得到它的一级分类名
            $firstname = $category->where("id='{$firstid}'")->getField('name');
            // 把分类名给返回变量
            $cate['firstname'] = $firstname;
            $cate['secondname'] = $secondname;
            $cate['thirdname'] = $data['name'];
            // 把分类id给返回变量
            $cate['firstid'] = $firstid;
            $cate['secondid'] = $secondid;
            $cate['thirdid'] = $getid;

        }else if($num == 2){ 
            // 它的父类id就是它的一级分类id 
            $firstid = $data['pid'];
            // 得到一级分类名
            $firstname = $category->where("id='{$firstid}'")->getField('name');
            // 二级分类名
            $secondname = $data['name'];
            // 把分类名给返回变量
            $cate['firstname'] = $firstname;
            $cate['secondname'] = $secondname;

            // 把分类id给返回变量
            $cate['firstid'] = $firstid;
            $cate['secondid'] = $getid;

        }else{ 
            // 它的id就是它的一级分类id
            // 把一级分类id给返回变量
            $cate['firstid'] = $getid;
            // 把分类名给返回变量
            $cate['firstname'] = $data['name'];
        }

        // 返回参数
        return $cate;
    }

    // 得到热卖商品
    public function hostShop()
    {

        $goods = M('goods');
        $goods_pic = M('goods_pic');
        $goodsdata = $goods->field('id,goodname,price,buy,addtime')->where('state=1')->order('buy desc')->limit(5)->select();  
        foreach($goodsdata as $key => $val){ 
            $gid = $val['id'];
            $picname = $goods_pic->where("gid='{$gid}'")->getField('picname');
            $goodsdata[$key]['picname'] = $picname;
            $goodsdata[$key]['saleprice'] = intval($val['price'] / 0.9);
            // 时间转换
            $goodsdata[$key]['addtime'] = date('Y-m-d',$goodsdata[$key]['addtime']);
        }
        return $goodsdata;
    }

    public function goodsShop($getid,$type)
    { 

        $category = M('category');
        $goods = M('goods');
        $goods_pic = M('goods_pic');
        $data = $category->field('pid,path,name')->where("id='{$getid}'")->find();
        
        // 计算路径中的逗号有几个，可以得知几级分类
        $num = substr_count($data['path'],',');
        // 一级分类时
        if($num == 1){
            // 拼接goods表的typeid
            $path = $getid.',';
            $where['typeid'] = array('like',"{$path}%");
            $where['state'] = 1;
            $count = $goods->where($where)->count();
            
            $Page = new \Think\Page($count,4);
            $show = $Page->show();
            // 当是新品和购买量是倒序查询
            if($type != 'addtime' && $type != 'buy'){
                $goodsdata = $goods->field('id,goodname,price,addtime,buy')->where($where)->order($type)->limit($Page->firstRow.','.$Page->listRows)->select();
            }else{ 
                $goodsdata = $goods->field('id,goodname,price,addtime,buy')->where($where)->order("{$type} desc")->limit($Page->firstRow.','.$Page->listRows)->select();
            }
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
                // 时间转换
                $goodsdata[$key]['addtime'] = date('Y-m-d',$goodsdata[$key]['addtime']);
                }
            

        }else if($num == 2){ // 二级分类时
            // 拼接goods表的typeid
            $path2 = substr($data['path'], 2);
            $path = $path2.$getid.',';
            $where['typeid'] = array('like',"{$path}%");
            $where['state'] = 1;
            $count = $goods->where($where)->count();
            $Page = new \Think\Page($count,4);
            $show = $Page->show();

            // 当是新品和购买量是倒序查询
            if($type != 'addtime' && $type != 'buy'){
                $goodsdata = $goods->field('id,goodname,price,buy,addtime')->where($where)->order($type)->limit($Page->firstRow.','.$Page->listRows)->select();
            }else{
                $goodsdata = $goods->field('id,goodname,price,addtime,buy')->where($where)->order("{$type} desc")->limit($Page->firstRow.','.$Page->listRows)->select(); 
            }
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
                // 时间转换
                $goodsdata[$key]['addtime'] = date('Y-m-d',$goodsdata[$key]['addtime']);
                }   

        }else{ // 三级分类时
            // 拼接goods表的typeid
            $path3 = substr($data['path'], 2);
            $path = $path3.$getid.',';
            $where['typeid'] = $path;
            $where['state'] = 1;
            $count = $goods->where($where)->count();
            $Page = new \Think\Page($count,4);
            $show = $Page->show();
            // 当是新品和购买量是倒序查询
            if($type != 'addtime' && $type != 'buy'){
                $goodsdata = $goods->field('id,goodname,price,addtime,buy')->where($where)->order($type)->limit($Page->firstRow.','.$Page->listRows)->select();
            }else{
                $goodsdata = $goods->field('id,goodname,price,addtime,buy')->where($where)->order("{$type} desc")->limit($Page->firstRow.','.$Page->listRows)->select();
            }
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
                // 时间转换
                $goodsdata[$key]['addtime'] = date('Y-m-d',$goodsdata[$key]['addtime']);
                }
            
        }
        $page['count'] = $count;
        $page['page'] = $show;

        $return = array($goodsdata,$page);

        return $return;
    }

    // 搜索
    public function cateSearch($search)
    {
        $goods = M('goods');
        $goods_pic = M('goods_pic');

        $data['goodname'] = array('like',"%{$search}%");
        $data['keyword'] = array('like',"%{$search}%");
        $data['discribe'] = array('like',"%{$search}%");
        $data['_logic'] = 'or';
        $map['_complex'] = $data;
        $map['state'] = 1;
        $count = $goods->where($map)->count();
        $Page = new \Think\Page($count,1);
        $show = $Page->show();
        $goodscate = $goods->field('id,price,goodname,buy,addtime')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($goodscate as $key => $val){
            $gid = $val['id'];
            $pic = $goods_pic->field('picname')->where("gid='{$gid}'")->select();
            $goodscate[$key]['goods_url'] = $pic;
            // 时间转换
            $goodscate[$key]['addtime'] = date('Y-m-d',$goodscate[$key]['addtime']);
            
        }
        $cate['page']['page'] = $show;
        $cate['page']['count'] = $count;
        $cate['data'] = $goodscate;
        return $cate;
    }
}
