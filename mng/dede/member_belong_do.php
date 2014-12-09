<?php
/**
 * 会员管理操作
 *
 * @version        $Id: member_do.php 1 13:47 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/curd_common.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($dopost)) $dopost = '';
if(empty($fmdo)) $fmdo = '';
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? 'member_main.php' : '';

/*function add*/

if($dopost=='insert'){
    $params=array(
        'name'=>$name,
        'type'=>$type
    );
    $condition['table']='`#@__member_belong`';
 $sql=add($params,$condition);
  $rs = $dsql->ExecuteNoneQuery2($sql);
}
elseif($dopost=='update'){
    $params=array(
        'name'=>$name,
        'type'=>$type
    );
    $condition['table']='`#@__member_belong`';
    $condition['id']=$id;
    $sql=save($params,$condition);
    $rs = $dsql->ExecuteNoneQuery2($sql);
}elseif($dopost=='del'){
    $condition['table']='`#@__member_belong`';
    $sql=delete($condition);
    $rs = $dsql->ExecuteNoneQuery2($sql);
}


