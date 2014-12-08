<?php
/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 14-10-24
 * Time: 下午10:25
 */
class IndexAction extends Action{

    protected function _initialize()
    {
        define('RES', THEME_PATH . 'common');
        define('STATICS', TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
    }

    public function index(){
        $this->display();
    }
    public function pdsys(){
        $this->display();
    }
    public function activity(){
        if($_POST){
            $openid=$_POST['openid'];
            $keyword=urlencode($_POST['keyword']);
            $txtTitle=$_POST['txtTitle'];
            $txt_RealName=$_POST['txt_RealName'];
            $sex=$_POST['sex'];
            $txt_Phone=$_POST['txt_Phone'];
            $txt_Number=$_POST['txt_Number'];
            $txt_Address=$_POST['txt_Address'];
            $txt_Content=$_POST['txt_Content'];
            $Wxuser=M('actuserinfo');
            $data['txtTitle']=$txtTitle;
            $data['txt_RealName']=$txt_RealName;
            $data['sex']=$sex;
            $data['txt_Phone']=$txt_Phone;
            $data['txt_Number']=$txt_Number;
            $data['txt_Address']=$txt_Address;
            $data['txt_Content']=$txt_Content;
            $data['openid']=$openid;
          $res=$Wxuser->add($data);
            if($res){
                echo '提交成功，自动返回';
                header('Refresh:5;url='.__ROOT__.'/index.php?g=jixiang&m=Index&a=activity&openid='.$openid.'&keyword='.$keyword);
            }

        }else{
            $openid=$_GET['openid'];
            $keyword=urldecode($_GET['keyword']);
            $activity=M('activity');
            $condition['keyword']=$keyword;
            $res=$activity->where($condition)->order('id desc')->find();
            if($res){
                $type=$res['rewardkey'];
                $this->assign('info',$res);
            }
            $keyword=urldecode($_GET['keyword']);
            $this->assign('keywords',$res['title']);
            $this->assign('keyword',$keyword);
            $this->assign('openid',$openid);
            $this->display();
        }
    }
    public function jianyi(){
        if($_POST){
            $SelProvince=$_POST['SelProvince'];
            $txt_RealName=$_POST['txt_RealName'];
            $SelCity=$_POST['SelCity'];
            $txt_Phone=$_POST['txt_Phone'];
            $SelArea=$_POST['SelArea'];
            $txt_Content=$_POST['txt_Content'];
            $txt_store=$_POST['txt_store'];
            if($SelArea=='请选择')
                $SelArea='';
            $Wxuser=M('tousu');
            $data['txt_area']=$SelProvince.$SelCity.$SelArea;
            $data['txt_RealName']=$txt_RealName;
            $data['txt_Phone']=$txt_Phone;
            $data['txt_store']=$txt_store;
            $data['txt_Content']=$txt_Content;
            $res=$Wxuser->add($data);
            if($res){
                echo '<div style=\"margin: 0 auto;width: 100%;text-align: center;font-size:1.6em;\";><h1 style="margin: 180px auto;width: 80%;text-align: center;">提交成功，自动返回</h1></div>';
                header('Refresh:5;url='.__ROOT__.'/index.php?g=jixiang&m=Index&a=jianyi');
            }

        }else{
            $this->display();
        }
    }
    public function activity1(){
        $openid=$_GET['openid'];
        $type=$_GET['type'];
        $keyword=urldecode($_GET['keyword']);
        $activity=M('activity');
        $condition['keyword']=$keyword;
        $res=$activity->where($condition)->order('id desc')->find();
        if($res){
            $type=$res['rewardkey'];
            $this->assign('info',$res);
        }
        $this->assign('keywords',$res['title']);
        $this->assign('openid',$openid);
        $this->assign('type',$type);
        $this->display();
    }
      public function getquan(){
//           $openid=$_POST['openid'];
          $openid=$_POST['openid'];
         $type=$_POST['type'];
          $wxmember = M('wxmember');
          $condition['sOpenid']=$openid;
         $res= $wxmember->where($condition)->find();
          if($res){
              $phone=$res['phone'];
              import("@.ORG.soap_cls");
              $wsdl="http://system.tiici.com:8063/Wsforfood/WontonExchange.asmx?WSDL";
              $soapclient=new jxsoapClient($wsdl);
//         $ss= $soapclient-> searchFromPhoneNo($phone);
//          var_dump($ss);
              $ss=$soapclient->getExchangeUse($phone,$type);
              if($ss->WontonExchangeUseResult)
                  echo 1;
              else
                  echo 0;
          }else{
              echo 3;
          }
      }
}