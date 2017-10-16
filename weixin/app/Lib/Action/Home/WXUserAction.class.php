<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 14-10-4
 * Time: 下午9:33
 */

// 本文档自动生成，仅供测试运行

define('INDEX','index');
define('HOTREPO','hotrepo'); //43
define('HISTORYREPO','historyrepo'); //44
define('PARKSEINFO','parkinfo'); //47
define('TREEINFO','treeinfo');//48
define('FORESTINFO','forestinfo');//49
define('WETLANDINFO','wetlandinfo');//50
define('ANIMALPROTECT','animalprotect');//51
define('PREVENTION','prevention');//52
define('ZHIWUKEPU','zhiwukepu');//53
define('ENROLL','enroll');//54
define('ACTIVEREVIEW','activereview');//55
define('SHOWQRDETAIL','showqrdetail');//二维码文章访问
define('SERVICE','service');//二维码文章访问

class WXUserAction extends Action
{
    /**
     * +----------------------------------------------------------
     * 默认操作
     * +----------------------------------------------------------
     */
    private $AppID = '';
    private $Secret = '';

    protected function _initialize()
    {
          $this->AppID=C('APPID');
         $this->Secret=C('APPKEY');
        define('RES', THEME_PATH . 'common');
        define('STATICS', TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
    }
    public function index()
    {

    }

    /*
     * oauth 唯一接口
     */
    public function oauth2($noreg='reg',$state='')
    {
        if($noreg==='reg')
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode/");
        elseif($noreg==='suggestenter'){
            $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_suggest/");
        }
        else{
            $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/".$noreg);
        }
        $scope = 'snsapi_userinfo';
        //$scope = "snsapi_base";
        //TODO oauth2
        $oauth2url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->AppID&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";
      // file_put_contents('log/testoauth2',date('Y-m-d h:i:s').$redirect_uri."\r\n",FILE_APPEND);
        header('location:' . $oauth2url);
    }

    public function getCode()
    {
        $code = $_GET['code'];
        $state = $_GET['state'];
        if (!empty($code)) {
           $userinfo= $this->getoauthinfo();
          //  $userinfo=array('openid'=>11,'nickname'=>11);
            if($userinfo!==false){
                $openid=$userinfo['openid'];
                $sName=$userinfo['nickname'];
//                 $checkout = $this->getwxmemberId($openid);
                if (intval($userinfo['init'])==1) {
//                    $_SESSION['sOpenid'] = $openid;
//                    $_SESSION['sInvCode'] = '1458432479';
//                    $_SESSION['iMid'] = $checkout;
//                  //  $url="index.php?g=Home&m=WXUser&a=index";
                    $_SESSION['openid']=$openid;
                    $_SESSION['nickname']=$sName;
					$_SESSION['mid']=$userinfo['mid'];
                    $url=C('MAPPURL').'weixin/index.php?g=Zp&m=online&a=sign'."&sopenid=$openid";
                    header('location:'.$url);
                } else {
                    $this->assign('openid', $openid);
                    $this->assign('sName', $sName);
                    $this->display();
                }
            }
        }
//        $this->assign('openid','111');
//       $this->display();
        // header('location:'.$getCodeurl);
    }

    public function getCode_suggest()
    {
        $code = $_GET['code'];
        $state = $_GET['state'];
        if (!empty($code)) {
            $userinfo= $this->getoauthinfo();
            //  $userinfo=array('openid'=>11,'nickname'=>11);
            if($userinfo!==false){
                $openid=$userinfo['openid'];
                $sName=$userinfo['nickname'];
                $checkout = $this->getwxmemberId($openid);
                if (intval($userinfo['init'])==1) {
//                    $_SESSION['sOpenid'] = $openid;
//                    $_SESSION['sInvCode'] = '1458432479';
//                    $_SESSION['iMid'] = $checkout;
//                  //  $url="index.php?g=Home&m=WXUser&a=index";
                    $_SESSION['openid']=$openid;
                    $_SESSION['nickname']=$sName;
					$_SESSION['mid']=$userinfo['mid'];
                    $tag='人大代表请等待后台验证后查看！';
                    $url=C('MAPPURL').'weixin/index.php?g=Zp&m=Index&a=errorpage&tag='.$tag;
                    header('location:'.$url);
                } else {
                    $this->assign('openid', $openid);
                    $this->assign('sName', $sName);
                    $this->display();
                }
            }
        }
//        $this->assign('openid','111');
//       $this->display();
        // header('location:'.$getCodeurl);
    }


    public function getCode_noreg($type)
    {
        $code = $_GET['code'];
        $state = $_GET['state'];
        if (!empty($code)) {
             $userinfo= $this->getoauthinfo();
             if($userinfo!=false&&is_array($userinfo)){
                 $_SESSION['openid']=$userinfo['openid'];
                 $_SESSION['nickname']=$userinfo['nickname'];
                 $_SESSION['mid']=$userinfo['mid'];
             }
            if($type===INDEX){
                $url='index.php?g=Forest&m=Index&a=index';
            }elseif($type===HOTREPO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=43';
            }
            elseif($type===HISTORYREPO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=44';
            }
            elseif($type===PARKSEINFO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=47';
            }
            elseif($type===TREEINFO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=48';
            }
            elseif($type===FORESTINFO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=49';
            }elseif($type===WETLANDINFO){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=50';
            }elseif($type===ANIMALPROTECT){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=51';
            }elseif($type===PREVENTION){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=52';
            }elseif($type===ZHIWUKEPU){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=53';
            }elseif($type===ENROLL){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=54';
            }elseif($type===ACTIVEREVIEW){
                $url='index.php?g=Forest&m=news&a=getnewslist&type=55';
            }elseif($type===SHOWQRDETAIL){
				//state=tag_47_455
				list($tag,$typeId,$aid)=explode('_',$state);
                $url='index.php?g=Forest&m=news&a=getone&nid='.intval($aid);
            }elseif($type===SERVICE){
				list($tag,$typeId,$aid)=explode('_',$state);
                $url='index.php?g=Forest&m=news&a=getone&nid='.intval($aid);
            }
          //  file_put_contents('log/testnoreg',date('Y-m-d h:i:s').$url."\r\n",FILE_APPEND);
            header('location:'.$url);
        }
    }

	private function initUser($openid){
		$checkout = $this->getwxmemberId($openid);
		if(!$checkout){

		}
	}


//???无用
//    public function regadd()
//    {
//        $openid = $_POST['openid'];
//        if (!empty($openid)) {
//            $username=trim($_POST['username']);
//            $phone = trim($_POST['phone']);
//            $sName = trim($_POST['sName']);
////            $address = trim($_POST['workaddress']);
////            $worktel = trim($_POST['worktel']);
//            $arr = array(
//                'phone' => $phone,
//                'sName' => $sName,
//                'username'=>$username,
////                'address'=>$address,
////                'worktel'=>$worktel
//            );
//            $res = $this->bindOpenIDtoinVcode($openid, $arr);
//            if ($res != false) {
//                $_SESSION['openid']=$openid;
//                $_SESSION['nickname']=$sName;
//				$_SESSION['mid']=$res;
//                if($_POST['act']=='suggest'){
//                    $tag='人大代表请等待后台验证后查看！';
//                    $url=C('MAPPURL').'weixin/index.php?g=Zp&m=Index&a=errorpage&tag='.$tag;
//                }else{
//                    $url=C('MAPPURL').'weixin/index.php?g=Zp&m=online&a=sign'."&sopenid=$openid";
//                }
//                header('location:'.$url);
//            } else
//                die('bind error');
//        } else
//            die('error');
//    }



    public function getwxapi()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }


