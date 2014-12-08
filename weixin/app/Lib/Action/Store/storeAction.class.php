<?php
// 本文档自动生成，仅供测试运行
class storeAction extends Action
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    protected function _initialize()
    {
        define('RES', THEME_PATH . 'common');
        define('STATICS', TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
    }

    protected $size=6;
    public function index() {
        $page=1;
        $start=($page-1)*$this->size;
        $end=$this->size;
        if(!empty($_SESSION['sOpenid'])){
            $wxlocation = M('wxmemberlocation');
            $condtion['sOpenid'] = trim($_SESSION['sOpenid']);
            $res = $wxlocation->where($condtion)->find();
            $lat= $res['Latitude'];
            $lot= $res['Longitude'];
            $neararr=$this->getAround($lat,$lot);
            $json=array($lat,$lot,$neararr);
            $store=M('store');
            $dbname= $store->getTableName();
            $sql="select * from ".$dbname." where lat between $neararr[0] and  $neararr[1] and lon between $neararr[2] and  $neararr[3] limit $start,$end";
            $res= $store->query($sql);
        }else{
            $res=$this->getAllstore($start,$end);
        }
        $this->assign('list',$res);
        $this->assign('page',$page);
        $this->display('tpl/Store/newshow.html');
    }
    public function one() {
        $iAid=$_GET['id'];
       $res= $this->getAone($iAid);
        $this->assign('list',$res[0]);
        if($screenshot=$this->getScreenshot($iAid)){
            $this->assign('screenshot',$screenshot);
        }
        $this->display('tpl/Store/newshow_txt.html');
    }
    public function meet() {
        $this->display('tpl/Store/meet.html');
    }

    private function getAone($iAid){
        $store=M('store');
        $condition['id']=$iAid;
       return  $res=$store->where($condition)->select();
    }
    private function getScreenshot($iAid){
        $screenshot=M('screenshot');
        $condition['iAid']=$iAid;
      return   $screenshot->where($condition)->select();

    }

    public function AjaxGetAllstore(){
       $page=$_GET['page'];
        $page=isset($_GET['page'])?$_GET['page']:2;
        if(!empty($_SESSION['sOpenid'])){
            $wxlocation = M('wxmemberlocation');
            $condtion['sOpenid'] = trim($_SESSION['sOpenid']);
            $res = $wxlocation->where($condtion)->find();
            $lat= $res['Latitude'];
            $lot= $res['Longitude'];
            $neararr=$this->getAround($lat,$lot);
            $json=array($lat,$lot,$neararr);
            $store=M('store');
            $dbname= $store->getTableName();
            $sql="select count(*) from ".$dbname." where lat between $neararr[0] and  $neararr[1] and lon between $neararr[2] and  $neararr[3]";
            $count=$store->query($sql);
        }else
        $count=$this->getAllstorecount();
        $maxpage=ceil($count/$this->size);
        if($page<=$maxpage){
            $start=($page-1)*$this->size;
            $end=$this->size;
            $res=$this->getAllstore($start,$end);
            $this->assign('list',$res);
            $this->assign('page',$page);
            $this->display('tpl/Store/newshow_ajax.html');
        }else{
            die('error');
        }

    }

    private  function getAllstore($start,$end){
        $store=M('store');
        $res=$store->limit("$start,$end")->order('id desc')->select();
        return $res;
    }

    private  function getAllstorecount(){
        $store=M('store');
        return $count=$store->count();
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


}
?>