<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Jz52_onepage')) {$zbp->ShowError(48);die();}
$blogtitle='主题配置';
$act = "";
if ($_GET['act']){
$act = $_GET['act'] == "" ? 'config' : $_GET['act'];
}
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
//基本设置
if(isset($_POST['submit'])){
	$zbp->Config('Jz52_onepage')->headtite = $_POST['headtite'];
	$zbp->Config('Jz52_onepage')->headimg = $_POST['headimg'];
	$zbp->Config('Jz52_onepage')->headp = $_POST['headp'];
	$zbp->Config('Jz52_onepage')->headbut = $_POST['headbut'];
	$zbp->Config('Jz52_onepage')->headbutu = $_POST['headbutu'];
	$zbp->Config('Jz52_onepage')->headbut1 = $_POST['headbut1'];
		
	$zbp->Config('Jz52_onepage')->onet = $_POST['onet'];
	$zbp->Config('Jz52_onepage')->oneico = $_POST['oneico'];
		
	$zbp->Config('Jz52_onepage')->two1img = $_POST['two1img'];
	$zbp->Config('Jz52_onepage')->two1t = $_POST['two1t'];
	$zbp->Config('Jz52_onepage')->two1p = $_POST['two1p'];
	$zbp->Config('Jz52_onepage')->two1u = $_POST['two1u'];
	$zbp->Config('Jz52_onepage')->two2img = $_POST['two2img'];
	$zbp->Config('Jz52_onepage')->two2t = $_POST['two2t'];
	$zbp->Config('Jz52_onepage')->two2p = $_POST['two2p'];
	$zbp->Config('Jz52_onepage')->two2u = $_POST['two2u'];
	$zbp->Config('Jz52_onepage')->two3img = $_POST['two3img'];
	$zbp->Config('Jz52_onepage')->two3t = $_POST['two3t'];
	$zbp->Config('Jz52_onepage')->two3p = $_POST['two3p'];
	$zbp->Config('Jz52_onepage')->two3u = $_POST['two3u'];
	$zbp->Config('Jz52_onepage')->twoico = $_POST['twoico'];
	
	$zbp->Config('Jz52_onepage')->threet = $_POST['threet'];
	$zbp->Config('Jz52_onepage')->threep = $_POST['threep'];
	$zbp->Config('Jz52_onepage')->threebut = $_POST['threebut'];
	$zbp->Config('Jz52_onepage')->threebutu = $_POST['threebutu'];
		
	$zbp->Config('Jz52_onepage')->footico = $_POST['footico'];

	$zbp->Config('Jz52_onepage')->beian = $_POST['beian'];
	$zbp->Config('Jz52_onepage')->DelCon = $_POST['DelCon'];
	
	$zbp->SaveConfig('Jz52_onepage');
	$zbp->ShowHint('good');	
}
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu">
	<?php Jz52_onepage_SubMenu($act);?>
     <a href="https://www.jz52.com" target="_blank"><span class="m-right">技术支持</span></a>
    </div>
