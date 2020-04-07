$(function () {

});

function goto_help() {
    $('#js-help-tab').tab('show');
}

function save_my_logo() {
    var formData = new FormData();
    formData.append("uploadfile",$("#qjt_logo_img")[0].files[0]);
    $.ajax({
        url:'save.php?action=logo',
        dataType:'json',
        type:'POST',
        async: false,
        data: formData,
        processData : false,
        contentType : false,
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
                $('#now-logo-img').attr('src','./needfile/images/front-logo.png?rad='+Math.random());
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function save_my_favicon() {
    var formData = new FormData();
    formData.append("uploadfile",$("#qjt_icon_img")[0].files[0]);
    $.ajax({
        url:'save.php?action=favicon',
        dataType:'json',
        type:'POST',
        async: false,
        data: formData,
        processData : false,
        contentType : false,
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
                $('#now-icon-img').attr('src','./needfile/images/favicon.ico?rad='+Math.random());
            }else{
                showMsg(res.msg,2);
            }

        }
    })

}

function save_my_weixin() {
    var formData = new FormData();
    formData.append("uploadfile",$("#qjt_base_weixin_img")[0].files[0]);
    $.ajax({
        url:'save.php?action=weixinimg',
        dataType:'json',
        type:'POST',
        async: false,
        data: formData,
        processData : false,
        contentType : false,
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
                $('#now-weixin-img').attr('src','./needfile/images/qianjiangmengqrcode.png?rad='+Math.random());
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function save_base_set(){
    var qjt_base_qq_switch=qjt_base_weixin_switch=qjt_base_weibo_switch=0;
    if($('#qjt_base_qq_switch').is(':checked')) qjt_base_qq_switch = 1;
    if($('#qjt_base_weixin_switch').is(':checked')) qjt_base_weixin_switch = 1;
    if($('#qjt_base_weibo_switch').is(':checked')) qjt_base_weibo_switch = 1;

    var qjt_base_qq = $('#qjt_base_qq').val();
    var qjt_base_weixin = $('#qjt_base_weixin').val();
    var qjt_base_weibo = $('#qjt_base_weibo').val();
    var qjt_carousel_img = $('#qjt_carousel_img').val();
    var qjt_footer_left = $('#qjt_footer_left').val();
    var qjt_footer_right = $('#qjt_footer_right').val();
    var qjt_footer_copyright = $('#qjt_footer_copyright').val();

    $.ajax({
        url:'save.php?action=base_set',
        data: {
            qjt_base_qq_switch:qjt_base_qq_switch,
            qjt_base_weixin_switch:qjt_base_weixin_switch,
            qjt_base_weibo_switch:qjt_base_weibo_switch,
            qjt_base_qq:qjt_base_qq,
            qjt_base_weixin:qjt_base_weixin,
            qjt_base_weibo:qjt_base_weibo,
            qjt_carousel_img:qjt_carousel_img,
            qjt_footer_left:qjt_footer_left,
            qjt_footer_right:qjt_footer_right,
            qjt_footer_copyright:qjt_footer_copyright,
            csrfToken:$('#csrfToken').val()
        },
        dataType:'json',
        type:'POST',
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function save_seo_set() {

    var qjt_seo_switch = 0;
    if($('#qjt_seo_switch').is(':checked')) {
        qjt_seo_switch = 1;
    }
    var qjt_seo_site_title = $('#qjt_seo_site_title').val();
    var qjt_seo_site_keywords = $('#qjt_seo_site_keywords').val();
    var qjt_seo_site_discribe = $('#qjt_seo_site_discribe').val();

    $.ajax({
        url:'save.php?action=seo_set',
        data: {
            qjt_seo_switch:qjt_seo_switch,
            qjt_seo_site_title:qjt_seo_site_title,
            qjt_seo_site_keywords:qjt_seo_site_keywords,
            qjt_seo_site_discribe:qjt_seo_site_discribe,
            csrfToken:$('#csrfToken').val()
        },
        dataType:'json',
        type:'POST',
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function save_adv_set() {

    var qjt_adv_switch = 0;
    if($('#qjt_adv_switch').is(':checked')) {
        qjt_adv_switch = 1;
    }
    var qjt_adv_pc_head = $('#qjt_adv_pc_head').val();
    var qjt_adv_mobile_head = $('#qjt_adv_mobile_head').val();

    $.ajax({
        url:'save.php?action=adv_set',
        data: {
            qjt_adv_switch:qjt_adv_switch,
            qjt_adv_pc_head:qjt_adv_pc_head,
            qjt_adv_mobile_head:qjt_adv_mobile_head,
            csrfToken:$('#csrfToken').val()
        },
        dataType:'json',
        type:'POST',
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function upload_file(action,fileobj){
    var formData = new FormData();
    formData.append("uploadfile",fileobj);
    $.ajax({
        url:'save.php?action='+action, /*接口域名地址*/
        dataType:'json',
        type:'POST',
        async: false,
        data: formData,
        processData : false, // 使数据不做处理
        contentType : false, // 不要设置Content-Type请求头
        success:function(res){
            console.log(res);
            if(1==res.code) {
                showMsg(res.msg,1);
            }else{
                showMsg(res.msg,2);
            }

        }
    })
}

function showMsg(str,flag) {
    if (1==flag){
        var div = '<div class="show-msg-alert msg-alert-success"></div>';
    }else{
        var div = '<div class="show-msg-alert msg-alert-error"></div>';
    }
    $('#main_container').before(div);
    $('.show-msg-alert').html(str);
    $('.show-msg-alert').show();
    setTimeout(function() {
        $('.show-msg-alert').hide();
        $('.show-msg-alert').remove();
    }, 2000)
}