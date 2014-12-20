<?php
define('WORKER',13);
define('STONE',14);
define('RENDA',16);
define('REPORTID',18);
define('INFOID',19);
define('DEALID',20);
define('DBZBID',21);

define('REPORT','report');
define('INFO','info');
define('DEAL','deal');
define('DBZB','dbzo');
/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 14-12-16
 * Time: 下午10:29
 */
return array(
    //'TMPL_FILE_DEPR'=>'_',
    'DEFAULT_THEME'=>'default',
    'MAPPURL'=>'http://www.transmension.com.cn/weixinapp/',
    'KEYWORDS'=>array(
        'index'=>'周浦人大'
    ),
    'PAGERSIZE'=>2,
    'NEWSLIST'=>array(array('value'=>'工作要闻','id'=>WORKER),
        array('value'=>'他山之石','id'=>STONE),
        array('value'=>'人大概览','id'=>RENDA),
    ),
    'ZPSTYLE'=>array(array('key'=>'履职报道','value'=>REPORT),
        array('key'=>'代表信息','value'=>INFO),
        array('key'=>'意见办理','value'=>DEAL),
        array('key'=>'代表展播','value'=>DBZB),
    ),
);