<?php
define('WORKER',13);
define('STONE',14);
define('RENDA',16);
define('REPORTID',18);
define('INFOID',19);
define('DEALID',20);
define('DBZBID',21);

//new
define('HOMERD',35);
define('HOMERDACTIVEROOM',36);
define('HOMERDRSBS',37);

define('HOMETUNE',38);
define('HOMETUNEWATER',39);
define('HOMETUNERSBS',40);
define('RDWORKROOM',41);
define('RDWORKROOMRSBS',42);


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
    'MAPPURL'=>'http://www.lgrenda.com/weixin/',
    'KEYWORDS'=>array(
        'index'=>'南汇人大'
    ),
    'PAGERSIZE'=>4,
    'NEWSLIST'=>array(array('value'=>'工作要闻','id'=>WORKER,'nav'=>1),
        array('value'=>'他山之石','id'=>STONE,'nav'=>1),
        array('value'=>'人大概览','id'=>RENDA,'nav'=>1),

		array('value'=>'周浦人大代表之家','id'=>HOMERD,'nav'=>0),
		array('value'=>'周浦人大代表活动室','id'=>HOMERDACTIVEROOM,'nav'=>0),
		array('value'=>'周浦人大代表之家职责','id'=>HOMERDRSBS,'nav'=>0),

		array('value'=>'村人大代表之家','id'=>HOMETUNE,'nav'=>0),
		array('value'=>'社区人大代表接待室','id'=>HOMETUNEWATER,'nav'=>0),
		array('value'=>'村社区人大代表职责','id'=>HOMETUNERSBS,'nav'=>0),

		array('value'=>'人大代表工作室','id'=>RDWORKROOM,'nav'=>0),
		array('value'=>'人大代表工作室职责','id'=>RDWORKROOMRSBS,'nav'=>0),
    ),
    'ZPSTYLE'=>
	array(
        array('key'=>'代表信息','value'=>INFO),
        array('key'=>'代表提案','value'=>DEAL),
        array('key'=>'优秀代表','value'=>DBZB),
    ),
	'GAS'=>array('key'=>'代表加油站'),
);
