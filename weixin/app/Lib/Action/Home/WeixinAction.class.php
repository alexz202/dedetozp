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
        if('SCAN'==$data['Event']){
            $key=$data['EventKey'];
            $ticket=$data['Ticket'];
            $username= $data['FromUserName'];
            //TODO 签到
            return array(join('|',array($username,$key,$ticket)),'Text');
        }
        elseif('subscribe'==$data['Event']){
            if(isset($data['EventKey'])&&(strpos($data['EventKey'],'qrscene')!==false)){
                $ticket=$data['Ticket'];
                $key=str_replace('qrscene_',"",$data['EventKey']);
                $username= $data['FromUserName'];
                //TODO 签到
                return array(join('|',array($username,$key,$ticket)),'Text');
            }else{
                // return array('test1','Text');
            }
        }

    }
}

?>
