/**
 * Created by alexzhu on 15-1-7.
 */
var imgUrl = "http://www.baidu.com/img/bdlogo.gif";



var lineLink = "weixin://profile/gh_647e3f3936a6";



var descContent = '周浦人大';



var shareTitle = '周浦人大';



var appid = 'wxab27ae668e90cb25';



function shareFriend() {



    WeixinJSBridge.invoke('sendAppMessage',{



        "appid": appid,



        "img_url": imgUrl,



        "img_width": "200",



        "img_height": "200",



        "link": lineLink,



        "desc": descContent,



        "title": shareTitle



    }, function(res) {



    })



}



function shareTimeline() {



    WeixinJSBridge.invoke('shareTimeline',{



        "img_url": imgUrl,



        "img_width": "200",



        "img_height": "200",



        "link": lineLink,



        "desc": descContent,



        "title": shareTitle



    }, function(res) {



    });



}



function shareWeibo() {



    WeixinJSBridge.invoke('shareWeibo',{



        "content": descContent,



        "url": lineLink



    }, function(res) {



    });



}

function onBridgeReady(){
  //  WeixinJSBridge.call('hideToolbar');
    WeixinJSBridge.on('menu:share:appmessage', function(argv){
        shareFriend();
    });

    // 分享到朋友圈


    WeixinJSBridge.on('menu:share:timeline', function(argv){
        shareTimeline();

    });

    // 分享到微博


    WeixinJSBridge.on('menu:share:weibo', function(argv){
        shareWeibo();
    });


}

// 当微信内置浏览器完成内部初始化后会触发WeixinJSBridgeReady事件。

if (typeof WeixinJSBridge == "undefined"){
    if( document.addEventListener ){
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    }else if (document.attachEvent){
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }
}else{
    onBridgeReady();
}
