<?php
/**
 * 内容列表
 *
 * @version        $Id: content_i_list.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

if(isset($_GET['talkid'])&&!empty($_GET['talkid'])){
    $talkid=$_GET['talkid'];
    $s_tmplets = "templets/content_suggest_list.htm";
    include(dirname(__FILE__) . "/content_msugeesttalk_list.php");
}else{
    $s_tmplets = "templets/content_suggest_list.htm";
    include(dirname(__FILE__) . "/content_msugeest_list.php");
}
