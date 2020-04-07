<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('mo_App')) {$zbp->ShowError(48);die();}

if($_GET['type'] == 'ArticleImg' ){
	global $zbp;
	foreach ($_FILES as $key => $value) {
		if(!strpos($key, "_php")){
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];
				@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir . 'plugin/mo_App/images/no-image.jpg');
			}
		}
	}
	$zbp->SetHint('good','修改成功');
	Redirect('./main.php?act=upload');
}
if($_GET['type'] == 'Logo' ){
	global $zbp;
	foreach ($_FILES as $key => $value) {
		if(!strpos($key, "_php")){
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];
				@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir . 'plugin/mo_App/images/logo.png');
			}
		}
	}
	$zbp->SetHint('good','修改成功');
	Redirect('./main.php?act=upload');
}
if($_GET['type'] == 'Favicon' ){
	global $zbp;
	foreach ($_FILES as $key => $value) {
		if(!strpos($key, "_php")){
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];
				@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir . 'plugin/mo_App/images/favicon.ico');
			}
		}
	}
	$zbp->SetHint('good','修改成功');
	Redirect('./main.php?act=upload');
}
?>