<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 14-10-4
 * Time: 下午9:33
 */

// 本文档自动生成，仅供测试运行

define('DINGCAN','dc');
define('MEISHI','ms');
define('CHAXUN','cx');
define('MENDIAN','md');
define('GETYHQ','yhq');

class WXUserAction extends Action
{
    /**
     * +----------------------------------------------------------
     * 默认操作
     * +----------------------------------------------------------
     */
    private $AppID = 'wxbc3c5ce6bc2d8c57';
    private $Secret = 'f8534b0cdf8cb2929a7e24723fbaa273';

    protected function _initialize()
    {
        define('RES', THEME_PATH . 'common');
        define('STATICS', TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
    }
    public function index()
    {
        if(isset($_POST['sOpenid'])){
            $sOpenid=$_POST['sOpenid'];
            $username=$_POST['username'];
            $phone=$_POST['phone'];
            $birthday=join('-',array($_POST['year'],$_POST['month'],$_POST['day']));
            $address=$_POST['address'];
            $sInvCode=$_POST['sInvCode'];
            $phone_o=$_POST['phone_o'];
            $wxmember = M('wxmember');
            $condition['sOpenid'] = $sOpenid;
            $data=array(
                'username'=>$username,
                'birthday'=>$birthday,
                'address'=>$address,
            );
            //todo phone remark
            if(trim($phone_o)!=trim($phone)){
                //TODO 验证
                $url="http://180.168.179.50:8081/IFC/SMS/CheckSmsRD.ashx";
                $_data=array('hl_mobile'=>$phone,'hl_rdCode'=>$sInvCode,'ostype'=>'','appVersion'=>'');
                //echo  $_data=json_encode($_data);
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
                $output= json_decode($output,true);
                if($output['status']!==1){
                    $msg= $output['statusText'];
                    echo '<div style=\"margin: 0 auto;width: 100%;text-align: center;font-size:1.6em;\";><h1 style="margin: 180px auto;width: 80%;text-align: center;">验证码不正确</h1></div>';
                    // header('Refresh:3;url='.__ROOT__.'/index.php?g=Home&m=WXUser&a=index&sOpenid='.$sOpenid);
                    echo "<script>javascript:setTimeout(\"history.back()\",3000);</script>";
                    die();
                }
                $data['phone']=$phone;
            }
           $res= $wxmember->where($condition)->save($data);
          //  if($res)
                echo '<div style=\"margin: 0 auto;width: 100%;text-align: center;font-size:1.6em;\";><h1 style="margin: 180px auto;width: 80%;text-align: center;">更新成功，自动返回</h1></div>';
            header('Refresh:3;url='.__ROOT__.'/index.php?g=Home&m=WXUser&a=index&sOpenid='.$sOpenid);

        }else{
            if(isset($_GET['sOpenid'])){
                $sOpenid=$_GET['sOpenid'];
            }else
                $sOpenid= $_SESSION['sOpenid'];
            $wxmember = M('wxmember');
            $condition['sOpenid'] = $sOpenid;
            // $condition['sInvCode']=$sInvCode;
            $wxmemberinfo = $wxmember->where($condition)->find();
           list($year,$month,$day)=explode("-",$wxmemberinfo['birthday']);
            $this->assign('year',$year);
            $this->assign('month',$month);
            $this->assign('day',$day);
            $this->assign('info',$wxmemberinfo);
            $this->display();
        }
    }

    public function getCode()
    {
//      $code=1;
        $code = $_GET['code'];
        $state = $_GET['state'];
        if (!empty($code)) {
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
                $sName=$userinfo['nickname'];
            }
            $checkout = $this->getwxmemberId($openid);
            if ($checkout) {
                $_SESSION['sOpenid'] = $openid;
                $_SESSION['sInvCode'] = '1458432479';
                $_SESSION['iMid'] = $checkout;
                  $url="index.php?g=Home&m=WXUser&a=index";
                header('location:'.$url);
            } else {
                $this->assign('openid', $openid);
                $this->assign('sName', $sName);
                $this->display();
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
                $sName=$userinfo['nickname'];
            }
            $_SESSION['sOpenid'] = $openid;
            if($type==DINGCAN){
                $url='http://180.168.179.50:8081/html5/jxwx/order.html?openid='.$openid;
            }elseif($type==MEISHI){
                //$url='http://180.168.179.50:8081/html5/jxwx/index.html?wx=promotion&openid='.$openid;
                $url='http://180.168.179.50:8081/html5/jxwx/mycoupon.html?openid='.$openid;
            }elseif($type==CHAXUN){
               // $url='http://180.168.179.50:8081/html5/jxwx/index.html?wx=memberCenter&openid='.$openid;
                $url='http://180.168.179.50:8081/html5/jxwx/myorder.html?openid='.$openid;
            }elseif($type==MENDIAN){
                $url=C('MAPPURL').'weixin/index.php?g=Store&m=store&a=index&openid='.$openid;
            }
            elseif($type==GETYHQ){
                $url=C('MAPPURL').'weixin/index.php?g=jixiang&m=Index&a=activity1&openid='.$openid;
            }
            else{
                $url=C('MAPPURL')."weixin/index.php?g=jixiang&m=Index&a=index";
            }
            header('location:'.$url);
        }
//        $this->assign('openid','111');
//        $this->display('Tpl/default/index/bindwxinfo.html');
        // header('location:'.$getCodeurl);
    }
    public function regadd()
    {
        $openid = $_POST['openid'];
        if (!empty($openid)) {
            $username=trim($_POST['username']);
            $phone = trim($_POST['phone']);
            $sName = trim($_POST['sName']);
            $birthday = trim($_POST['birthday']);
            $address = trim($_POST['address']);
            $sInvCode=trim($_POST['sInvCode']);
            $arr = array('sInvCode' => '1458432479',
                'email' => '',
                'phone' => $phone,
                'sName' => $sName,
                'birthday'=>$birthday,
                'username'=>$username,
                'address'=>$address,
                'sInvCode'=>$sInvCode
            );
            //TODO 验证
           $url="http://180.168.179.50:8081/IFC/SMS/CheckSmsRD.ashx";
            $_data=array('hl_mobile'=>$phone,'hl_rdCode'=>$sInvCode,'ostype'=>'','appVersion'=>'');
         //echo  $_data=json_encode($_data);
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
            $output= json_decode($output,true);
            if($output['status']!==1){
                      $msg= $output['statusText'];
                echo '<div style=\"margin: 0 auto;width: 100%;text-align: center;font-size:1.6em;\";><h1 style="margin: 180px auto;width: 80%;text-align: center;">验证码不正确</h1></div>';
               // header('Refresh:3;url='.__ROOT__.'/index.php?g=Home&m=WXUser&a=index&sOpenid='.$sOpenid);
                echo "<script>javascript:setTimeout(\"history.back()\",3000);</script>";
                die();
            }
            $res = $this->bindOpenIDtoinVcode($openid, $arr);
            if ($res != false) {
                $_SESSION['iMid'] = $res;
                $_SESSION['sOpenid'] = $openid;
                $_SESSION['sInvCode'] = '1458432479';
                header('location:index.php?g=Home&m=WXUser&a=index1');
            } else
                die('bind error');
        } else
            die('error');
    }

