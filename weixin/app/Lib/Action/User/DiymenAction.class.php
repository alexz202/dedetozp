<?php
class DiymenAction extends UserAction{
	//自定义菜单配置
	public function index(){
		$data=M('Diymen_set')->where(array('token'=>$_SESSION['token']))->find();
		if(IS_POST){
			$_POST['token']=$_SESSION['token'];
			if($data==false){
				$this->all_insert('Diymen_set');
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Diymen_set');
			}
		}else{
			$this->assign('diymen',$data);
			$class=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>0))->order('sort desc')->select();//dump($class);
			foreach($class as $key=>$vo){
				$c=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>$vo['id']))->order('sort desc')->select();
				$class[$key]['class']=$c;
			}
			//dump($class);
			$this->assign('class',$class);
			$this->display();
		}
	}


	public function  class_add(){
		if(IS_POST){
            $_POST['token'] = session('token');
            if(!empty($_FILES['indexpic']['tmp_name'])){
                $pic[]='indexpic';
            }
            if(count($pic)>0){
                $res=$this->_upload();
                $i=0;
                foreach ($pic as $v){
                    $data[$v]=$res[$i]['name'];
                    $_POST[$v]=$res[$i]['name'];
                    $i++;
                }
            }

			$this->all_insert('Diymen_class','/class_add');
		}else{
			$class=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>0))->order('sort desc')->select();
			$this->assign('class',$class);
			$this->display();
		}
	}
	public function  class_del(){
		$class=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>$this->_get('id')))->order('sort desc')->find();
		//echo M('Diymen_class')->getLastSql();exit;
		if($class==false){
			$back=M('Diymen_class')->where(array('token'=>session('token'),'id'=>$this->_get('id')))->delete();
			if($back==true){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->error('请删除该分类下的子分类');
		}


	}
	public function  class_edit(){
		if(IS_POST){
			$_POST['id']=$this->_get('id');
            if(!empty($_FILES['indexpic']['tmp_name'])){
                $pic[]='indexpic';
            }
            if(count($pic)>0){
                $res=$this->_upload();
                $i=0;
                foreach ($pic as $v){
                   $data[$v]=$res[$i]['name'];
                    $_POST[$v]=$res[$i]['name'];
                    $i++;
                }
            }
			$this->all_save('Diymen_class','/class_edit?id='.$this->_get('id'));
		}else{
			$data=M('Diymen_class')->where(array('token'=>session('token'),'id'=>$this->_get('id')))->find();
			if($data==false){
				$this->error('您所操作的数据对象不存在！');
			}else{
				$class=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>0))->order('sort desc')->select();//dump($class);
				$this->assign('class',$class);
				$this->assign('show',$data);
			}
			$this->display();
		}
	}
	public function  class_send(){
		if(IS_GET){
			$api=M('Diymen_set')->where(array('token'=>session('token')))->find();
			if($api['appid']==false||$api['appsecret']==false){$this->error('必须先填写【AppId】【 AppSecret】');exit;}
			//dump($api);
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$api['appid'].'&secret='.$api['appsecret'];
			$json=json_decode(file_get_contents($url_get));
			if($json->errcode=="40001"){$this->error('【AppId】或者【 AppSecret】验证错误');exit;}
			$data = '{"button":[';

			$class=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>0))->limit(3)->order('sort desc')->select();//dump($class);
			$kcount=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>0))->limit(3)->order('sort desc')->count();
			$k=1;
			foreach($class as $key=>$vo){
				//主菜单
				$data.='{"name":"'.$vo['title'].'",';
				$c=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>$vo['id']))->limit(5)->order('sort desc')->select();
				//dump($c);
				$count=M('Diymen_class')->where(array('token'=>session('token'),'pid'=>$vo['id']))->limit(5)->order('sort desc')->count();
				//子菜单
				if($c!=false){
					$data.='"sub_button":[';
				}else{
					if ($vo['url']) {
						$data.='"type":"view","url":"'.$vo['url'].'"';
					}else{
						$data.='"type":"click","key":"'.$vo['keyword'].'"';
					}
				}
				$i=1;
				foreach($c as $voo){
					$voo['url']=str_replace(array('&amp;'),array('&'),$voo['url']);
					if($i==$count){
						if($voo['url']&&!$voo['keyword']){
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"}';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"}';
						}
					}else{
						if($voo['url']&&!$voo['keyword']){
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"},';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"},';
						}
					}
					$i++;
				}
				if($c!=false){
					$data.=']';
				}

				if($k==$kcount){
					$data.='}';
				}else{
					$data.='},';
				}
				$k++;
			}
			$data.=']}';
            //die();
		//	file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$json->access_token);
			$url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$json->access_token;
			$result=json_decode($this->api_notice_increment($url,$data));
			if($result->errcode!="0"){
				$this->error('操作失败');
				echo($result->errmsg);
			}else{
				$this->success('操作成功');
			}
			exit;
		}else{
			$this->error('非法操作');
		}
	}
	function api_notice_increment($url, $data){
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
////            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
////            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
//        $output = curl_exec($ch);
//        curl_close($ch);
//        $output= json_decode($output,true);
//var_dump($output);
		$urlarr = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		 $newurl = $transports . $urlarr['host'];
       // https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
		$fp = @fsockopen($newurl, $urlarr['port'], $errno, $errstr, 60);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			fputs($fp, "POST ".$urlarr["path"]."?".$urlarr["query"]." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($data)."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data . "\r\n\r\n");
			while(!feof($fp)) {
				$receive[] = @fgets($fp, 1024);
			}
			fclose($fp);
			$result = $receive[count($receive) - 1];
			return $result;
		}
	}


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

}
	?>