    public function bindOpenID()
    {
        $this->display('Tpl/default/index/bindOpenID.html');
    }


    public function showqrcode(){
       $scene_id=$_GET['scene_id'];
       $filename="qrcode/".$scene_id.'.jpg';
       if(!file_exists($filename)){
           $qrcodeinfo=$this->getQRCodeinfo($scene_id);
           if(is_array($qrcodeinfo)){
               $ticket=$qrcodeinfo['ticket'];
               $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
               $this->saveQrCode($url,$filename);
               header("location:$filename");
              // header("location:https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket);
           }else
               die('get qrcode error');
       }else{
           header("location:$filename");
       }

    }
    private function saveQrCode($url,$filename=null){
        if($filename!=null){
            $context=file_get_contents($url);
          return  file_put_contents($filename,$context);
        }
        return true;
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'zjy202';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }


    private function getoauthinfo(){
        $code = $_GET['code'];
            $state = $_GET['state'];
            $getCodeurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->AppID&secret=$this->Secret&code=$code&grant_type=authorization_code";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $getCodeurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $output = curl_exec($ch);
            curl_close($ch);
            $returnarr = json_decode($output, true);
            //   file_put_contents('log/textgetcode.txt', date('Y-m-d H:i:s') . $getCodeurl . '||' . $output . "\r\n", FILE_APPEND);
            $openid = $returnarr['openid'];
            $access_token = $returnarr['access_token'];
            if ($state === 'snsapi_userinfo') {
                //TODO GET USERINFO
                $userinfourl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $userinfourl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                $output = curl_exec($ch);
                curl_close($ch);
                $userinfo = json_decode($output, true);
                file_put_contents('log/textgetcode.txt', date('Y-m-d H:i:s') . $userinfourl . '||' . $output . "\r\n", FILE_APPEND);
				if(is_array($userinfo)){
					$openid=$userinfo['openid'];
					$sName=$userinfo['nickname'];
					$member=$this->getwxmemberId($openid);
					if(!$member){
						$arr=array(
							'sName'=>$sName
						);
						$res=$this->createUser($openid,$arr);
						if($res){
							$userinfo['mid']=$res;
							$userinfo['init']=0;
						}
						else
							return false;
					}else{
						$userinfo['mid']=$member['mid'];
						$userinfo['init']=$member['init'];
					}
				}
            }
            return $userinfo;
    }

