<?php

class WeixinAction extends Action
{
    private $token;
    private $fun;
    private $data = array();
    private $my = '小微';
    private $myapi = 'http://www.transmension.com.cn/weixinapp/';
    private $AppID = 'wxbc3c5ce6bc2d8c57';
    private $Secret = 'f8534b0cdf8cb2929a7e24723fbaa273';

    public function index()
    {
        $this->token = 'zjy202';
        $xml = file_get_contents("php://input");
//        file_put_contents("log/".date('Ymd')."alllog",date('Y-m-d H:i:s').$xml."\n",FILE_APPEND);
        $weixin = new Wechat($this->token);
        $data = $weixin->request();
        $this->data = $weixin->request();
        $this->my = C('site_my');
        list($content, $type) = $this->reply($data);
        $weixin->response($content, $type);
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

    private function getAround($lat, $lon, $raidus=500)
    {
        $PI = 3.14159265;

        $latitude = $lat;
        $longitude = $lon;

        $degree = (24901 * 1609) / 360.0;

        $raidusMile = $raidus;

        $dpmLat = 1 / $degree;
        $radiusLat = $dpmLat * $raidusMile;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;

        $mpdLng = $degree * cos($latitude * ($PI / 180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng * $raidusMile;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        return array(floatval($minLat), floatval($maxLat), floatval($minLng), floatval($maxLng));
//echo $minLat."#".$maxLat."@".$minLng."#".$maxLng;
    }

    private function reply($data)
    {
//        file_put_contents('log/test_123.log', json_encode($data)."\n", FILE_APPEND);
        if ('LOCATION' == $data['Event']) {
            $data['Content'] = array($data['FromUserName'], $data['Latitude'], $data['Longitude'], $data['Precision']);
            file_put_contents('log/test.log', json_encode($data), FILE_APPEND);
            $wxlocation = M('wxmemberlocation');
            $condtion['sOpenid'] = trim($data['FromUserName']);
            $res = $wxlocation->where($condtion)->find();
            if (!$res) {
                $locationdata = array(
                    'sOpenid' => trim($data['FromUserName']),
                    'Latitude' => trim($data['Latitude']),
                    'Longitude' => trim($data['Longitude']),
                    'Precision' => trim($data['Precision']),
                );
                $wxlocation->add($locationdata);
            } else {
                $locationdata = array(
                    'Latitude' => trim($data['Latitude']),
                    'Longitude' => trim($data['Longitude']),
                    'Precision' => trim($data['Precision']),
                );
                $res = $wxlocation->where($condtion)->save($locationdata);
            }
            return array('1111','');
        }
        if ('pic_weixin' == $data['Event']) {
            file_put_contents('log/test_pic.log', json_encode($data), FILE_APPEND);
            return array('get pic_weixin', 'text');
        }
        if ('image' == $data['MsgType']) {
            file_put_contents('log/test_image.log', json_encode($data), FILE_APPEND);
            $PicUrl = $data['PicUrl'];
            $MediaId = $data['MediaId'];
            //  $this->getImage($PicUrl,'log/'.$MediaId.'.jpg',1);
            if (empty($_SESSION['access_token'])) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->AppID&secret=$this->Secret";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                $output = curl_exec($ch);
                curl_close($ch);
                $returnarr = json_decode($output, true);
                $access_token = $returnarr['access_token'];
                if (!empty($access_token))
                    $_SESSION['access_token'] = $access_token;
            } else {
                $access_token = $_SESSION['access_token'];
            }
            $picurl_ = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$MediaId";
            $files = $this->getImage($picurl_, 'log/' . $MediaId . '.jpg', 0);
            return array('get image' . $files, 'text');
        }

        if ('CLICK' == $data['Event']) {
            if ('LOCATION_GET_X_Y' == $data['EventKey']) {
                file_put_contents('log/test_location.log', json_encode($data), FILE_APPEND);
                //TODO get near store;
                $wxlocation = M('wxmemberlocation');
                $condtion['sOpenid'] = trim($data['FromUserName']);
                $res = $wxlocation->where($condtion)->find();
               $lat= $res['Latitude'];
                $lot= $res['Longitude'];
                $neararr=$this->getAround($lat,$lot);
                  $json=array($lat,$lot,$neararr);
                $store=M('store');
                $dbname= $store->getTableName();
                $sql="select * from ".$dbname." where lat between $neararr[0] and  $neararr[1] and lon between $neararr[2] and  $neararr[3] ";
              $res= $store->query($sql);
                $arr=array();
                $str='';
                foreach($res as $value){
                   $arr[]=array('name'=>$value['name'],'email'=>$value['email'],'address'=>$value['address'],'tel'=>$value['tel']);
               $str.='name:'.$value['name'].'<br>'.'address:'.$value['address'].'<br>address:'.$value['tel'].'<br><br>';
                }
                return array('附近门店'.'<br>'.$str, 'text');
            }
            elseif('addmember'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
                $wxmember = M('wxmember');
                $condition['sOpenid'] = $sOpenid;
                // $condition['sInvCode']=$sInvCode;
                $wxmemberinfo = $wxmember->where($condition)->find();

                $condition_['keyword']='addmember';
                $diymen_class=M('diymen_class');
                $res=$diymen_class->where($condition_)->find();
                if(empty($res['indexpic']))
                    $indexpic=C('MAPPURL').'weixin/images/jx.jpg';
                else
                    $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                if($wxmemberinfo)
                return array(array(array('已是会员','已是会员',$indexpic,C('MAPPURL')."weixin/index.php?g=Home&m=WXUser&a=index&sOpenid=$sOpenid")),'news');
                else
                return array(array(array('加入会员','加入会员',$indexpic,C('MAPPURL').'weixin/index.php?g=Home&m=WXUser&a=oauth2&noreg=0')),'news');
            }
            elseif('newproductact'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
                $keyword=urlencode('新品福利');
                $activity=M('activity');
                $condition['keyword']=urldecode($keyword);
                $res=$activity->where($condition)->order('id desc')->find();
                $type=urlencode('TEST01');
               $name=$res['name'];
               $describe=$res['describe'];
               if(empty($res['indexpic']))
               $indexpic=C('MAPPURL').'weixin/images/123.jpg';
               else
               $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                return array(array(array($name,$describe,$indexpic,C('MAPPURL')."weixin/index.php?g=jixiang&m=Index&a=activity1&type=$type&keyword=$keyword&openid=".$sOpenid)),'news');
            }
            elseif('fstvact'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
                $keyword=urlencode('节日福利');
                $activity=M('activity');
                $condition['keyword']=urldecode($keyword);
                $res=$activity->where($condition)->order('id desc')->find();
                $type=urlencode('TEST02');
                $name=$res['name'];
                $describe=$res['describe'];
                if(empty($res['indexpic']))
                    $indexpic=C('MAPPURL').'weixin/images/123.jpg';
                else
                    $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                return array(array(array($name,$describe,$indexpic,C('MAPPURL')."weixin/index.php?g=jixiang&m=Index&a=activity1&type=$type&keyword=$keyword&openid=".$sOpenid)),'news');
            }
            elseif('newact'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
                $keyword=urlencode('最新活动');
                $activity=M('activity');
                $condition['keyword']=urldecode($keyword);
                $res=$activity->where($condition)->order('id desc')->find();
                $type=urlencode('TEST02');
                $name=$res['name'];
                $describe=$res['describe'];
                if(empty($res['indexpic']))
                    $indexpic=C('MAPPURL').'weixin/images/123.jpg';
                else
                    $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                return array(array(array($name,$describe,$indexpic,C('MAPPURL')."weixin/index.php?g=jixiang&m=Index&a=activity&keyword=$keyword&openid=".$sOpenid)),'news');
            }
            elseif('news'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
               $diymen_class=M('diymen_class');
                $condition['keyword']='news';
               $res=$diymen_class->where($condition)->find();
               $url=$res['url'];
               $name=$res['title'];
                if(empty($res['indexpic']))
                    $indexpic=C('MAPPURL').'weixin/images/123.jpg';
                else
                    $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                return array(array(array($name,'',$indexpic,$url)),'news');
            }
            elseif('recruit'==$data['EventKey']){
                $sOpenid= $data['FromUserName'];
                $diymen_class=M('diymen_class');
                $condition['keyword']='recruit';
                $res=$diymen_class->where($condition)->find();
                $url=$res['url'];
                $name=$res['title'];
                if(empty($res['indexpic']))
                    $indexpic=C('MAPPURL').'weixin/images/123.jpg';
                else
                    $indexpic=C('MAPPURL').'weixin/uploads/'.$res['indexpic'];
                return array(array(array($name,'',$indexpic,$url)),'news');
            }
            elseif('nearbystore'==$data['EventKey']){
                file_put_contents('log/test_nearbystore.log', json_encode($data), FILE_APPEND);
                return array('您好，请点击文字输入旁边的”+“，把您的位置发给吉祥，即可查看附近门店哦~','text');
            }
            $data['Content'] = $data['EventKey'];
        }
        if('location'===$data['MsgType']){
            $x=$data['Location_X'];
            $y=$data['Location_Y'];
           // return array('1111'.$x.$y,'text');
            import('Home.Action.MapAction');
            $mapAction = new MapAction();
            return $mapAction->nearest_jx($x, $y);
        }
    }
}

?>
