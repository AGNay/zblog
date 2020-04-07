<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('mo_App')) {$zbp->ShowError(48);die();}

$blogtitle='漠漠睡集成';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
$act = "";
if (isset($_GET['act'])){$act = $_GET['act'];}else{$act = 'cjsm';}
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu"><?php mo_App_SubMenu($act); ?></div>
	<div id="divMain2">
	<?php if ($act == 'cjsm'){ ?>
		<div>
			<p style="font-size: 32px;font-weight: bold;line-height: 40px;">收费插件应有良好的售后服务，售后QQ:3577270034,备注“漠漠睡集成+订单号”。</p><br>
			<p><b>1.1</b></p>
			<p>优化</p>
			<br>
			<p><b>1.0：</b></p>
			<p>1.插件首发</p>
			<p>2.集成部分功能可以自由使用/调用，但是大多数功能会直接集成在漠漠睡的主题或者其他插件中配合使用。</p>
			<p>2.0.1插件参考/集成如涉及侵权，请相关开发者在开发者群联系我</p>
			<p>2.1集成LOGO调用，默认封面调用，默认favicon.ico调用</p>
			<p>2.2集成自定义首页、分类页、文章页TDK</p>
			<p>2.3集成首页不显示指定分类（可多选）</p>
			<p>2.4集成自定义单个/多个分类按照时间/浏览/回复数量排序</p>
			<p>2.5集成调用指定顶级分类下二级分类</p>
			<p>2.6集成自定义文章页简介（基于文章内容前XX个字符）</p>
			<p>2.7集成自定义文章封面，优先级最高</p>
			<p>2.8集成修改前台文章页图片alt</p>
			<p>2.9集成文章内容头部尾部自定义增加内容</p>
			<p>2.10集成自动添加百度推送代码</p>
			<p>2.11集成发表文章自动进行百度主动推送</p>
			<p>2.12集成幻灯自定义增减 参考插件：<a href="https://app.zblogcn.com/?id=1258" target="_blank">https://app.zblogcn.com/?id=1258</a></p>
			<p>2.13集成删除未使用配置项 参考插件：<a href="https://app.zblogcn.com/?id=2018" target="_blank">https://app.zblogcn.com/?id=2018</a></p>
			<p>2.14集成不修改原始文件，后台新增/修改CSS&nbsp;</p>
			<p>3.如果你暂时使用不到漠漠睡的其他插件或者主题，那么该插件你可能并不能完美使用。</p>
			<br>
		</div>
	<?php }
	if ($act == 'upload'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=upload');
		} ?>
		<form enctype="multipart/form-data" method="post" action="upload.php?type=ArticleImg" style="margin-bottom: 0;">  
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';
			}?>
			<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0'>
				<tr>
					<th width='20%'><p align="center">上传内容</p></th>
					<th width='30%'><p align="center">上传文件</p></th>
					<th width='50%'><p align="center">提交</p></th>
				</tr>
				<tr>
					<td width='20%'><label for="no-image.jpg"><p align="center">文章不含图片时调用指定封面</p></label></td>
					<td width='30%'><p align="center">现有为演示，请自行上传<br>上传后，清空浏览器缓存查看图片<br><img src="<?php if(file_exists('images/no-image.jpg')){echo 'images/no-image.jpg';}?>" height="50px" /></p></td>
					<td width='50%'><p align="center"><input name="no-image.jpg" type="file"/><input name="" type="Submit" class="button" value="保存"/></p></td>
				</tr>
			</table>
		</form>
		<form enctype="multipart/form-data" method="post" action="upload.php?type=Logo" style="margin-bottom: 0;">  
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';
			}?>
			<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0'>
				<tr>
					<td width='20%'><label for="logo.png"><p align="center">上传LOGO .png格式(200*60px)</p></label></td>
					<td width='30%'><p align="center"><img src="<?php if(file_exists('images/logo.png')){echo 'images/logo.png';}?>" height="50px" /></p></td>
					<td width='50%'><p align="center"><input name="logo.png" type="file"/><input name="" type="Submit" class="button" value="保存"/></p></td>
				</tr>
			</table>
		</form>
		<form enctype="multipart/form-data" method="post" action="upload.php?type=Favicon" style="margin-bottom: 0;">  
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';
			}?>
			<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0'>
				<tr>
					<td width='20%'><label for="favicon.ico"><p align="center">favicon.ico (64*64px)</p></label></td>
					<td width='30%'><p align="center"><img src="<?php if(file_exists('images/favicon.ico')){echo 'images/favicon.ico';}?>" height="50px" /></p></td>
					<td width='50%'><p align="center"><input name="favicon.ico" type="file"/><input name="" type="Submit" class="button" value="保存"/></p></td>
				</tr>
			</table>
		</form>
	<?php }
	if ($act == 'index'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			$zbp->Config('mo_App')->IndexTitle = $_POST['IndexTitle'];//网站首页标题
			$zbp->Config('mo_App')->IndexDes = $_POST['IndexDes'];//网站首页关键词
			$zbp->Config('mo_App')->IndexKey = $_POST['IndexKey'];//网站首页描述

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=index');
		}?>
		<form id="form1" name="form1" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';
			}?>
			<table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>
					<th width='20%'><p align="center">设置</p></th>
					<th width='30%'><p align="center">内容</p></th>
					<th width='50%'><p align="center">说明</p></th>
				</tr>
				<tr><td colspan="3" style="text-align: center;"><span style="color: red;font-weight: 700;">（非漠漠睡主题需修改主题源码）</span></td></tr>
				<tr>
					<td>网站首页标题</td>
					<td><input type="text" name="IndexTitle" style="width:95%;" value="<?php if($zbp->Config('mo_App')->IndexTitle){ echo $zbp->Config('mo_App')->IndexTitle;}else{ echo "$zbp->name".'-'."$zbp->subname"; }?>" /></td>
					<td>仅限网站首页,自定义网站首页标题，不使用程序自带</td>
				</tr>
				<tr>
					<td>网站首页关键词</td>
					<td><input type="text" name="IndexKey" style="width:95%;" value="<?php echo $zbp->Config('mo_App')->IndexKey;?>" /></td>
					<td>自定义网站首页描述，需自行修改代码,一般不超过200个字符</td>
				</tr>
				<tr>
					<td>网站首页描述</td>
					<td><input type="text" name="IndexDes" style="width:95%;" value="<?php echo $zbp->Config('mo_App')->IndexDes;?>" /></td>
					<td>自定义网站首页关键词，需自行修改代码,请用英文“,”分开关键词，一般不超过100个字符。</td>
				</tr>
			</table>
			<input type="Submit" class="button" value="保存"/>
		</form>
	<?php }
	if ($act == 'category'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			$zbp->Config('mo_App')->CateZdyOn = $_POST['CateZdyOn'];//自定义分类TDK--开关
			$zbp->Config('mo_App')->CateFlOn = $_POST['CateFlOn'];//首页不显示指定分类--开关
			$zbp->Config('mo_App')->CateFlSx = $_POST['CateFlSx'];//首页不显示指定分类

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=category');
		}?>
		<form id="form2" name="form2" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';
			}?>
			<table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>
					<th width='20%'><p align="center">设置</p></th>
					<th width='30%'><p align="center">内容</p></th>
					<th width='50%'><p align="center">说明</p></th>
				</tr>
				<tr>
					<td>自定义分类TDK</td>
					<td colspan="2">
						开关<input type="text" name="CateZdyOn" value="<?php echo $zbp->Config('mo_App')->CateZdyOn;?>" class="checkbox"/><br>
					</td>
				</tr>
				<tr>
					<td>首页不显示指定分类的文章</td>
					<td colspan="2">
						开关<input type="text" name="CateFlOn" value="<?php echo $zbp->Config('mo_App')->CateFlOn;?>" class="checkbox"/>
						指定分类ID<input id="CateFlSx" name="CateFlSx" type="text" value="<?php echo $zbp->Config('mo_App')->CateFlSx;?>">
					</td>
				</tr>
			</table>
			<br />
			<input type="Submit" class="button" value="保存"/>
		</form>
	<?php }
	if ($act == 'article'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			$zbp->Config('mo_App')->ArticleIntromax= $_POST['ArticleIntromax'];//摘要最多字符
			$zbp->Config('mo_App')->ArticleIntroless = $_POST['ArticleIntroless'];//摘要最少字符
			$zbp->Config('mo_App')->ArticleImg = $_POST['ArticleImg'];//开启文章内容页自定义封面
			$zbp->Config('mo_App')->ArticleDk = $_POST['ArticleDk'];//开启文章内容页自定义关键词、描述
			$zbp->Config('mo_App')->ArticleBdts = $_POST['ArticleBdts'];//百度自动推送
			$zbp->Config('mo_App')->ArticleAlt = $_POST['ArticleAlt'];//前台自动添加图片alt标签为文章标题
			$zbp->Config('mo_App')->ArticleDelimgs = $_POST['ArticleDelimgs'];//删除文章时删除附带的图片
			$zbp->Config('mo_App')->ArticleTflSxpOn = $_POST['ArticleTflSxpOn'];//上下篇显示为同分类上下篇--开关
			$zbp->Config('mo_App')->AdConTop = $_POST['AdConTop'];//文章内容头部广告--开关
			$zbp->Config('mo_App')->AdConTopNr = $_POST['AdConTopNr'];//文章内容头部广告
			$zbp->Config('mo_App')->AdConBot = $_POST['AdConBot'];//文章内容尾部广告--开关
			$zbp->Config('mo_App')->AdConBotNr = $_POST['AdConBotNr'];//文章内容尾部广告

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=article');
		}?>
		<form id="form3" name="form3" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';
			}?>
			<table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>
					<th width='20%'><p align="center">设置</p></th>
					<th width='30%'><p align="center">内容</p></th>
					<th width='50%'><p align="center">说明</p></th>
				</tr>
				<tr>
					<td>自定义文章摘要<br>从内容中调取</td>
					<td>
						最多字数<input id="ArticleIntromax" name="ArticleIntromax" type="text" value="<?php echo $zbp->Config('mo_App')->ArticleIntromax;?>"><br>
						最少字数<input id="ArticleIntroless" name="ArticleIntroless" type="text" value="<?php echo $zbp->Config('mo_App')->ArticleIntroless;?>">
					</td>
					<td>调用文章内容指定字符数内容。（一般作为摘要展示）<br>摘要大于最大字符数则显示省略号，摘要小于最少字符数则显示”内容提要：文章标题“</td>
				</tr>
				<tr>
					<td>自定义文章封面<br>文章内容页修改</td>
					<td>开关<input type="text" name="ArticleImg" value="<?php echo $zbp->Config('mo_App')->ArticleImg;?>" class="checkbox"/></td>
					<td>开关控制是否在后台显示</td>
				</tr>
				<tr>
					<td>自定义文章关键词、描述<br>文章内容页修改</td>
					<td>开关<input type="text" name="ArticleDk" value="<?php echo $zbp->Config('mo_App')->ArticleDk;?>" class="checkbox"/></td>
					<td>开关控制是否在后台显示</td>
				</tr>
				<tr>
					<td>百度自动推送</td>
					<td><input type="text" name="ArticleBdts" value="<?php echo $zbp->Config('mo_App')->ArticleBdts;?>" class="checkbox"/></td>
					<td>{$footer}页脚添加百度自动推送代码，需主题含有{$footer}标签</td>
				</tr>
				<tr>
					<td>前台自动添加图片alt标签为文章标题</td>
					<td><input type="text" name="ArticleAlt" value="<?php echo $zbp->Config('mo_App')->ArticleAlt;?>" class="checkbox"/></td>
					<td>请谨慎开启，自动过滤文章源码中除了src（图片地址）外所有内容，可能会导致页面样式出现问题。</td>
				</tr>
				<tr>
					<td>删除文章时删除文章中存在服务器的图片</td>
					<td><input type="text" name="ArticleDelimgs" value="<?php echo $zbp->Config('mo_App')->ArticleDelimgs;?>" class="checkbox"/></td>
					<td>删除文章时删除文章中存在服务器的图片</td>
				</tr>
				<tr>
					<td>上下篇显示为同分类上下篇</td>
					<td><input type="text" name="ArticleTflSxpOn" value="<?php echo $zbp->Config('mo_App')->ArticleTflSxpOn;?>" class="checkbox"/></td>
					<td>建议开启，接口已调用，不开启可能会无法正常显示上一篇下一篇，修改上一篇、下一篇为当前分类上下篇</td>
				</tr>
				<tr>
					<td>文章内容头部广告</td>
					<td>
						<input type="text" name="AdConTop" value="<?php echo $zbp->Config('mo_App')->AdConTop;?>" class="checkbox"/><br>
						<textarea name="AdConTopNr" type="text" id="AdConTopNr" rows="4" style="width:98%;"><?php echo $zbp->Config('mo_App')->AdConTopNr;?></textarea>
					</td>
					<td>在文章头部直接添加代码</td>
				</tr>
				<tr>
					<td>文章内容尾部广告</td>
					<td>
						<input type="text" name="AdConBot" value="<?php echo $zbp->Config('mo_App')->AdConBot;?>" class="checkbox"/><br>
						<textarea name="AdConBotNr" type="text" id="AdConBotNr" rows="4" style="width:98%;"><?php echo $zbp->Config('mo_App')->AdConBotNr;?></textarea>
					</td>
					<td>在文章尾部直接添加代码</td>
				</tr>
			</table>
			<input type="Submit" class="button" value="保存"/>
		</form>
	<?php }
	if ($act == 'seo'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			$zbp->Config('mo_App')->OtherBdts = $_POST['OtherBdts'];//百度主动推送--开关
			$zbp->Config('mo_App')->OtherBdtsApi = $_POST['OtherBdtsApi'];//百度主动推送--API

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=seo');
		}?>
		<form id="form5" name="form5" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';
			}?>
			<table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>
					<th width='20%'><p align="center">设置</p></th>
					<th width='30%'><p align="center">内容</p></th>
					<th width='50%'><p align="center">说明</p></th>
				</tr>
				<tr>
					<td>百度主动推送</td>
					<td>
						默认开启:<input type="text" name="OtherBdts" value="<?php echo $zbp->Config('mo_App')->OtherBdts?>" class="checkbox"/><br>
						API:<textarea style="height:60px;width:100%" name="OtherBdtsApi" id="OtherBdtsApi" placeholder=""><?php echo TransferHTML($zbp->Config('mo_App')->OtherBdtsApi, '[textarea]');?></textarea>
					</td>
					<td>进入：<a href="https://ziyuan.baidu.com/linksubmit/index">https://ziyuan.baidu.com/linksubmit/index</a><br>接口调用地址： http://data.zz.baidu.com/urls?site=**************&token=************</td>
				</tr>
			</table>
			<input type="Submit" class="button" value="保存"/>
		</form>
	<?php }
	if ($act == 'huandeng'){
		if ($_POST && isset($_POST)) {
			if ($_GET && isset($_GET['type'])) {
				if ($_GET['type'] == 'add') {
					if($zbp->Config('mo_App')->HasKey('huandengArray')){$huandengArray = json_decode($zbp->Config('mo_App')->huandengArray,true);}
					$huandengArray[] = $_POST;
					foreach ($huandengArray as $key => $row) {
						$order[$key] = $row['order'];
						$title[$key]  = $row['title'];
					}
					array_multisort($order, SORT_ASC, $title, SORT_DESC, $huandengArray);
					$zbp->Config('mo_App')->huandengArray = json_encode($huandengArray);
					$zbp->SaveConfig('mo_App');
				} elseif ($_GET['type'] == 'edit') {
					$huandengArray = json_decode($zbp->Config('mo_App')->huandengArray,true);
					$editid = $_POST['editid'];
					unset($_POST['editid']);
					$huandengArray[$editid] =$_POST;
					foreach ($huandengArray as $key => $row) {
						$order[$key] = $row['order'];
						$title[$key]  = $row['title'];
					}
					array_multisort($order, SORT_ASC, $title, SORT_DESC, $huandengArray);
					$zbp->Config('mo_App')->huandengArray = json_encode($huandengArray);
					$zbp->SaveConfig('mo_App');
				}
			}
		} elseif ($_GET && isset($_GET)) {
			if (@$_GET['type'] == 'del') {
				$huandengArray = json_decode($zbp->Config('mo_App')->huandengArray,true);
				$editid = $_GET['id'];
				unset($huandengArray[$editid]);
				$zbp->Config('mo_App')->huandengArray = json_encode($huandengArray);
				$zbp->SaveConfig('mo_App');
			}
		}
		if($zbp->Config('mo_App')->HasKey('huandengArray')){
			$huandengArray = json_decode($zbp->Config('mo_App')->huandengArray,true);
			mo_App_Huandeng_Build($huandengArray);
		}else{
			$huandengArray = array();
		}
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=huandeng');
		}?>
		<style>
		.uplod_img {width:60%;}
		.uploadimg strong {cursor:pointer; background:#3A6EA5; width:15%; text-align:center; padding:5px 0; color:#fff; display:block; float:right;}
		.uploadimg img {width:15%; height:auto; float:left; display:inline-block; margin-right:5%;}
		.uploadimg input {float:left;}
		input.sedit{ width:93%;}
		table input{margin:0.25em 0;}
		table input.text{padding: 2px 5px;}
		table .button{padding: 2px 12px 5px 12px; margin: 0.25em 0;}
		</style>

		<div  style="color:#F00"><strong>温馨提示：</strong>上传图片功能需要安装启用<strong>UEditor编辑器</strong>插件，否则只能输入图片URL地址，zblog php自带UEditor编辑器，直接在插件管理中启用即可。</div>
		<form action="?act=huandeng&type=add" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';}?>
			<table width="100%" border="1" class="tableBorder">
				<tr>
					<th scope="col" width="5%" height="32" nowrap="nowrap">序号</th>
					<th scope="col" width="20%">标题</th>
					<th scope="col" width="35%">图片</th>
					<th scope="col" width="20%">链接</th>
					<th scope="col" width="5%">排序</th>
					<th scope="col" width="10%">操作</th>
				</tr>
				<tr>
					<td align="center"></td>
					<td><input type="text" class="sedit" name="title" value=""></td>
					<td><div class="uploadimg"><input type="text" class="uplod_img" name="img" value=""><strong>上传图片</strong></div></td>
					<td><input type="text" class="sedit" name="url" value=""></td>
					<td><input type="text" name="order" value="" style="width:40px"></td>
					<td><input type="submit" class="button" value="增加"/></td>
				</tr>
			</form>
<?php
foreach ($huandengArray as $key => $value) {
	echo '<form action="?act=huandeng&type=edit" method="post">'; 
	if (function_exists('CheckIsRefererValid')) {
		echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';
	}
	echo <<<eof
	<tr>
	<td align="center">{$key}</td>
	<input type="hidden" name="editid" value="{$key}">
	<td><input type="text" class="sedit" name="title" value="{$value['title']}" ></td>
	<td><div class="uploadimg"><img src="{$value['img']}"/><input type="text" class="uplod_img" name="img" value="{$value['img']}" ><strong>上传图片</strong></div></td>
	<td><input type="text" class="sedit" name="url" value="{$value['url']}" ></td>
	<td><input type="text" class="sedit" name="order" value="{$value['order']}" style="width:40px"></td>
	<td nowrap="nowrap">
	<input type="submit" class="button" value="修改"/>
	<input type="button" class="button" value="删除" onclick="if(confirm('您确定要进行删除操作吗？')){location.href='?act=huandeng&type=del&id={$key}'}"/></td></tr></form>
eof;
}?>
				</table>
			</div>
		</div>
		<?php
		if ($zbp->CheckPlugin('UEditor')) {	
			echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/UEditor/ueditor.config.php"></script>';
			echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/UEditor/ueditor.all.min.js"></script>';
			echo "<script type=\"text/javascript\" src=\"js/lib.upload.js\"></script>";
		}
	}
	if ($act == 'other'){
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			$zbp->Config('mo_App')->OtherIcp = $_POST['OtherIcp'];//网站备案信息
			$zbp->Config('mo_App')->BackClear = $_POST['BackClear'];//清理未使用配置项--开关
			$zbp->Config('mo_App')->SeoCssOn = $_POST['SeoCssOn'];//CSS开关
			$zbp->Config('mo_App')->SeoCss = $_POST['SeoCss'];//CSS

			$zbp->SaveConfig('mo_App');
			$zbp->SetHint('good');
			Redirect('./main.php?act=other');
		}?>
		<form id="form1" name="form1" method="post">
			<?php if (function_exists('CheckIsRefererValid')) {
				echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';
			}?>
			<table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>
					<th width='20%'><p align="center">设置</p></th>
					<th width='30%'><p align="center">内容</p></th>
					<th width='50%'><p align="center">说明</p></th>
				</tr>
				<tr>
					<td>网站备案信息</td>
					<td>
						<input type="text" name="OtherIcp" value="<?php echo $zbp->Config('mo_App')->OtherIcp ?>" id="OtherIcp" size="40"/><br>
					</td>
					<td>{$zbp->Config('mo_App')->OtherIcp}</td>
				</tr>
				<tr>
					<td>开启清理未使用配置项开关</td>
					<td>
						<input type="text" name="BackClear" value="<?php echo $zbp->Config('mo_App')->BackClear;?>" class="checkbox"/>替换开关
					</td>
					<td>进入右上角插件，或点击<a href="clear.php" style="padding: 5px 10px;background-color: red;color: #fff;">这里</a></td>
				</tr>
				<tr>
					<td>自定义CSS样式<br>在不调整主题文件的前提下自定义CSS样式</td>
					<td colspan="2">
						<input type="text" name="SeoCssOn" value="<?php echo $zbp->Config('mo_App')->SeoCssOn;?>" class="checkbox"/><br>
						<textarea name="SeoCss" type="text" id="SeoCss" rows="4" style="width:98%;"><?php echo $zbp->Config('mo_App')->SeoCss;?></textarea>
					</td>
				</tr>
			</table>
			<input type="Submit" class="button" value="保存"/>
		</form>
	<?php }?>
	</div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>