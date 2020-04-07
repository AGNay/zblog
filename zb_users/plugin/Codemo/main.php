<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Codemo')) {$zbp->ShowError(48);die();}

$blogtitle='运行代码演示';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
 	<a href="main.php" ><span class="m-left m-now">插件说明</span></a>
	<a href="../../plugin/AppCentre/main.php?id=816"><span class="m-left" style="color:#F60">有收费版？</span></a>
    <a href="../../plugin/AppCentre/main.php?auth=3ec7ee20-80f2-498a-a5dd-fda19b198194"><span class="m-left">作者作品</span></a>
    <a href="http://www.yiwuku.com/diy-zblog.html" target="_blank"><span class="m-left">定制服务</span></a>
    <a href="http://www.yiwuku.com/" target="_blank"><span class="m-right">作者网站</span></a>
  </div>
  <div id="divMain2">
    <table class="tb-set" width="100%">
        <tr>
            <td colspan="2"><p style="color:#960;padding:0.8em;">本插件旨在为文章中贴出（通过编辑器）的前端代码片段添加“运行代码”和“复制”功能按钮，有效提升网站实用性和用户体验。</p></td>
        </tr>
        <tr>
            <td align="right" width="200" height="30"><b>通过编辑器贴代码示意图：</b></td>
            <td><img src="../Codemo/ez.jpg" height="300" /></td>
        </tr>
        <tr>
            <td align="right" height="30"><b>插件正常启用示意图：</b></td>
            <td><img src="../Codemo/ez2.jpg" /></td>
        </tr>
        <td colspan="2"><p style="color:#666;line-height:24px;padding:5px 0.8em;">该插件需基于jQuery库运行，绝大多数常规主题都有加载此库，不可用时则需咨询主题作者；<br>
        该插件有对应 [<a href="../../plugin/AppCentre/main.php?id=816">收费版</a>] ，功能更多、细节更精巧，如需了解更多请点击 <a href="../../plugin/AppCentre/main.php?id=816" target="_blank">这里</a> 。</p></td>
    </table>
	</div>
</div>
<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/Codemo/logo.png';?>");</script>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>