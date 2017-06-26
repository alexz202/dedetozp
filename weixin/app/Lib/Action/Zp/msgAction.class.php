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
	private $typeStr_2 = '村人大代表之家';
	private $typeStr_3 = '社区人大代表接待室';

	function getZpAreaList($type){
		$this->assign('type',$type);
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
//		$zpinfo = M('member');
//		$condition['rank'] = array('in',array(180,200));
//		$condition['belong'] =$bid;
//		$count_ = $zpinfo->where($condition)->count();
//		import('ORG.Util.Page');
//		$page = new Page($count_, C('PAGERSIZE'));
//		$page->setConfig('theme', "%upPage%   %downPage% ");
//		$list = $zpinfo->where($condition)->order('mid desc')->limit($page->firstRow . ',' . $page->listRows)->select();
//		// var_dump($list);
//		$show = $page->show();

		$zpinfo=M('zpshow_index');
		$member=M('member');
		$condition['zid']=$bid;
		$info=$zpinfo->where($condition)->find();
		if($info){
			$mids=explode(',',$info['midList']);
			$count_=count($mids);
			import('ORG.Util.Page');
			$page = new Page($count_, C('PAGERSIZE'));
			$page->setConfig('theme', "%upPage%   %downPage% ");
			$member_info=$member->where(array('mid'=>array('in',$mids)))->select();
			$list=array_slice($member_info,$page->firstRow,$page->listRows);
			$show = $page->show();
			$this->assign('commentlist', $list);
			$this->assign('page', $show);
			$this->assign('count', $count_);
		}
		$zpstyle = C('ZPSTYLE');
		$this->assign('active', $zpstyle[1]['value']);
		$this->assign('keywords', $zpstyle[1]['key']);
		$this->assign('tag',$_GET['tag']);
		$this->display();
	}

//	function getOnePerson(){

//	}

	function add(){
		$type=$_GET['type'];
		$tag=isset($_GET['tag'])?$_GET['tag']:1;
		$obj='typeStr_'.$tag;
		$toMsgObjInfo=$this->$obj;
		if($type==3){
			$toMid=$_GET['toMid'];
			$model=M('member');
			$member=$model->where(array('mid'=>$toMid))->find();
			$bid=$member['belong'];
			$uname=$member['uname'];
			$toMsgObjInfo.=':'.$uname;
		}else{
			$toMid=0;
		}
		$this->assign('toMsgObjInfo',$toMsgObjInfo);
		$this->assign('toMid',$toMid);
		$this->assign('type',$type);
		$this->display();
	}

	function reMsgTb(){
		$id=$_GET['id'];
		$pid=$_GET['pid'];
		$mid = $_SESSION['mid'];
//		$mid=4;
		$this->assign('mid',$mid);
		$this->assign('id',$id);
		$this->assign('pid',$pid);
		$this->display();
	}


      /*
       * 添加消息
       */
      function addMsg(){
		  if($_POST){
//			  $mid=3;
			  $mid = $_SESSION['mid'];
//			  $mid=3;
			  $type=$_POST['type'];
			  $info=$_POST['info'];
			  $toMid=$_POST['toMid'];
			  $title=$_POST['title'];
			  $params=array(
				  'fMid'=>$mid,
				  'msg'=>$info,
				  'type'=>$type,
				  'createTime'=>time(),
				  'updateTime'=>time(),
				  'toMid'=>$toMid,
				  'title'=>$title,
			  );

			  if($type==3){
				  $model=M('member');
				  $member=$model->where(array('mid'=>$toMid))->find();
				  $bid=$member['belong'];
				  $uname=$member['uname'];
				  if(!$member){
					  die('异常');
				  }
				  $this->assign('uname',$uname);
			  }

			  $msgModel=new MsgModel();
			  $res=$msgModel->addMsg($params);
			  if($type==3){
				  $url=__ROOT__."/index.php?g=Zp&m=msg&a=getZpOneArea&bid={$bid}&tag=";
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
			  $pid=$_POST['pid'];
			  $mid=$_POST['mid'];
			  $remsg=$_POST['info'];
			  $remsgModel=new ReMsgModel();

			  $params=array(
				 'msgId'=>$id,
				  'pId'=>$pid,
				  'mid'=>$mid,
				  'content'=>$remsg,
					'updateTime'=>time()
			  );
			  $res=$remsgModel->reMsg($params);
			  $msgModel=new MsgModel();
			  $res2=$msgModel->where(array('id'=>$id))->setInc('resCnt');
			  $url=__ROOT__."/index.php?g=Zp&m=msg&a=Detail&id={$id}&tag=";
			  $tag='发言已成功！感谢您的宝贵意见！';
			  header('location:'.$url.$tag);
		  }
      }


      /*
       * 我的消息 (人大代表)
       */
      function toMyMsg(){
		//$mid=3;
		$mid = $_SESSION['mid'];
		$msgModel=new MsgModel();
		$list=$msgModel->getToMyMsg($mid);
		$this->assign('list',$list);
		$this->display();
      }



    /*
     * 我提的消息
     */
     function MyAddMsg(){
//		$mid=3;
		$mid = $_SESSION['mid'];
//		 var_dump($mid);
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
		$remsgModel=new ReMsgModel();
		$remsg_info=$remsgModel->getsByMsgId($id);

		if($remsg_info){
			$member_list=array();
			$member_ids=array();
			foreach($remsg_info as $v){
				$member_ids[]=intval($v['mid']);
			}
			$member=M('member');
			$condition=array('mid'=>array('in',$member_ids));
			$member=$member->where($condition)->select();
			if($member){
				foreach($member as $v){
					if(intval($v['rank'])>=180)
						$member_list[$v['mid']]=array('uname'=>$v['uname']);
					else
						$member_list[$v['mid']]=array('uname'=>$v['userid']);
				}
			}
		}
		$this->assign('needRemsg',$needRemsg);
		$this->assign('info',$info);
		$this->assign('remsg_info',$remsg_info);
		$this->assign('mtype',$type);
		$this->assign('memberlist',$member_list);
		$this->display();
	}



}