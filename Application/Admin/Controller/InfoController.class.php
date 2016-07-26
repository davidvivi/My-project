<?php

namespace Admin\Controller;

use Think\Controller;

class InfoController extends CommonController 
{
	public function index(){
		$info = D('Information');
		$count = $info -> count();
		$Page = new \Think\Page($count,3);//实例化分页类传入总记录数和每页显示的记录数(3)
		$show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $info->order('addtime')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		//时间戳转换，状态对应
		foreach($list as $key=>$value){
			$list[$key]['addtime'] = date("Y-m-d H:i:s",$list[$key]['addtime']);
            if($value['state'] == 1){ 
                $list[$key]['state'] = '开启';
            }else{ 
                $list[$key]['state'] = '关闭';
            }
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);  //总条数
		$this->display('Info/index'); // 输出模板
	}
    public function infoDelete()
    { 
        $id = I('id');
        $info = M('information');
        if($id){ 
            $dd = $info->where("id='{$id}'")->delete();
            if($dd){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
	
	public function infoAdd()
    {
        $this->display('Info/gonggao-add');
	}
	
	public function infoForm()
    { 
        
        $url = I('url');
        $contents = I('contents');
        $state = I('state');
        $information = M('information');
        $info = M('information');    
        $data['url'] = $url;
        $data['contents'] = $contents;
        $data['state'] = $state;
        $data['addtime'] = time();        
        $dd = $info->add($data);     
        if($dd){
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
}