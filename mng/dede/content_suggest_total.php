<?php

$s_tmplets = "templets/content_suggest_total.htm";
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
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/typelink.class.php');
require_once(DEDEINC.'/datalistcp.class.php');
require_once(DEDEADMIN.'/inc/inc_list_functions.php');

$cid = isset($cid) ? intval($cid) : 0;
if($cid==0)$cid=SUGGESTTYPEID;
$channelid = isset($channelid) ? intval($channelid) : 0;
$mid = isset($mid) ? intval($mid) : 0;

if(!isset($keyword)) $keyword = '';
if(!isset($flag)) $flag = '';
if(!isset($arcrank)) $arcrank = '';
if(!isset($dopost)) $dopost = '';

//检查权限许可，总权限
CheckPurview('a_List,a_AccList,a_MyList');

//栏目浏览许可
$userCatalogSql = '';
if(TestPurview('a_List'))
{
    ;
}
else if(TestPurview('a_AccList'))
{
    if($cid==0 && $cfg_admin_channel == 'array')
    {
        $admin_catalog = join(',', $admin_catalogs);
        $userCatalogSql = " arc.typeid IN($admin_catalog) ";
    }
    else
    {
        CheckCatalog($cid, '你无权浏览非指定栏目的内容！');
    }
    if(TestPurview('a_MyList')) $mid =  $cuserLogin->getUserID();

}

$adminid = $cuserLogin->getUserID();
$maintable = '#@__suggest';
setcookie('ENV_GOBACK_URL', $dedeNowurl, time()+3600, '/');
$tl = new TypeLink($cid);
//var_dump($tl);

$query="select * from tp_arctype where reid=23";
$dsql->SetQuery($query);
$dsql->Execute();
$list=array();
while($trow = $dsql->GetObject()){
    $res=   $dsql->GetOne("SELECT count(id) as count1 FROM `#@__suggest` where typeid=$trow->id ");
    $list[]=array('count'=>$res['count1'],
        'name'=>$trow->typename,
        'id'=>$trow->id,
    );

}
//var_dump($list);
include DedeInclude($s_tmplets);
