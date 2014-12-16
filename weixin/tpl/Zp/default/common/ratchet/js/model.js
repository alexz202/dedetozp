/**
 * Created by alexzhu on 14-12-15.
 */

$.fn.extend({
    Mask : function(width, height, callback) {
        var divWidth = width;
        var divHeight = height;
        var isSCROLL = 1;// 0 div not move if scroll
        var _scrollHeight = $(document).scrollTop();
        var arrWindows = $(this).getWido();
        var left = ((arrWindows[0] - divWidth) / 2);
        var top = ((arrWindows[1] - divHeight) / 2);
        $(this).showMask();
        $(this).showDiv(left,top);
//        eval(obj + '(' + left + ',' + top + ',' + _scrollHeight + ','
//            + callback + ')');
        if (isSCROLL == 1) {
            $(window).scroll(function() {
                var _scrollHeight = $(document).scrollTop();
                var arrWindows = $(this).getWido();
                var left = ((arrWindows[0] - divWidth) / 2);
                var top = ((arrWindows[1] - divHeight) / 2);
                var _scrollHeight = $(document).scrollTop();
                $("#dl").css({
                    'top' : top + _scrollHeight
                });
            });
        }
    },
    MaskClose : function() {
        $('#mask').remove();
        $('#dl').remove();
    },
    getWido : function() {
        var arr = [ $(window).width(), $(window).height() ];
        return arr;
    },
    showMask : function() {
        var arrWindows = $(this).getWido();
        var width = arrWindows[0];
        var height = $('body').height();
        var Mask = "<div id='mask'></div>";
        $('body').append(Mask);
        $('#mask').css({
            'top' : 0,
            'left' : 0,
            'width' : width,
            'height' : height,
            'opacity' : '0.4',
            'background' : '#000000',
            'position' : 'absolute'
        });
    },
    showDiv:function(left,top,scrollHeight){
        var div = "<div id='modeldiv'>1111111</div>";
        $('body').append(div);
        $('#modeldiv').css({
            'top' : top,
            'left' : left,
            'width' : this.divWidth,
            'height' : this.divHeight,
            'position' : 'absolute'
        });
    }

});