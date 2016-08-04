<?php
namespace Home\Controller;
use Think\Controller;
class AssessController extends Controller {
    public function index(){
        $this->display();
    }

    public function add(){
        $userid = $_SESSION['user']['id'];

        $goal = I('goal');
        

        $oid=$_GET['oid']; // 获取url里的参数值  ord=1
        $content=$_POST['rateContents'];  //评论内容 
        $assess=M('assess');
        $data['addtime']=time();
        $data['odid']=$oid;
        $data['uid']=$userid;
        $data['grade'] = $goal;
       

       // if(empty($content)){.........}
        $data['contents']=$content;
        $bol=$assess->add($data);
        if($bol){
            //$this->ajaxReturn(array('res'=>'1'));
             $this-> success('评价成功',U('Home/Assess/index'));
        }
        else{
            //$this->ajaxReturn(array('res'=>'-1'));
            echo "评价失败";
        }
    }
}
