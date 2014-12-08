<?php
class ActivityAction extends UserAction{
	private function loginin(){
//		if(!isset($_SESSION[C('USER_AUTH_KEY')])&&$_SESSION['administator']){
//			$this->redirect('Login/login');
//		}
//		elseif(empty($_SESSION['administator'])){
//			$this->redirect('Index/index');
//		}
//		else{
//			$username=Cookie::get('username');
//			$isadmin=$_SESSION['administator'];
//			$this->assign('username',$username);
//			$this->assign('isadmin',$isadmin);
//		}

	}

	public function index(){
		$this->loginin();
		$this->assign('ad_name','admin');
		$Activity=M('Activity');
        $dbname= $Activity->getTableName();
		if(!empty($_REQUEST['search'])){
			$map=array();
			$sqlcount="select count(id) as count from $dbname where 1=1 and name='".trim($_REQUEST['search'])."' or city= '".trim($_REQUEST['search'])."'";
			import('ORG.Util.Page');
			$count=$Activity->query($sqlcount);
			$count=$count[0]['count'];
			$page=new Page($count,C('PAGESIZE'));
			$map['search']=$_REQUEST['search'];
			foreach ($map as $k=>$v){
				//$page->parameter.="$k=".urlencode($v).'&';
				$page->parameter.="&$k=".urlencode($v);
			}
			$show=$page->show();
			$sql="select * from $dbname where 1=1 and name='".trim($_REQUEST['search'])."' or city= '".trim($_REQUEST['search'])."' order by updatetime desc,id desc limit $page->firstRow,$page->listRows";
			$list=$Activity->query($sql);
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->assign('search',$_REQUEST['search']);
		}else{
			$sqlcount="select count(id) as count from $dbname";
			import('ORG.Util.Page');
			$count=$Activity->query($sqlcount);
			$count=$count[0]['count'];
			$page=new Page($count,C('PAGESIZE'));
			/*	 $map['search']=$_REQUEST['search'];
			 foreach ($map as $k=>$v){
			 //$page->parameter.="$k=".urlencode($v).'&';
			 $page->parameter.="&$k=".urlencode($v);
			 }
			 */
			$page->setConfig('theme'," %first% %prePage% %upPage%  %linkPage% %downPage%%nextPage% %end% %nowPage%/%totalPage% 页");
			$sql="select * from $dbname where 1=1 order by updatetime desc,id desc limit $page->firstRow,$page->listRows";
			$list=$Activity->query($sql);
			$show=$page->show();
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->assign('search','');
		}
		$this->display();




	}
	public function insert(){
		$this->loginin();

		if(!empty($_POST['name'])){
			$Activity=M('Activity');
			$name=$_POST['name'];
            $keyword=$_POST['keyword'];
            $describe=$_POST['describe'];
			$content=$_POST['content'];
			$rewardkey=$_POST['rewardkey'];
            $title=$_POST['title'];
            if(!empty($_FILES['indexpic']['tmp_name'])){
                $pic[]='indexpic';
            }
			if(!empty($_FILES['texture']['tmp_name'])){
				$pic[]='logo';
			}
		   if(!empty($_FILES['texture1']['tmp_name'])){
			$pic[]='pic';
			}
            if(!empty($_FILES['texture2']['tmp_name'])){
                $pic[]='pic2';
            }
			if(count($pic)>0){
			    	$res=$this->_upload();
			    	$i=0;
			    	foreach ($pic as $v){
			    		$data[$v]=$res[$i]['name'];
			    		$i++;
			    	}
			}
			
			$data['name']=$name;
            $data['title']=$title;
            $data['keyword']=$keyword;
			$data['rewardkey']=$rewardkey;
			$data['content']=$content;
            $data['describe']=$describe;
			$result=$Activity->add($data);
			if($result){
				$this->success(L('update_success'),U('Activity/index', array(
                    'token' => $this->token,
                )));
			}
			else
			echo '已存在';
		}else{
			$this->display();
		}

	}
	public function update(){
		$this->loginin();
		if(!empty($_GET['id'])){
			$Activity=M('Activity');
			$condition['id']=$_GET['id'];
			$results=$Activity->where($condition)->select();
			$this->assign('list',$results[0]);
			$this->display();
		}
		if(!empty($_POST['id'])){
			$Activity=M('Activity');
			$name=$_POST['name'];
            $title=$_POST['title'];
            $keyword=$_POST['keyword'];
			$rewardkey=$_POST['rewardkey'];
            $describe=$_POST['describe'];
			$content=$_POST['content'];
            if(!empty($_FILES['indexpic']['tmp_name'])){
                $pic[]='indexpic';
            }
		   if(!empty($_FILES['texture']['tmp_name'])){
				$pic[]='logo';
			}
		   if(!empty($_FILES['texture1']['tmp_name'])){
			$pic[]='pic';
			}
            if(!empty($_FILES['texture2']['tmp_name'])){
                $pic[]='pic2';
            }
			if(count($pic)>0){
			    	$res=$this->_upload();
			    	$i=0;
			    	foreach ($pic as $v){
			    		$data[$v]=$res[$i]['name'];
			    		$i++;
			    	}
			}
            $data['name']=$name;
            $data['title']=$title;
            $data['keyword']=$keyword;
            $data['rewardkey']=$rewardkey;
            $data['describe']=$describe;
            $data['content']=$content;

			$condition['id']=$_POST['id'];
			$result=$Activity->where($condition)->save($data);
				//var_dump($result);
			//die();
			if($result!=false){
				//$this->assign("jumpUrl",$return_url);
				$this->success(L('update_success'),U('Activity/index', array(
                    'token' => $this->token,
                )));
			}else{
				//  echo 'false';
                $this->success(L('update_fail'),U('Activity/index', array(
                    'token' => $this->token,
                )));
			}

		}
	}


