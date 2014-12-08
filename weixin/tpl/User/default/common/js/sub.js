/**
 *
 */
$(function () {
    $("#allcheckbox").bind('click', function () {
        if ($("#allcheckbox").is(':checked')) {
            $(".checkboxa").attr("checked", true);
        }
        else {
            $(".checkboxa").attr("checked", false);
        }
});
})

function modify() {
    $(".checkboxa").each(function () {
        var result = 0;
        if ($(this).attr("checked")) {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=Index&a=modify',
                data: 'id=' + id,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })


}
function del() {
    $(".checkboxa").each(function () {
        var result = 0;
        if ($(this).attr("checked")) {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=Index&a=del',
                data: 'id=' + id,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })

}

function deluser() {
    $(".checkboxa").each(function () {
        var result = 0;
        if ($(this).attr("checked")) {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=user&a=del',
                data: 'id=' + id,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })

}
function delstore() {
    $(".checkboxa").each(function () {
        var result = 0;
        if ($(this).attr("checked")) {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=store&a=del',
                data: 'id=' + id,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })

}


function indexstore() {
    $(".radio").each(function () {
        var result = 0;
        var uid = $('#uid').val();
        if ($(this).attr("checked")) {
            var sid = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=user&a=ajaxuserindex',
                data: 'sid=' + sid + '&uid=' + uid,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            alert('帐号已添加对应门店');
        window.location.reload();
    })
}
function deluserindex() {
    $(".checkboxa").each(function () {
        var result = 0;
        var sid = $('#sid').val();
        if ($(this).attr("checked")) {
            var uid = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=user&a=ajaxdeluserindex',
                data: 'sid=' + sid + '&uid=' + uid,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })
}

function delmessage() {
    $(".checkboxa").each(function () {
        var result = 0;
        if ($(this).attr("checked")) {
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                async: false,
                url: 'index.php?m=message&a=del',
                data: 'id=' + id,
                success: function (msg) {
                    result = result + parseInt(msg);
                }
            });
        }
        if (result > 0)
            window.location.reload();
    })

}

