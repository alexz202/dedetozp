<?php
/**
 * Created by PhpStorm.
 * User: zjy202
 * Date: 2017/10/9
 * Time: 17:59
 */
class QrcodeAction extends BaseAction{

    public function make(){
        $scene_id=$_GET['scene_id'];
        $filename="qrcode/".$scene_id.'.png';
        if(!file_exists($filename)){
            import('ORG.Makeqrcode');
            $link="http://".$_SERVER['SERVER_NAME'].'/weixin/index.php/Home/WXUser/oauth2/showqrdetail/'.$scene_id;//??
            Makeqrcode::png($link,$filename,'L',8,4);
            header("location:$filename");
        }else{
            header("location:$filename");
        }
    }

}