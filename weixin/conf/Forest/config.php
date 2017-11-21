<?php

define('HOTREPO_I',43); //43
define('HISTORYREPO_I',44); //44
define('PARKSEINFO_I',47); //47
define('TREEINFO_I',48);//48
define('FORESTINFO_I',49);//49
define('WETLANDINFO_I',50);//50
define('ANIMALPROTECT_I',51);//51
define('PREVENTION_I',52);//52
define('ZHIWUKEPU_I',53);//53
define('ENROLL_I',54);//54
define('ACTIVEREVIEW_I',55);//55


define('HOTREPO','hotrepo'); //43
define('HISTORYREPO','historyrepo'); //44
define('PARKSEINFO','parkinfo'); //47
define('TREEINFO','treeinfo');//48
define('FORESTINFO','forestinfo');//49
define('WETLANDINFO','wetlandinfo');//50
define('ANIMALPROTECT','animalprotect');//51
define('PREVENTION','prevention');//52
define('ZHIWUKEPU','zhiwukepu');//53
define('ENROLL','enroll');//54
define('ACTIVEREVIEW','activereview');//55

/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 14-12-16
 * Time: 下午10:29
 */
return array(
    //'TMPL_FILE_DEPR'=>'_',
    'DEFAULT_THEME'=>'default',
    'MAPPURL'=>'http://www.pudongyanghua.com/weixin/',
    'KEYWORDS'=>array(
        'index'=>'浦东绿化林业'
    ),
    'PAGERSIZE'=>4,
    'NEWSLIST'=>array(
		array('value'=>'热点专题','id'=>HOTREPO_I,'nav'=>1),
        array('value'=>'历史推送','id'=>HISTORYREPO_I,'nav'=>1),
	),
    'SERVICE'=>array(
		array('value'=>'公园绿地','id'=>PARKSEINFO_I,'nav'=>1),
        array('value'=>'古树资源','id'=>TREEINFO_I,'nav'=>1),
        array('value'=>'林业资源','id'=>FORESTINFO_I,'nav'=>1),
        array('value'=>'湿地资源','id'=>WETLANDINFO_I,'nav'=>1),
		array('value'=>'野生动物保护','id'=>ANIMALPROTECT_I,'nav'=>1),
		array('value'=>'病虫害预警','id'=>PREVENTION_I,'nav'=>1),
		array('value'=>'常见植物科普','id'=>ZHIWUKEPU_I,'nav'=>1),
    ),
	'HUDONG'=>array(
		array('value'=>'报名互动','id'=>ENROLL_I,'nav'=>1),
		array('value'=>'活动回顾','id'=>ACTIVEREVIEW_I,'nav'=>1),
	),
);
