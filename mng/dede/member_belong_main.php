<?php
/**
 * 会员管理
 *
 * @version        $Id: member_main.php 1 10:49 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__)."/config.php");
//CheckPurview('member_List');
require_once(DEDEINC."/datalistcp.class.php");
//setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");


if(!isset($keyword)) $keyword = '';
else $keyword = trim(FilterSearch($keyword));
$sortkey=' type ';
if(!empty($keyword))
$wheres[] = " (name LIKE '%$keyword%') ";

$whereSql = join(' AND ',$wheres);
if($whereSql!='')
{
    $whereSql = ' WHERE '.$whereSql;
}
$sql  = "SELECT * FROM `#@__member_belong` $whereSql ORDER BY $sortkey DESC ";
$dlist = new DataListCP();
//$dlist->SetParameter('keyword',$keyword);
$dlist->SetTemplet(DEDEADMIN."/templets/member_belong_main.htm");
$dlist->SetSource($sql);
$dlist->display();
