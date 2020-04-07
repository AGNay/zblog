<?php
require '../../../zb_system/function/c_system_base.php'; 
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
	$zbp->ShowError(6);
	die();
}
if (!$zbp->CheckPlugin('mo_App')) {
	$zbp->ShowError(48);
	die();
}

$act = GetVars('act', 'GET');
$suc = GetVars('suc', 'GET');
$appID = GetVars('appID', 'GET');
if (GetVars('act', 'GET') == 'del') {
	CheckIsRefererValid($appID);
	if (!HasNameInString("cache|system", $appID) && !$zbp->CheckPlugin($appID)) {
		$zbp->DelConfig($appID);
	}
	$zbp->BuildTemplate();
	$zbp->SetHint('good');
	Redirect('./clear.php' . ($suc == null ? '' : '?act=$suc'));
}
$blogtitle = '配置项扫描';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
	<style>
	.isOn-1::before {content:"启用";}
	.isTheme-1::before {content:"（主题）";}
	.isPlugin-1::before {content:"（插件）";}
	.isOn-0::before {content:"未启用";}
	.isTheme-0.isPlugin-0::before {content:"（不存在）";}
	.isSys-1::before {content:"（系统）";}
	.isSys-1>span {display:none;}
	</style>
	<div class="SubMenu"><a href="main.php?act=back"><span class="m-left" style="color:#F00;font-weight: bold;">返回</span></a></div>
	<div id="divMain2">
		<table width="100%" class="tableBorder">
			<tr>
				<th width="10%">项目<span class="m-left m-now" style="float: right;margin-right: 20px;"><a title="刷新" href="clear.php" style="font-size: 16px;display: inline-block;margin-left: 5px; background: red;color: #fff;font-weight: 700;padding:0 10px;">刷新</a></span></th>
				<th>状态</th>
				<th width="45%">操作</th>
			</tr>
			<?php echo mo_App_configClean_list(); ?>
		</table>
	</div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
