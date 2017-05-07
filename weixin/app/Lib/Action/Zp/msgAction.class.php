<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 17-5-6
 * Time: 下午10:12
 */
class msgAction extends BaseAction{
	/*
	 *
	 */

	private $typeStr_1='周浦镇人大代表之家';
	private $typeStr_2 = '人大代表工作室';
	private $typeStr_3 = '村/社区代表之家';

	function getZpAreaList(){
		$member_belong = M('member_belong');
		$list = $member_belong->order('type asc')->select();
		$list_ = array();
		foreach ($list as $k => $v) {
			$list_[$v['type']][] = array('id' => $v['id'],
				'name' => $v['name'],
				'type' => $v['type']
			);
		}
		$this->assign('list', $list_);
		$this->display();
	}

	function getZpOneArea($bid){
		$zpinfo = M('zpinfo');
		$condition['belong'] = $bid;
		$condition['arcrank'] = 0;
		$count_ = $zpinfo->where($condition)->count();
		import('ORG.Util.Page');
		$page = new Page($count_, C('PAGERSIZE'));
		$page->setConfig('theme', "%upPage%   %downPage% ");
		$list = $zpinfo->where($condition)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		// var_dump($list);
		$show = $page->show();
		$this->assign('commentlist', $list);
		$this->assign('page', $show);
		$this->assign('count', $count_);
		$zpstyle = C('ZPSTYLE');
		$this->assign('active', $zpstyle[1]['value']);
		$this->assign('keywords', $zpstyle[1]['key']);

		$this->display();
	}

//	function getOnePerson(){

//	}

	function add(){
		$type=$_GET['type'];
		$obj='typeStr_'.$type;
		if($type==3){
			$toMid=$_GET['toMid'];
		}else{
			$toMid=0;
		}
		$toMsgObjInfo=$this->$obj;
		$this->assign('toMsgObjInfo',$toMsgObjInfo);
		$this->assign('toMid',$toMid);
		$this->assign('type',$type);
		$this->display();
	}

	function reMsgTb(){
		$this->display();
	}


      /*
       * 添加消息
       */
      function addMsg(){
		  if($_POST){
			  $mid=3;
			  $type=$_POST['type'];
			  $info=$_POST['info'];
			  $toMid=$_POST['toMid'];
			  $params=array(
				  'fMid'=>$mid,
				  'msg'=>$info,
				  'type'=>$type,
				  'createTime'=>time(),
				  'updateTime'=>time(),
				  'toMid'=>$toMid,
			  );
			  $msgModel=new MsgModel();
			  $res=$msgModel->addMsg($params);
			  if($type==3){
				  $url=__ROOT__.'/index.php?g=Zp&m=msg&a=getZpPersonList&tag=';
			  }else{
				  $url=__ROOT__.'/index.php?g=Zp&m=online&a=index&tag=';
			  }
			  $tag='发言已成功！感谢您的宝贵意见！';
			  header('location:'.$url.$tag);
//			  if ($res) {
//				  $tag='发言已成功！感谢您的宝贵意见！';
//				  header('location:'.__ROOT__.'/index.php?g=Zp&m=online&a=suggestaddresult&tag='.$tag);
////                $this->success('添加成功', U('Zp/online/suggestadd'));
//			  } else {
//				  //$this->error('添加成功', U('Zp/online/suggestadd'));
//				  $tag='发言已成功！感谢您的宝贵意见！';
//				  header('location:'.__ROOT__.'/index.php?g=Zp&m=online&a=suggestaddresult&tag='.$tag);
//			  }
		  }

      }


      /*
       * 回复
       */
      function reMsg(){
		  if($_POST){
			  $id=$_POST['id'];
			  $remsg=$_POST['info'];
			  $msgModel=new MsgModel();
			  $res=$msgModel->reMsg($id,$remsg);
			  $url=__ROOT__."/index.php?g=Zp&m=msg&a=detail&id={$id}&tag=";
			  $tag='发言已成功！感谢您的宝贵意见！';
			  header('location:'.$url.$tag);
		  }
      }


      /*
       * 我的消息 (人大代表)
       */
      function toMyMsg(){
		$mid=3;
		$msgModel=new MsgModel();
		$list=$msgModel->getToMyMsg($mid);
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
		$this->assign('list',$list);
		$this->display();
     }


	function Detail(){
		//1:我提的意见 2 提给我的意见
		$type=$_REQUEST['mtype'];
		$id=$_REQUEST['id'];
		$msgModel=new MsgModel();
		$info=$msgModel->where(array('id'=>$id))->find();
		$needRemsg=0;
		if(empty($info['remsg'])){
			$needRemsg=1;
		}
		$this->assign('needRemsg',$needRemsg);
		$this->assign('info',$info);
		$this->assign('mtype',$type);
		$this->display();
	}



}