    public function reg()
    {
        $openid = $_POST['openid'];
        if (!empty($openid)) {

//            $email = trim($_POST['email']);
            $username=trim($_POST['username']);
            $phone = trim($_POST['phone']);
            $sName = trim($_POST['sName']);
           // $birthday = trim($_POST['birthday']);
            $birthday=join('-',array($_POST['year'],$_POST['month'],$_POST['day']));
            $address = trim($_POST['address']);
//            $url='http://180.168.179.50:8081/IFC/SMS/SmsSend.ashx?hl_mobile='.$phone;
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            $output = curl_exec($ch);
//            var_dump($output);
//            curl_close($ch);
           $this->assign('username',$username);
            $this->assign('openid',$openid);
            $this->assign('phone',$phone);
            $this->assign('sName',$sName);
            $this->assign('birthday',$birthday);
            $this->assign('address',$address);
        $this->display();
        } else
            die('error');
    }

    public function oauth2($noreg=0)
    {
       if($noreg==1){
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/md");
       }
       else if($noreg==2){
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/dc");
    }
       else if($noreg==3){
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/ms");
       }
       else if($noreg==4){
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/cx");
       }
       else if($noreg==5){
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode_noreg/type/yhq");
       }
       else{
           $redirect_uri = urlencode(C('MAPPURL')."weixin/index.php/Home/WXUser/getCode");
       }
        $scope = 'snsapi_userinfo';
        //$scope = "snsapi_base";
        //TODO oauth2
        $oauth2url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->AppID&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$scope#wechat_redirect";
        header('location:' . $oauth2url);
    }

    public function getcompanyone() {
        $id=$_GET['id'];
         $company=M('company');
        $condition['id']=$id;
        $res=$company->where($condition)->find();
        $this->assign('info',$res);
        $this->display();

    }

    public function getwxapi()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function getdailyworker(){
        $wxmember = M('wxmember');
        $table=$wxmember->getTableName();
        $sql="select * from  $table where date(brithday)=date(now())";
        $res=$wxmember->query($sql);
        $wsdl="http://system.tiici.com:8063/Wsforfood/WontonExchange.asmx?WSDL";
        import("@.ORG.soap_cls");
        $soapclient=new jxsoapClient($wsdl);
        if($res){
            foreach($res as $value){
                $phone=$value['phone'];
                if(!empty($phone)){
//        $ss= $soapclient-> searchFromPhoneNo($phone);
//         var_dump($ss);
                    $ss=$soapclient->getExchangeUse($phone);
               //    var_dump($ss->WontonExchangeUseResult);
                }
            }
        }


    }

    public function bindOpenID()
    {
        $this->display('Tpl/default/index/bindOpenID.html');
    }


    public function showqrcode(){
       $scene_id=$_GET['scene_id'];
       $qrcodeinfo=$this->getQRCodeinfo($scene_id);
        if(is_array($qrcodeinfo)){
         $ticket=$qrcodeinfo['ticket'];
           header("location:https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket);
        }else
            die('get qrcode error');
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


    private function getwxmemberId($sOpenid)
    {
        $wxmember = M('wxmember');
        $condition['sOpenid'] = $sOpenid;
        // $condition['sInvCode']=$sInvCode;
        $wxmemberinfo = $wxmember->where($condition)->find();
        if ($wxmemberinfo)
            return $wxmemberinfo['id'];
        else
            return false;
    }


    private function bindOpenIDtoinVcode($sOpenid, $arr)
    {
        $wxmember = M('wxmember');
        $data['sOpenid'] = $sOpenid;
        $data['sInvCode'] = $arr['sInvCode'];
        $data['phone'] = $arr['phone'];
        $data['email'] = $arr['email'];
        $data['sName'] = $arr['sName'];
        $data['birthday']=$arr['birthday'];
        $data['username']=$arr['username'];
        $data['address'] = $arr['address'];
        $data['tCreate'] = date('Y-m-d H:i:s');
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
