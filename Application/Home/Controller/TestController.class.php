<?php

namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
		$this->display('test/index');
		
    }
	
	public function base(){
		
		$this->display('base/index');
	}
	
	public function webuploader() {
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =      './Public/Uploads/'; // 设置附件上传根目录
		$upload->savePath  =      ''; // 设置附件上传（子）目录
		$upload->autoSub = false;  // 关闭子目录

		// 上传文件
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}else{// 上传成功 获取上传文件信息
			$pathArr = array();
			foreach($info as $file){
				array_push($pathArr, "Public/Uploads/".$file['savepath'].$file['savename']);
			}
			echo json_encode($pathArr);
		}
	}
}