<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('qianjiangteam_51wzt')) {$zbp->ShowError(48);die();}

//echo '<pre>';print_r($_GET);die;

if($_GET['action'] == 'logo'){
    global $zbp;
    $filename = $zbp->usersdir . 'theme/qianjiangteam_51wzt/needfile/images/front-logo.png';
    if(empty($_FILES)) {
        echo json_encode(array('code'=>'0','msg'=>'请选择文件!','url'=>$filename));die;
    }
    foreach ($_FILES as $key => $value) {
        if(!strpos($key, "_php")){
            if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                $tmp_name = $_FILES[$key]['tmp_name'];
                $name = $_FILES[$key]['name'];
                @move_uploaded_file($_FILES[$key]['tmp_name'], $filename);
            }
        }
    }
    echo json_encode(array('code'=>'1','msg'=>'上传成功!','url'=>$filename));die;
}

if($_GET['action'] == 'favicon'){
    global $zbp;
    $filename = $zbp->usersdir . 'theme/qianjiangteam_51wzt/needfile/images/favicon.ico';
    //echo '<pre>';print_r($_FILES);die;
    if(empty($_FILES)) {
        echo json_encode(array('code'=>'0','msg'=>'请选择文件!','url'=>$filename));die;
    }
    foreach ($_FILES as $key => $value) {
        if(!strpos($key, "_php")){
            if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                $tmp_name = $_FILES[$key]['tmp_name'];
                $name = $_FILES[$key]['name'];
                @move_uploaded_file($_FILES[$key]['tmp_name'], $filename);
            }
        }
    }
    echo json_encode(array('code'=>'1','msg'=>'上传成功!','url'=>$filename));die;
}

if($_GET['action'] == 'weixinimg'){
    global $zbp;
    $filename = $zbp->usersdir . 'theme/qianjiangteam_51wzt/needfile/images/qianjiangmengqrcode.png';
    //echo '<pre>';print_r($_FILES);die;
    if(empty($_FILES)) {
        echo json_encode(array('code'=>'0','msg'=>'请选择文件!','url'=>$filename));die;
    }
    foreach ($_FILES as $key => $value) {
        if(!strpos($key, "_php")){
            if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                $tmp_name = $_FILES[$key]['tmp_name'];
                $name = $_FILES[$key]['name'];
                @move_uploaded_file($_FILES[$key]['tmp_name'], $filename);
            }
        }
    }
    echo json_encode(array('code'=>'1','msg'=>'上传成功!','url'=>$filename));die;
}

if($_GET['action'] == 'base_set'){
    global $zbp;
    if (function_exists('CheckIsRefererValid')) {
        $flag = CheckCSRFTokenValid();
        if ($flag && $zbp->option['ZC_ADDITIONAL_SECURITY']) {
            $flag = CheckHTTPRefererValid();
        }
        if (!$flag) {
            echo json_encode(array('code'=>'0','msg'=>'来源错误!'));die;
        }
    }; 
    $zbp->Config('qianjiangteam_51wzt')->qjt_base_qq_switch = intval($_POST['qjt_base_qq_switch']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_base_weixin_switch = intval($_POST['qjt_base_weixin_switch']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo_switch = intval($_POST['qjt_base_weibo_switch']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_base_qq = trim($_POST['qjt_base_qq']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo = trim($_POST['qjt_base_weibo']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_carousel_img = trim($_POST['qjt_carousel_img']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_footer_left = trim($_POST['qjt_footer_left']);      		   	
    $zbp->Config('qianjiangteam_51wzt')->qjt_footer_right = trim($_POST['qjt_footer_right']);      		   	
    $zbp->Config('qianjiangteam_51wzt')->qjt_footer_copyright = trim($_POST['qjt_footer_copyright']);      		   	
    $zbp->SaveConfig('qianjiangteam_51wzt'); 
    echo json_encode(array('code'=>'1','msg'=>'修改成功!'));die;
}

if($_GET['action'] == 'seo_set'){
    global $zbp;
    if (function_exists('CheckIsRefererValid')) {
        $flag = CheckCSRFTokenValid();
        if ($flag && $zbp->option['ZC_ADDITIONAL_SECURITY']) {
            $flag = CheckHTTPRefererValid();
        }
        if (!$flag) {
            echo json_encode(array('code'=>'0','msg'=>'来源错误!'));die;
        }
    }; 
    $zbp->Config('qianjiangteam_51wzt')->qjt_seo_switch = trim($_POST['qjt_seo_switch']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_title = trim($_POST['qjt_seo_site_title']);      		   	
    $zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_keywords = trim($_POST['qjt_seo_site_keywords']);      		   	
    $zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_discribe = trim($_POST['qjt_seo_site_discribe']);      		   	
    $zbp->SaveConfig('qianjiangteam_51wzt'); 
    echo json_encode(array('code'=>'1','msg'=>'修改成功!'));die;

}

if($_GET['action'] == 'adv_set'){
    global $zbp;
    if (function_exists('CheckIsRefererValid')) {
        $flag = CheckCSRFTokenValid();
        if ($flag && $zbp->option['ZC_ADDITIONAL_SECURITY']) {
            $flag = CheckHTTPRefererValid();
        }
        if (!$flag) {
            echo json_encode(array('code'=>'0','msg'=>'来源错误!'));die;
        }
    }; 
    $zbp->Config('qianjiangteam_51wzt')->qjt_adv_switch = trim($_POST['qjt_adv_switch']);
    $zbp->Config('qianjiangteam_51wzt')->qjt_adv_pc_head = trim($_POST['qjt_adv_pc_head']);      		   	
    $zbp->Config('qianjiangteam_51wzt')->qjt_adv_mobile_head = trim($_POST['qjt_adv_mobile_head']);      		   	     		   	
    $zbp->SaveConfig('qianjiangteam_51wzt'); 
    echo json_encode(array('code'=>'1','msg'=>'修改成功!'));die;

}

?>
