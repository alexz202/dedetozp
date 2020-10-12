<?php
/**
 * Created by PhpStorm.
 * User: zjy202
 * Date: 2020/10/10
 * Time: 17:11
 */
require "Wechat.php";
$config=array(
    'appid'=>'wx7c559e82aca1dd9f',
    'appsecret'=>'df1ac88852f34baf9f05614d20153c13',
);
$menu = [
    'button'=>[
        ['name'=>'人大要闻',
            'sub_button' => [
                [
                    'name'=>'工作动态',
                    'type'=>'view',
                    'url' => 'http:///www.lgrenda.cn/weixin/index.php?g=Zp&m=news&a=getnewslist'
                ],
                [
                    'name'=>'立法联系',
                    'type'=>'view',
                    'url' => 'http://www.lgrenda.cn/weixin/index.php/Home/WXUser/oauth2/noreg/lifa'
                ],
            ]
        ],
        ['name'=>'代表风采',
            'sub_button' => [
                [
                    'name'=>'代表信息',
                    'type'=>'view',
                    'url' => 'http:///www.lgrenda.cn/weixin/index.php/Home/WXUser/oauth2/noreg/zppsinfo'
                ],
                [
                    'name'=>'优秀代表',
                    'type'=>'view',
                    'url' => 'http://www.lgrenda.cn/weixin/index.php/Home/WXUser/oauth2/noreg/dbzo'
                ],
                [
                    'name'=>'代表建议',
                    'type'=>'view',
                    'url' => 'http://www.lgrenda.cn/weixin/index.php/Home/WXUser/oauth2/noreg/deal'
                ]
            ]
        ],
        ['name'=>'人大之窗',
            'sub_button' => [
                [
                    'name'=>'人大概览',
                    'type'=>'view',
                    'url' => 'http:///www.lgrenda.cn/weixin/index.php?g=Zp&m=news&a=getnewslist&type=16'
                ],
                [
                    'name'=>'他山之石',
                    'type'=>'view',
                    'url' => 'http:///www.lgrenda.cn/weixin/index.php?g=Zp&m=news&a=getnewslist&type=14'
                ],
                [
                    'name'=>'在线互动',
                    'type'=>'view',
                    'url' => 'http://www.lgrenda.cn/weixin/index.php/Home/WXUser/oauth2/noreg/online'
                ],
            ]
        ],
    ]
];
$type=isset($_GET['type'])?intval($_GET['type']):1;
$wxClass=new \config\Wechat($config);
if($type==1){
    $result=$wxClass->getMenu();
    if($result){
        var_dump($result);
    }else{
        echo "get menu error";
    }
}elseif ($type==2){
    $result=$wxClass->createMenu($menu);
    if($result){
        echo "operation completed";
    }else{
        echo "operation failed";
    }
}
