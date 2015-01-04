<?php
/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 15-1-4
 * Time: 下午11:47
 */

$yeararr=array('2015','2016','2017','2018');
$quarterarr=array('0'=>'全年',
              '1'=>'第一季度',
              '2'=>'第二季度',
              '3'=>'第三季度',
              '4'=>'第四季度',
);

function getQuarter($quarter){
    $arr=array();
    if($quarter==0){
        $arr['starttime']='01-01 00:00:00';
        $arr['endtime']='12-31 23:59:59';
    }elseif($quarter==1){
        $arr['starttime']='01-01 00:00:00';
        $arr['endtime']='04-01 00:00:00';
    }
    elseif($quarter==2){
        $arr['starttime']='04-01 00:00:00';
        $arr['endtime']='07-01 00:00:00';
    }
    elseif($quarter==3){
        $arr['starttime']='07-01 00:00:00';
        $arr['endtime']='10-01 00:00:00';
    }
    elseif($quarter==4){
        $arr['starttime']='10-01 00:00:00';
        $arr['endtime']='12-31 23:59:59';
    }
    return $arr;
}