<?php

class WeixinAction extends Action
{
    private $token;
    private $fun;
    private $data = array();
    private $my = '小微';
    private $AppID = '';
    private $Secret = '';
    private $rank = 180;

    protected function _initialize()
    {
        $this->AppID = C('APPID');
        $this->Secret = C('APPKEY');
    }

//    public function test(){
////        $key = $data['EventKey'];
////        $ticket = $data['Ticket'];
////        $username = $data['FromUserName'];
//        $username='test';
//        $key=120;
//        //TODO 签到
//        $result = $this->signIN($username, $key);
//     $res=   $this->getSIGNRESULT($result,$username,$key);
//       var_dump($res);
//    }

    public function index()
    {
        $this->token = 'zprenda';
        $xml = file_get_contents("php://input");
//        file_put_contents("log/".date('Ymd')."alllog",date('Y-m-d H:i:s').$xml."\n",FILE_APPEND);
        $weixin = new Wechat($this->token);
        $data = $weixin->request();
        $this->data = $weixin->request();
        $this->my = C('site_my');
        list($content, $type) = $this->reply($data);
        $weixin->response($content, $type);
    }


    private function reply($data)
    {
//        if ('pic_weixin' == $data['Event']) {
//            file_put_contents('log/test_pic.log', json_encode($data), FILE_APPEND);
//            return array('get pic_weixin', 'text');
//        }
//        if ('image' == $data['MsgType']) {
//            file_put_contents('log/test_image.log', json_encode($data), FILE_APPEND);
//            $PicUrl = $data['PicUrl'];
//            $MediaId = $data['MediaId'];
//            //  $this->getImage($PicUrl,'log/'.$MediaId.'.jpg',1);
//            if (empty($_SESSION['access_token'])) {
//                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->AppID&secret=$this->Secret";
//                $ch = curl_init();
//                curl_setopt($ch, CURLOPT_URL, $url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//                curl_setopt($ch, CURLOPT_HEADER, 0);
//                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//                $output = curl_exec($ch);
//                curl_close($ch);
//                $returnarr = json_decode($output, true);
//                $access_token = $returnarr['access_token'];
//                if (!empty($access_token))
//                    $_SESSION['access_token'] = $access_token;
//            } else {
//                $access_token = $_SESSION['access_token'];
//            }
//            $picurl_ = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$MediaId";
//            $files = $this->getImage($picurl_, 'log/' . $MediaId . '.jpg', 0);
//            return array('get image' . $files, 'text');
//        }
        //CLICK
//        if ('CLICK' == $data['Event']) {
//
//        }


        if ('SCAN' == $data['Event']) {
            $key = $data['EventKey'];
            $ticket = $data['Ticket'];
            $username = $data['FromUserName'];
            //TODO 签到
            $result = $this->signIN($username, $key);
//            return array($result,'text');
         return    $this->getSIGNRESULT($result,$username,$key);
        } elseif ('subscribe' == $data['Event']) {
            if (isset($data['EventKey']) && (strpos($data['EventKey'], 'qrscene') !== false)) {
                $ticket = $data['Ticket'];
                $key = str_replace('qrscene_', "", $data['EventKey']);
                $username = $data['FromUserName'];
                //TODO 签到
                $result = $this->signIN($username, $key);
          return   $this->getSIGNRESULT($result,$username,$key);
            } else {
                // return array('test1','Text');
            }
        }


    }


    private function signIN($sOpenid, $meetid)
    {
        //TODO 判断用户
        //TODO 是否签到过
        $ismember = $this->getmemberinfo($sOpenid);
        if ($ismember === true) {
            $isSign = $this->IsignIn($sOpenid, $meetid);
            if ($isSign === true) {
                $this->addsignIN($sOpenid, $meetid);
                return 0;
            } else {
                return 1;
            }
        } else {
             $exsit= $this->getmemberexiset($sOpenid);
            $isSign = $this->IsignIn($sOpenid, $meetid);
            if(!$exsit){
                if ($isSign === true) {
                    $this->addsignIN($sOpenid, $meetid, 1);
                    return 2;
                } else {
                    return 2;
                }

            }else{
                if ($isSign === true) {
                    $this->addsignIN($sOpenid, $meetid, 1);
                    return 3;
                } else {
                    return 3;
                }
            }
        }
    }

    /**
     * 签到结果返回
     * @param $result
     * @param $sOpenid
     * @param $meetid
     */
    private function getSIGNRESULT($result, $sOpenid, $meetid)
    {
        $url=C('MAPPURL').'weixin/index.php?g=Zp&m=online&a=sign';
        $urlreg=C('MAPPURL').'weixin/index.php/Home/WXUser/oauth2/noreg/reg';
        $img=C('MAPPURL').'weixin/tpl/Zp/default/common/images/logo.png';
        $meetinfo=$this->getmeet($meetid);
        if(!empty($meetinfo['litpic']))$img="http://118.126.11.231/".$meetinfo['litpic'];
        if ($result == 0) {
            //签到成功
           $array=array('签到成功','',$img,$url."&sopenid=$sOpenid&meetid=$meetid");

        } elseif ($result == 1) {
            //已签到
            $array=array('已签到','',$img,$url."&sopenid=$sOpenid&meetid=$meetid");

        } elseif ($result == 2) {
            //预签到成功
            $array=array('请先登记代表信息','',$img,$urlreg);

        } elseif ($result == 3) {
            //预签到过
            $array=array('已签到等待验证','',$img,$url."&sopenid=$sOpenid&meetid=$meetid");
        }
        return array(array($array),'news');
    }


    private function getmemberinfo($sOpenid)
    {
        $user = M('member');
        $condition['rank'] = array('egt',$this->rank);
        $condition['sOpenId'] = $sOpenid;
        $res = $user->where($condition)->find();
        if ($res)
            return true;
        else
            return false;
    }


    private function getmemberexiset($sOpenid)
    {
        $user = M('member');
      //  $condition['rank'] = array('egt',$this->rank);
        $condition['sOpenId'] = $sOpenid;
        $res = $user->where($condition)->find();
        if ($res)
            return true;
        else
            return false;
    }

    private function getmeet($meetid){
        $meeting = M('addoninfos');
        $condition['aid']=$meetid;
        $res=$meeting->where($condition)->find();
        return $res;
    }

    private function IsignIn($sOpenid, $meetid)
    {
        $sign = M('member_sign');
        $condition['sOpenId'] = $sOpenid;
        $condition['infosId'] = $meetid;
        $res = $sign->where($condition)->select();
        if ($res) {
            return false;
        } else
            return true;
    }

    private function addsignIN($sOpenid, $meetid, $result = 0)
    {
        $sign = M('member_sign');
        $data['sOpenId'] = $sOpenid;
        $data['infosId'] = $meetid;
        $data['result'] = $result;
        return $sign->add($data);
    }

    private function getImage($url, $filename = '', $type = 0)
    {
        if ($url == '') {
            return false;
        }
        if ($filename == '') {
            $ext = strrchr($url, '.');
            if ($ext != '.gif' && $ext != '.jpg') {
                return false;
            }
            $filename = time() . $ext;
        }
        //文件保存路径
        if ($type) {
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        $size = strlen($img);
        //文件大小
        $fp2 = @fopen($filename, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        return md5_file($filename);
    }
}

?>
