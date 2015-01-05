<?php

$s_tmplets = "templets/content_meetall_total.htm";
/**
 * 内容列表
 * content_s_list.php、content_i_list.php、content_select_list.php
 * 均使用本文件作为实际处理代码，只是使用的模板不同，如有相关变动，只需改本文件及相关模板即可
 *
 * @version        $Id: content_list.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__) . '/config.php');
require_once(DEDEINC . '/typelink.class.php');
require_once(DEDEINC . '/datalistcp.class.php');
require_once(DEDEADMIN . '/inc/inc_list_functions.php');
require_once('makeexcel.php');
require_once('commonselect.php');

//get year quarter
if (!isset($year)) $year = date('Y');
if (!isset($quarter)) $quarter = 0;

$arr = getQuarter($quarter);
$startime = $year . '-' . $arr['starttime'];
$endtime = $year . '-' . $arr['endtime'];
$startime = strtotime($startime);
$endtime = strtotime($endtime);


$adminid = $cuserLogin->getUserID();
//to get all meet
$query = "select id from `#@__meeting`";
$dsql->SetQuery($query);
$meetinglist=array();
while ($trow = $dsql->GetObject()) {
    $meetinglist[]=array('meetid'=>$trow->id);
}


foreach($meetinglist as $value){
    $meetid=$value['meetid'];
//get one meet total info

    $query = "select * from `#@__member_belong` order by typeid asc";
    $dsql->SetQuery($query);
    $dsql->Execute();
    $list = array();
    $list_attend = array();
    $allcount = 0;
    while ($trow = $dsql->GetObject()) {
        $belongid=$trow->id;
        $belongname=$trow->name;
        $sql = "SELECT count(m.mid) as count from `#@__member_sign` as ms join `#@__member` as m on ms.sOpenId=m.sOpenId  where m.belong=$belongid and ms.infosId=$meetid";
        $res=  $dsql->GetOne($query);
        $count= $res['count'];
        //get belong all member
        $sql2="select count(m.mid) as count from `#@__member` where belong=$belongid";
        $res2= $dsql->GetOne($query);
        $allcount=$res2['count'];
        $list[$belongid]['allcount']=$allcount;
        $list[$belongid]['count']=$count;
        $list[$belongid]['name']=$belongname;
        $list_attend["$belongid"]=sprintf("%0.2f",($count/$allcount)*100);
    }

    $list_attend[$meetid][]=array_flip(rsort(array_flip($list_attend)));

}


//var_dump($list);
include DedeInclude($s_tmplets);