	//private fun

	protected  function _upload(){
		//  $this->display();
		$path=C('UPLOADFOLDER');
		$path=empty($path)?'uploads/':'uploads/'.$path;
		//import("ORG.Net.UploadFile");
        import("ORG.UploadFile");
		if(!empty($_FILES)){
			$upload=new UploadFile();

			$upload->__set('allowExts',explode(',','jpg,gif,png,bmp')) ;
			// 使用对上传图片进行缩略图处理
            $upload->__set('thumb',false);
			// 缩略图最大宽度
            $upload->__set('thumbMaxWidth',150);
			// 缩略图最大高度
            $upload->__set('thumbMaxHeight',150);
			// 缩略图前缀
            $upload->__set('thumbPrefix',thumb_);
            $upload->__set('thumbSuffix','');
			// 缩略图保存路径
            $upload->__set('thumbPath','./'.$path.'thumb/');
			// 缩略图文件名
			//$upload->thumbFile        =    '';
			//移除原图
            $upload->__set('thumbRemoveOrigin',false);
            $upload->__set('uploadReplace',true);
			//设置附件上传目录
            $upload->__set('savePath','./'.$path);
			//  $upload->saveRule =uniqid;

			if(!$upload->upload())
			{
				//捕获上传异常
				$this->error($upload->getErrorMsg());
			}else
			{
				//取得成功上传的文件信息
				$uploadList = $upload->getUploadFileInfo();
				foreach ($uploadList as $key => $value) {
					$file=$value['savepath'].$value['savename'];
					$aa_[]=array('name'=>$value['savename']);
				}
				//var_dump($aa_);
				//die();
				return $aa_;
				//die($uploadList[0]['savename']);
				//$file=$uploadList[0]['savepath'].$uploadList[0]['savename'];
				// $this->createMd5($file);
				// var_dump($uploadList);
				// return $uploadList[0]['savename'];
			}
		}else{
			//$this->display();
			return 'empty files';
			//die('empty files');
		}
	}


    public function del(){
        $id=$_GET['id'];
        $reserve=M('Activity');
        $condition['id']=$id;
        $res=$reserve->where($condition)->delete();
		if($res)
            $this->success(L('update_success'),U('Activity/index', array(
                'token' => $this->token,
            )));
            else
                $this->success(L('update_fail'),U('Activity/index', array(
                    'token' => $this->token,
                )));
    }


//	public function del(){
//		$this->loginin();
//		$id=$_POST['id'];
//		$reserve=M('Activity');
//		$condition['id']=$id;
//		$res=$reserve->where($condition)->delete();
//		if($res)
//		echo 1;
//		else
//		echo 0;
//	}

	
	public function getOneActivitySP(){
		if(!empty($_POST['Activityid'])){
		$sp=M('spinfo');
		$condition['Activityid']=$_POST['Activityid'];
		$list=$sp->where($condition)->select();
		echo json_encode($list);
		}
	}
	
	
	public function addspajax(){
		if(!empty($_POST['Activityid'])){
			$Activityid=$_POST['Activityid'];
			$spname=$_POST['spname'];
			$sp=M('spinfo');
			$date['Activityid']=$Activityid;
			$date['spname']=$spname;
	  $result= $sp->add($date);
		if($result){
				//$this->assign("jumpUrl",$return_url);
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$Activityid);
				$this->success(L('update_success'));
			}else{
				//  echo 'false';
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$Activityid);
				$this->error(L('update_fail'));
			}
	}
	}
	
	public function modifyspajax(){
	if(!empty($_POST['spid'])){
			$spid=$_POST['spid'];
			$spname=$_POST['spname'];
			$sp=M('spinfo');
			$condition['id']=$spid;
			$date['spname']=$spname;
	$result=$sp->where($condition)->save($date);
	if($result){
				//$this->assign("jumpUrl",$return_url);
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$Activityid);
				$this->success(L('update_success'));
			}else{
				//  echo 'false';
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$Activityid);
				$this->error(L('update_fail'));
			}
		}
	}
	
	public function delspajax(){
	if(!empty($_GET['spid'])){
			$spid=$_GET['spid'];
			$sp=M('spinfo');
			$condition['id']=$spid;
		$result= $sp->where($condition)->delete();
	if($result){
				//$this->assign("jumpUrl",$return_url);
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$spid);
				$this->success(L('update_success'));
			}else{
				//  echo 'false';
				$this->assign("jumpUrl",'__ROOT__/index.php?m=Activity&a=spinfo&id='.$spid);
				$this->error(L('update_fail'));
			}
		}
	}


}