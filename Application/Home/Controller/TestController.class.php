<?php

namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
		$this->display('Test/index');
		
    }
	public function base(){
		
		$this->display('base/index');
	}
	
	public function upload(){   
		$upload = new \Think\Upload();// 实例化上传类    
		$upload->maxSize = 3145728 ;// 设置附件上传大小  
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->saveName = time().'_'.mt_rand(); //文件名
		$upload->rootPath = './public/img/';
		$upload->savePath = ''; // 设置附件上传目录    
		// 上传文件    
		$info = $upload->upload(); 
		if(!$info) {
			// 上传错误提示错误信息  
			$this->error($upload->getError());    
		}else{
			print_r($info);
			 
		}
	}
}