    private function getwxmemberId($sOpenid)
    {
        $wxmember = M('member');
        $condition['sOpenId'] = $sOpenid;
//		$condition['init']=1;
        // $condition['sInvCode']=$sInvCode;
        $wxmemberinfo = $wxmember->where($condition)->find();
        if ($wxmemberinfo)
            return $wxmemberinfo;
        else
            return false;
    }


    private function bindOpenIDtoinVcode($sOpenid, $arr)
    {
        $wxmember = M('member');
		$wxmemberinfo=$this->getwxmemberId($sOpenid);
		if($wxmemberinfo){
			//存在的话更新
			$data['phone'] = $arr['phone'];
//        $data['email'] = $arr['email'];
			$data['pwd'] = md5($sOpenid);
			$data['userid'] = $arr['sName'];
			$data['uname']=$arr['username'];
//        $data['workaddress'] = $arr['address'];$userinfo
//        $data['worktel'] = $arr['worktel'];
			$data['uptime'] = time();
//			$data['jointime'] = time();
			$data['init'] = 1;
			$condtion['sOpenId']=$sOpenid;
			$res=$wxmember->where($condtion)->save($data);
			if($res){
				return $wxmemberinfo['mid'];
			}else
				return false;
		}else{
			// 如果不存在 添加$userinfo
			$data['sOpenId'] = $sOpenid;
			$data['phone'] = $arr['phone'];
//        $data['email'] = $arr['email'];
			$data['pwd'] = md5($sOpenid);
			$data['userid'] = $arr['sName'];
			$data['uname']=$arr['username'];
//        $data['workaddress'] = $arr['address'];
//        $data['worktel'] = $arr['worktel'];
			$data['uptime'] = time();
			$data['jointime'] = time();
			$data['init'] = 1;
			// $data['rank'] = time();
			$res = $wxmember->add($data);
			if ($res) {
				return $wxmember->getLastInsID();
			}
			return false;
		}
    }


	private function createUser($sOpenid,$arr){
		$wxmember = M('member');
		$data['sOpenId'] = $sOpenid;
		$data['pwd'] = md5($sOpenid);
		$data['userid'] = $arr['sName'];
		$data['uptime'] = time();
		$data['jointime'] = time();
		$data['init']=0;
		// $data['rank'] = time();
		$res = $wxmember->add($data);
		if ($res) {
			return $wxmember->getLastInsID();
		}
		return false;
	}

    /*
     * get QRCODE
     */
    private  function getQRCodeinfo($scene_id){
        $Token= $this->getToken();
        $urlQRCode = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$Token";
        $scenearr=array(
            'scene_id'=>$scene_id
        );
        $_data=array(
           'action_name'=>'QR_LIMIT_SCENE',
            'action_info'=>array(
                'scene'=>$scenearr
            ),
        );
      $jsonstr=  json_encode($_data);
       $output= $this->curlpost($urlQRCode,$jsonstr);
       $returnarr=json_decode($output,true);
        $ticket=$returnarr['ticket'];
        $url=$returnarr['url'];
      // $showqrcodeurl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
      return array('ticket'=>$ticket,'url'=>$url);
    }


    /**get token
     * @return mixed
     */
    private function getToken(){
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->AppID&secret=$this->Secret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr= json_decode($output,true);
        return $arr['access_token'];
    }


    private function curlpost($url,$_data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}

?>
