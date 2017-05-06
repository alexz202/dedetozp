<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 17-5-6
 * Time: 下午10:12
 */
class msgAction extends BaseAction{
      /*
       * 添加消息
       */
      function addMsg(){
		$params=array(
		'mid'=>3,
		 'msg'=>'test',
		  'type'=>1,
		  'createTime'=>time(),
		  'updateTime'=>time(),
		);
		$msgModel=new MsgModel();
		$res=$msgModel->addMsg($params);
		if($res){
			echo 'success';
		}else{
			echo 'fail';
		}
      }


      /*
       * 回复
       */
      function reMsg(){
		$id=1;
	  	$remsg="2522";
		$msgModel=new MsgModel();
		$res=$msgModel->reMsg($id,$remsg);
		if($res){
		  echo 'success';
		}else{
		  echo 'fail';
		}

      }


      /*
       * 我的消息 (人大代表)
       */
      function toMyMsg(){
		$mid=3;
		$msgModel=new MsgModel();
		$list=$msgModel->getToMyMsg($mid);
		var_dump($list);
		$this->assign('list',$list);
		$this->display();
      }



    /*
     * 我提的消息
     */
     function MyAddMsg(){
		$mid=3;
		$msgModel=new MsgModel();
		$list=$msgModel->getMyAddMsg($mid);
		var_dump($list);
		$this->assign('list',$list);
		$this->display();
     }


	function Detail(){
		$id=1;
		$msgModel=new MsgModel();
		$info=$msgModel->where(array('id'=>$id))->find();
		$this->assign('info',$info);
		$this->display();
	}



}