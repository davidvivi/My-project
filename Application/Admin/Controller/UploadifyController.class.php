<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-22
 */
 
namespace Admin\Controller;

class UploadifyController extends CommonController{
   
    public function upload(){
        $func = I('func');
        $path = I('path','temp');
        $info = array(
        	'num'=> I('num'),
            'title' => '',       	
        	'upload' =>U('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'size' => '4M',
            'type' =>'jpg,png,gif,jpeg',
            'input' => I('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        $this->display('uploadify/upload');
    }
    
    /*
              删除上传的图片
     */
    public function delupload(){
        $action=isset($_GET['action']) ? $_GET['action'] : null;
        $filename= isset($_GET['filename']) ? $_GET['filename'] : null;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if($action=='del' && !empty($filename)){
            $size = getimagesize($filename);
            $filetype = explode('/',$size['mime']);
            if($filetype[0]!='image'){
                return false;
                exit;
            }
            unlink($filename);
            exit;
        }
    }

}