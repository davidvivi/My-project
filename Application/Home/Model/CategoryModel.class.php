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
        $goodsdata = $goods->field('id,goodname,price,buy')->where('state=1')->order('buy desc')->limit(5)->select();   
        foreach($goodsdata as $key => $val){ 
            $gid = $val['id'];
            $picname = $goods_pic->where("gid='{$gid}'")->getField('picname');
            $goodsdata[$key]['picname'] = $picname;
            $goodsdata[$key]['saleprice'] = $val['price'] * 0.9;
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
            $path = $data['path'].$getid.',';
            $count = $goods->count();
            $Page = new \Think\Page($count,4);
            $show = $Page->show();
            $where['typeid'] = array('like',"{$path}%");
            $goodsdata = $goods->field('id,goodname,price')->where($where)->order($type)->limit($Page->firstRow.','.$Page->listRows)->select();
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
            }
            

        }else if($num == 2){ // 二级分类时
            $path = $data['path'].$getid.',';
            
            $goodsdata = $goods->field('id,goodname,price')->where("typeid='{$path}'")->order($type)->select();
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
            }   

        }else{ // 三级分类时
            $path = $data['path'];
            $count = $goods->where("typeid='{$path}'")->count();
            $Page = new \Think\Page($count,4);
            $show = $Page->show();
            $goodsdata = $goods->field('id,goodname,price')->where("typeid='{$path}'")->order($type)->limit($Page->firstRow.','.$Page->listRows)->select();
            foreach($goodsdata as $key => $val){ 
                $gid = $val['id'];
                $goods_url = $goods_pic->field('picname')->where("gid='{$gid}'")->select();    
                $goodsdata[$key]['goods_url'] = $goods_url;
            }
            
        }
        $page['count'] = $count;
        $page['page'] = $show;

        $return = array($goodsdata,$page);
        return $return;
        

    }
}
