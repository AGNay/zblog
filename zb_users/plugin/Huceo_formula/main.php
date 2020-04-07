<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Huceo_formula')) {$zbp->ShowError(48);die();}

$blogtitle='KityFormula公式编辑插件';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
     <a href="http://www.huceo.com/post/452.html" target="_blank"><span class="m-right">帮助</span></a>
  </div>
  <div id="divMain2">
  <table width="100%" border="1">
            <tr height="32"><td colspan="2">本插件木有设置项，纯洁无污染，直接启用或停用即可。</td></tr>
          </table>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>