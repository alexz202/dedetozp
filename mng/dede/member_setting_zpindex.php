<?php
/**
 * 会员查看
 *
 * @version        $Id: member_view.php 1 14:15 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */


require(dirname(__FILE__)."/config.php");
CheckPurview('member_Edit');
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? "member_belong_main.php" : '';
$id = preg_replace("#[^0-9]#", "", $id);
$one_info = $dsql->GetOne("select  * from #@__zpshow_index where zid='$id'");

$sql="select * from #@__member where rank in (180,200) order by belong desc";

$zp=array();
$dsql->SetQuery($sql);
$dsql->Execute();
while($row=$dsql->GetObject())
{
	$zp[$row->belong][]=array('uname'=>$row->uname,'mid'=>$row->mid);
}
$add="insert";
if($one_info){
	$zp_has=explode(',',$one_info['midList']);
	$add="update";
}

////如果这个用户是管理员帐号，必须有足够权限的用户才能操作
//if($row['matt']==10) CheckPurview('sys_User');
//
//if($row['uptime']>0 && $row['exptime']>0)
//{
//    $mhasDay = $row['exptime'] - ceil((time() - $row['uptime'])/3600/24)+1;
//} else {
//    $mhasDay = 0;
//}

include DedeInclude('templets/member_setting_zpindex.htm');