<style>
.lianxi { padding: 10px; }
.lianxi p { line-height: 36px; }
.lianxi a { padding: 0 10px; }
.wiki { padding: 10px; background-color: #f4f4f4; }
.wiki p { line-height: 36px; }
input { margin: 5px 0; }
strong { background-color: #3a6ea5; padding: 5px 10px; color: #ffffff; }
p { margin: 5px 0; }
td { padding: 10px; }
</style>
<div id="divMain2">
<?php if ($act == 'config') { ?>

<form id="form1" name="form1" method="post">	
    <table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
		<tr>
			<th width="15%"><p align="center">选项名称</p></th>
			<th width="50%"><p align="center">选项内容</p></th>
			<th width="25%"><p align="center">选项说明</p></th>
		</tr>
		
		<tr>
        <td width="20%"  align="center"><p>页面大标题</p></td>
        <td>
          <p><input type="text" name="headtite" id="headtite" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->headtite;?>"/></p>
		</td>
		<td><p align="left">顶部区域大标题，比如APP名称等</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>页面描述</p></td>
        <td>
          <p><textarea name="headp" type="text" id="headp" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->headp;?></textarea></p>
		</td>
		<td><p align="left">顶部区域描述文字，比如APP广告语、简介等</p></td>
      </tr>
		
		
		<tr>
        <td width="20%"  align="center"><p>顶部手机内图片</p></td>
        <td><p><img src="<?php echo $zbp->Config('Jz52_onepage')->headimg;?>" style = "height:40px;"/></p>
          <p class="uploadimg">
            <input name="headimg" id="headimg" type="text" class="uplod_img" style="width:68%;" value="<?php echo $zbp->Config('Jz52_onepage')->headimg;?>" />
            <strong>浏览文件</strong> 
			</p>
		</td>
		<td><p align="left">比如APP界面截图</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>下载按钮文字</p></td>
        <td>
          <p><input type="text" name="headbut" id="headbut" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->headbut;?>"/></p>
		</td>
		<td><p align="left">下载按钮文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>下载按钮链接</p></td>
        <td>
          <p><input type="text" name="headbutu" id="headbutu" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->headbutu;?>"/></p>
		</td>
		<td><p align="left">下载按钮链接</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>了解更多按钮文字</p></td>
        <td>
          <p><input type="text" name="headbut1" id="headbut1" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->headbut1;?>"/></p>
		</td>
		<td><p align="left">了解更多按钮文字</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>一号区域文字</p></td>
        <td>
          <p><textarea name="onet" type="text" id="onet" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->onet;?></textarea></p>
		</td>
		<td><p align="left">一号区域文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>一号区域图标</p></td>
        <td>
          <p><textarea name="oneico" type="text" id="oneico" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->oneico;?></textarea></p>
		</td>
		<td><p align="left">一号区域图标</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容1标题</p></td>
        <td>
          <p><textarea name="two1t" type="text" id="two1t" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two1t;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容1标题</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容1链接</p></td>
        <td>
          <p><input type="text" name="two1u" id="two1u" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->two1u;?>"/></p>
		</td>
		<td><p align="left">二号区域内容1链接</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容1简介文字</p></td>
        <td>
          <p><textarea name="two1p" type="text" id="two1p" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two1p;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容1简介文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容1图片</p></td>
        <td><p><img src="<?php echo $zbp->Config('Jz52_onepage')->two1img;?>" style = "height:40px;"/></p>
          <p class="uploadimg">
            <input name="two1img" id="two1img" type="text" class="uplod_img" style="width:68%;" value="<?php echo $zbp->Config('Jz52_onepage')->two1img;?>" />
            <strong>浏览文件</strong> 
			</p>
		</td>
		<td><p align="left">二号区域内容图片 请固定长宽比1：1</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容2标题</p></td>
        <td>
          <p><textarea name="two2t" type="text" id="two2t" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two2t;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容2标题</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容2链接</p></td>
        <td>
          <p><input type="text" name="two2u" id="two2u" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->two2u;?>"/></p>
		</td>
		<td><p align="left">二号区域内容2链接</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容2简介文字</p></td>
        <td>
          <p><textarea name="two2p" type="text" id="two2p" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two2p;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容2简介文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容2图片</p></td>
        <td><p><img src="<?php echo $zbp->Config('Jz52_onepage')->two2img;?>" style = "height:40px;"/></p>
          <p class="uploadimg">
            <input name="two2img" id="two2img" type="text" class="uplod_img" style="width:68%;" value="<?php echo $zbp->Config('Jz52_onepage')->two2img;?>" />
            <strong>浏览文件</strong> 
			</p>
		</td>
		<td><p align="left">二号区域内容图片 请固定长宽比1：1</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容3标题</p></td>
        <td>
          <p><textarea name="two3t" type="text" id="two3t" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two3t;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容3标题</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容3链接</p></td>
        <td>
          <p><input type="text" name="two3u" id="two3u" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->two3u;?>"/></p>
		</td>
		<td><p align="left">二号区域内容3链接</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容3简介文字</p></td>
        <td>
          <p><textarea name="two3p" type="text" id="two3p" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->two3p;?></textarea></p>
		</td>
		<td><p align="left">二号区域内容3简介文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域内容3图片</p></td>
        <td><p><img src="<?php echo $zbp->Config('Jz52_onepage')->two3img;?>" style = "height:40px;"/></p>
          <p class="uploadimg">
            <input name="two3img" id="two3img" type="text" class="uplod_img" style="width:68%;" value="<?php echo $zbp->Config('Jz52_onepage')->two3img;?>" />
            <strong>浏览文件</strong> 
			</p>
		</td>
		<td><p align="left">二号区域内容图片 请固定长宽比1：1</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>二号区域图标</p></td>
        <td>
          <p><textarea name="twoico" type="text" id="twoico" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->twoico;?></textarea></p>
		</td>
		<td><p align="left">二号区域底部图标</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>三号区域标题</p></td>
        <td>
          <p><textarea name="threet" type="text" id="threet" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->threet;?></textarea></p>
		</td>
		<td><p align="left">三号区域标题</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>三号区域简介</p></td>
        <td>
          <p><textarea name="threep" type="text" id="threep" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->threep;?></textarea></p>
		</td>
		<td><p align="left">三号区域简介</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>三号区域按钮文字</p></td>
        <td>
          <p><input type="text" name="threebut" id="threebut" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->threebut;?>"/></p>
		</td>
		<td><p align="left">三号区域按钮文字</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>三号区域按钮链接</p></td>
        <td>
          <p><input type="text" name="threebutu" id="threebutu" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->threebutu;?>"/></p>
		</td>
		<td><p align="left">三号区域按钮链接</p></td>
      </tr>
	  
	  <tr>
        <td width="20%"  align="center"><p>底部社交按钮</p></td>
        <td>
          <p><textarea name="footico" type="text" id="footico" style="width:98%;"><?php echo $zbp->Config('Jz52_onepage')->footico;?></textarea></p>
		</td>
		<td><p align="left">底部社交按钮代码</p></td>
      </tr>
	  <tr>
        <td width="20%"  align="center"><p>备案号</p></td>
        <td>
          <p><input type="text" name="beian" id="beian" style="width:98%;" value="<?php echo $zbp->Config('Jz52_onepage')->beian;?>"/></p>
		</td>
		<td><p align="left">备案号</p></td>
      </tr>
		<tr>
			<td><label for="DelCon"><p align="center">清除设置</p></label></td>
			<td><p align="left"><input type="text" id="DelCon" name="DelCon" class="checkbox" value="<?php echo $zbp->Config('Jz52_onepage')->DelCon;?>"/></p></td>
			<td><p align="left">打开后更换主题会清除设置</p></td>
		</tr>
		
		
	</table>
	<br />
	<input name="submit" type="Submit" class="button" style="width:15%;" value="保存"/>
</form>



<?php } if($act == 'other'){?>

<table name="form1" width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
	<br/>
	<tr>
    <td>	
		<p>1、极致·APP下载单页主题是由 <a href="https://www.jz52.com" target="_blank">极致时空</a> 开发制作并免费分享的 zblog php 主题，免费主题制作不易，请保留底部版权链接</p>
		<p>2、免费主题不提供在线技术支持，有问题请到应用中心主题发布页留言，尽量解答</p>
	</td>
	
	</tr>	
</table>
	
<?php
    }	
?>
</div>
</div>
<?php
if ($zbp->CheckPlugin('UEditor')) {	
	echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/UEditor/ueditor.config.php"></script>';
	echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/UEditor/ueditor.all.min.js"></script>';
	echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/theme/Jz52_onepage/function/js/lib.upload.js"></script>';
	
	}
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>