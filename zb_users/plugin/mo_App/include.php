<?php 
RegisterPlugin("mo_App","ActivePlugin_mo_App");
include('function/DeleteImgs.class.php');
function ActivePlugin_mo_App() {
	global $zbp;
	Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu','mo_App_AddMenu');//后台右上角导航
	if($zbp->Config('mo_App')->CateFlOn == 1) {Add_Filter_Plugin('Filter_Plugin_ViewList_Core','mo_App_CateFlOn');}//首页不显示指定分类的文章
	if($zbp->Config('mo_App')->CateZdyOn == 1) {Add_Filter_Plugin('Filter_Plugin_Category_Edit_Response','mo_App_cate_ctitle');}// 自定义分类信息（TDK）
	if($zbp->Config('mo_App')->ArticleImg == 1) {Add_Filter_Plugin('Filter_Plugin_Edit_Response5','mo_App_ArticleImg');}//文章发布页面自定义图片上传
	if($zbp->Config('mo_App')->ArticleDk == 1){	Add_Filter_Plugin('Filter_Plugin_Edit_Response5','mo_App_ArticleDk');}//文章发布页面自定义文章关键词、描述
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','mo_App_Footer');//{$footer}信息
	if($zbp->Config('mo_App')->ArticleAlt == 1){Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','mo_App_ArticleAlt');}// 前台调整，忽视源码img标签中alt标签
	if($zbp->Config('mo_App')->ArticleDelimgs == 1){
		Add_Filter_Plugin('Filter_Plugin_PostArticle_Core','mo_App_oldimg');
		Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed','mo_App_process');
		Add_Filter_Plugin('Filter_Plugin_DelArticle_Succeed','mo_App_delsu');
	}//删除文章时删除附带的图片
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','mo_App_ContentAd');// 文章内容新增前后广告信息
	if($zbp->Config('mo_App')->ArticleTflSxpOn == 1) {
		Add_Filter_Plugin('Filter_Plugin_Post_Prev', 'mo_App_Post_Prev');//同分类上下篇
		Add_Filter_Plugin('Filter_Plugin_Post_Next', 'mo_App_Post_Next');//同分类上下篇
	}
	if($zbp->Config('mo_App')->OtherBdts == 1) {
	Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'mo_App_OtherBdts');
	Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed', 'mo_App_post_article_succeed');
	}//百度主动推送
	Add_Filter_Plugin('Filter_Plugin_ViewList_Template','mo_App_Huandeng_sz');//幻灯片
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','mo_App_Huandeng_sz');//幻灯片
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','mo_App_ThemeCss');//CSS样式自定义
}
function mo_App_SubMenu($id){	//主题配置选项
	global $zbp;
	$arySubMenu = array(
		0 => array('说明', 'cjsm', 'left', false),
		1 => array('上传类', 'upload', 'left', false),
		2 => array('首页设置', 'index', 'left', false),
		3 => array('分类设置', 'category', 'left', false),
		4 => array('文章设置', 'article', 'left', false),
		5 => array('SEO设置', 'seo', 'left', false),
		6 => array('幻灯设置', 'huandeng', 'left', false),
		7 => array('其他设置', 'other', 'left', false),
	);
	foreach($arySubMenu as $k => $v){
		echo '<a href="?act='.$v[1].'&csrfToken='.$zbp->GetCSRFToken().'" '.($v[3]==true?'target="_blank"':'').'><span class="m-'.$v[2].' '.($id==$v[1]?'m-now':'').'">'.$v[0].'</span></a>';
	}
}
//后台右上角导航
function mo_App_AddMenu(&$m){
	global $zbp;
	$m[]=MakeTopMenu("root","漠漠睡集成",BuildSafeURL('/zb_users/plugin/mo_App/main.php?'),"","");
	if($zbp->Config('mo_App')->BackClear == 1) {
		$m[]=MakeTopMenu("root","清理配置项",BuildSafeURL('/zb_users/plugin/mo_App/clear.php'),"","");
	}
}

// 自定义分类信息（TDK）
function mo_App_cate_ctitle(){
	global $zbp,$cate;
	if($zbp->Config('mo_App')->CateZdyOn != 1) return;
	echo '<div id="alias" class="editmod">
	   <span class="title">当前分类标题、关键词、描述<font color="#FF0000">(不填写则按主题默认显示,注：此功能为插件定制)</font></span><br />
	   <strong>标题</strong><br>
	   <input type="text" style="width:75%;" name="meta_mo_App_catetitle" value="'.htmlspecialchars($cate->Metas->mo_App_catetitle).'"/><br>
	   <strong>关键词</strong><br>
	   <input type="text" style="width:75%;" name="meta_mo_App_catekeywords" value="'.htmlspecialchars($cate->Metas->mo_App_catekeywords).'"/><br>
	   <strong>描述</strong><br>
	   <input type="text" style="width:75%;" name="meta_mo_App_catemiaoshu" value="'.htmlspecialchars($cate->Metas->mo_App_catemiaoshu).'"/>
	   </div>';
}

//首页不显示指定分类的文章
function mo_App_CateFlOn(&$type,&$page,&$category,&$author,&$datetime,&$tag,&$w,&$pagebar){
	global $zbp;
	$catenum = explode(',',$zbp->Config('mo_App')->CateFlSx);
	if($type == 'index'){
		$w[]=array('NOT IN','log_CateID',$catenum);
		//以下是为了重建分页，过滤了分类，数量会发生变化
		$pagebar = new Pagebar($zbp->option['ZC_INDEX_REGEX']);
		$pagebar->PageCount = $zbp->displaycount;
		$pagebar->PageNow = $page;
		$pagebar->PageBarCount = $zbp->pagebarcount;
	}	
}

// 获取多个分类的文章并按照指定需求排序（最新 热门 热评）
function mo_App_GetCategorysArticle_new($Rows,$CategoryID,$hassubcate){
	global $zbp;
	$ids = strpos($CategoryID,',') !== false ? explode(',',$CategoryID) : array($CategoryID);
	$wherearray=array(); 
	foreach ($ids as $cateid){
		if (!$hassubcate) {
		  $wherearray[]=array('log_CateID',$cateid); 
		 }else{
			$wherearray[] = array('log_CateID', $cateid);
			foreach ($zbp->categorys[$cateid]->SubCategorys as $subcate) {
				$wherearray[] = array('log_CateID', $subcate->ID);
			}
		}
	}
	$where=array( 
		array('array',$wherearray), 
		array('=','log_Status','0'), 
	);
	$order = array('log_PostTime'=>'DESC'); 
	$articles= $zbp->GetArticleList(array('*'),$where,$order,array($Rows),'');
	return $articles;
}
// log_CommNums=按照评论数量排序；
function mo_App_GetCategorysArticle_com($Rows,$CategoryID,$hassubcate){
	global $zbp;
	$ids = strpos($CategoryID,',') !== false ? explode(',',$CategoryID) : array($CategoryID);
	$wherearray=array(); 
	foreach ($ids as $cateid){
		if (!$hassubcate) {
		  $wherearray[]=array('log_CateID',$cateid); 
		 }else{
			$wherearray[] = array('log_CateID', $cateid);
			foreach ($zbp->categorys[$cateid]->SubCategorys as $subcate) {
				$wherearray[] = array('log_CateID', $subcate->ID);
			}
		}
	}
	$where=array( 
		array('array',$wherearray), 
		array('=','log_Status','0'), 
	);
	$order = array('log_CommNums'=>'DESC'); 
	$articles= $zbp->GetArticleList(array('*'),$where,$order,array($Rows),'');
	return $articles;
}
// log_ViewNums=按照浏览数量排序；
function mo_App_GetCategorysArticle_view($Rows,$CategoryID,$hassubcate){
	global $zbp;
	$ids = strpos($CategoryID,',') !== false ? explode(',',$CategoryID) : array($CategoryID);
	$wherearray=array(); 
	foreach ($ids as $cateid){
		if (!$hassubcate) {
		  $wherearray[]=array('log_CateID',$cateid); 
		 }else{
			$wherearray[] = array('log_CateID', $cateid);
			foreach ($zbp->categorys[$cateid]->SubCategorys as $subcate) {
				$wherearray[] = array('log_CateID', $subcate->ID);
			}
		}
	}
	$where=array( 
		array('array',$wherearray), 
		array('=','log_Status','0'), 
	);
	$order = array('log_ViewNums'=>'DESC'); 
	$articles= $zbp->GetArticleList(array('*'),$where,$order,array($Rows),'');
	return $articles;
}

// 调用指定顶级分类下的二级分类
function mo_App_listfor($i,$ii) {
	global $zbp;
	$list = $zbp->GetCategoryByID($i);
	if ($list->SubCategorys&&$ii=='sub'){
		foreach ($list->SubCategorys as $p) {
			echo "<li><a href=".$p->Url.">".$p->Name."</a></li>";
		}
	}else{
		echo "<li><a href=".$p->Url.">".$p->Name."</a></li>";
	}
}

//获取指定分类及其二级分类
function mo_App_catelist($i,$ii) {
	global $zbp;
	$list = $zbp->GetCategoryByID($i);
	if ($list->SubCategorys&&$ii=='sub'){
		foreach ($list->SubCategorys as $p) {
			echo '<li>'.$p->Name.'</li>';
		}
	}else{
		echo '<li>'.$p->Name.'</li>';
		}	
}

// 自定义图片封面
function mo_App_ArticleImg(){
	global $zbp,$article;
	echo '<div>';
		echo '<label for="imagesfmxz"><p align="left" style="color:red;font-weight:700;">请酌情选择封面样式,权重大小：自定义上传‘>’自定义输入‘>’默认</p></label>';
		echo '<div><p style="color:blue;font-weight:700;">默认调用正文第一张图片，若文章内容没有图片则在./images/文件夹中随机选择一张作为封面<span style="color:red">(若其他两项为空，则生效)</span></p></div>';
		echo '<div>';
			echo '<label for="mo_zdyImage"><p align="left" style="color:blue;font-weight:700;">自定义图片地址（需加http(s)://）<span style="color:red">(若缩略图为空，则生效){$article->Metas->mo_zdyImage}</span></p></label>';
			echo '<input type="text" name="meta_zdyImage" style="width:60%;" value="'.$article->Metas->mo_zdyImage.'" />';
		echo '</div>';
		echo '<div>';
			echo '<label for="meta_mo_pic"><p style="color:blue;font-weight:700;">文章缩略图<span style="color:red">(若有，则其他不生效){$article->Metas->pic}</span></p></label>';
			echo '<p align="left" class="uploadimg">';
				echo '<input name="meta_mo_pic" id="edtTitle" type="text" class="uplod_img" style="width: 60%;" value="'.$article->Metas->mo_pic.'" />';
				echo '<strong class="button" style="color:#ffffff;padding:6px 18px 6px 18px;margin: 0 0.5em;background:#3a6ea5;border:1px solid #3399cc;cursor: pointer;">浏览文件</strong>';
			echo '</p>';
		echo '</div>';
	echo '</div>';
	echo '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/mo_App/js/images.js"></script>';
}

// 自定义文章摘要
function mo_App_Intro($article) {
	global $zbp;
	$max = $zbp->Config('mo_App')->ArticleIntromax;
	$less = $zbp->Config('mo_App')->ArticleIntroless;
	if(strlen(TransferHTML($article->Intro,'[nohtml]'))>50){
		$intro = preg_replace('/[\r\n]+/', '', trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),$max)).'...');
	}else{
		$intro = preg_replace('/[\r\n]+/', '', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),$max)).'...');
	}
	$s=mb_strlen($intro,'utf8');
	if($s<$less){
		$intro = "内容提要：".$article->Title;
	}
	echo $intro;
}

// 自定义关键词、描述
function mo_App_ArticleDk(){
	global $zbp,$article;
	echo '<div>';
		echo '<label for="mo_zdyTitle"><p align="left" style="color:blue;font-weight:700;">自定义文章标题<span style="color:red">{$article->Metas->zdyTitle}</span></p></label>';
		echo '<input type="text" name="meta_zdyTitle" style="width:60%;" value="'.$article->Metas->zdyTitle.'" />';
		echo '<label for="mo_zdyKey"><p align="left" style="color:blue;font-weight:700;">自定义文章关键词<span style="color:red">{$article->Metas->zdyKey}</span></p></label>'; 
		echo '<input type="text" name="meta_zdyKey" style="width:60%;" value="'.$article->Metas->zdyKey.'" />';
		echo '<label for="meta_mo_pic"><p style="color:blue;font-weight:700;">自定义文章描述<span style="color:red">(若有，则摘要不生效){$article->Metas->zdyDes}</span></p></label>';
		echo '<input type="text" name="meta_zdyDes" style="width:60%;" value="'.$article->Metas->zdyDes.'" />';
	echo '</div>';
}

// 在{$footer}标签位置添加版权信息
function mo_App_Footer(&$template){
	global $zbp;
	if($zbp->Config('mo_App')->OtherBq == 1) {
		$zbp->footer = $s = $zbp->Config('mo_App')->OtherBqNr;
	}
	// CSS样式添加
	if($zbp->Config('mo_App')->SeoCssOn == 1) {
		$zbp->header .= "<link rel=\"stylesheet\" href=\"{$zbp->host}zb_users/plugin/mo_App/css/style.css\" type=\"text/css\" />\r\n";
	}
	// 在{$footer}标签位置添加百度自动推送信息
	if ($zbp->Config('mo_App')->ArticleBdts == 1) {
		$zbp->footer .= "<script>
		(function(){
			var bp = document.createElement('script');
			var curProtocol = window.location.protocol.split(':')[0];
			if (curProtocol === 'https') {
				bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
			}
			else {
				bp.src = 'http://push.zhanzhang.baidu.com/push.js';
			}
			var s = document.getElementsByTagName(\"script\")[0];
			s.parentNode.insertBefore(bp, s);
		})();
		</script>";
	}
}

// 获取文章内容中的图片并选取第一张作为封面
// 如果没有，则返回指定图片
function mo_App_images($article) {
	global $zbp;	
	$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
	$content = $article->Content; 
	preg_match_all($pattern,$content,$matchContent);
	if(isset($matchContent[1][0])){
		$images=$matchContent[1][0]; 
	}else{
		$images=$zbp->host . "zb_users/plugin/mo_App/images/no-image.jpg";
	}
	return $images;
}

// 前台调整，忽视源码img标签中alt标签
// 正则调整文章内容中图片alt
function mo_App_ArticleAlt(&$template){
	global $zbp;
	if($zbp->Config('mo_App')->ArticleAlt == 0) return;
	$article = $template->GetTags('article');
	$pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i";
	$replacement = '<img alt="'.$article->Title.'" src=$2$3.$4$5/>';
	$content = preg_replace($pattern, $replacement, $article->Content);
	$article->Content = $content;
	$template->SetTags('article', $article);
}

//删除文章时删除文章内图片
function mo_App_oldimg($article){
	$c = new mo_App_DelImgs;
	$c->GetImgArray(GetPost((int)$article->ID)->Content,'oldpostimg');
	$GLOBALS['mo_App'] = $c;
}
function mo_App_process($article){
	$GLOBALS['mo_App']->GetImgArray($article->Content,'newpostimg');
	$GLOBALS['mo_App']->Process();
}
function mo_App_delsu($article){
	$c = new mo_App_DelImgs;
	$c->GetImgArray($article->Content,'oldpostimg');
	$c->DelFile($c->oldpostimg);
}

//同分类上一篇
function mo_App_Post_Prev(&$getthis) {
	global $zbp;
	$prev=$getthis;
	$articles = $zbp->GetPostList(
		array('*'),
		array(array('=', 'log_Type', 0), array('=', 'log_CateID', $prev->Category->ID),array('=', 'log_Status', 0), array('<', 'log_PostTime', $prev->PostTime)),
		array('log_PostTime' => 'DESC'),
		array(1),
		null
	);
	if (count($articles) == 1) {
		return $articles[0];
	} else {
		return null;
	}
}
//同分类下一篇
function mo_App_Post_Next(&$getthis) {
	global $zbp;
	$prev=$getthis;
	$articles = $zbp->GetPostList(
		array('*'),
		array(array('=', 'log_Type', 0), array('=', 'log_CateID', $prev->Category->ID),array('=', 'log_Status', 0), array('>', 'log_PostTime', $prev->PostTime)),
		array('log_PostTime' => 'ASC'),
		array(1),
		null
	);
	if (count($articles) == 1) {
		return $articles[0];
	} else {
		return null;
	}
}

//文章内容头部、尾部新增内容（广告或其他）
function mo_App_ContentAd (&$template){
	global $zbp;
	$article = $template->GetTags('article');
	if($zbp->Config('mo_App')->AdConTop == 1){
		$AdConTopNr = $zbp->Config('mo_App')->AdConTopNr;
		$article->Content = $AdConTopNr."".$article->Content;
		$template->SetTags('article', $article);
	}
	if($zbp->Config('mo_App')->AdConBot == 1){
		$AdConBotNr = $zbp->Config('mo_App')->AdConBotNr;
		$article->Content = $article->Content."".$AdConBotNr;
		$template->SetTags('article', $article);
	}
}

//百度主动推送-文章发布接口3
function mo_App_OtherBdts() {
	global $zbp;
	echo '<div id="mo_App" class="editmod">';
	echo '<label class="editinputname">百度主动推送:</label>';
	echo '<input id="OtherBdzd" name="OtherBdzd" type="text" value="'.$zbp->Config('mo_App')->OtherBdts.'" class="checkbox"/><br>';
	echo '</div>';
}
//文章发布成功提取链接
function mo_App_post_article_succeed(&$article) {
	if (GetVars('ArticleBdts', 'POST') != '1') {
		return;
	}
	global $zbp;

	$api = $OtherBdtsApi = $zbp->Config('mo_App')->OtherBdtsApi;
	if($api == ''){
		$zbp->SetHint('bad','推送失败请在插件后台设置百度主动推送接口地址');
	}else{
		$ajax = Network::Create();
		if (!$ajax) {
			throw new Exception('主机没有开启网络功能');
		}
		$ajax->open('POST', $api);
		$ajax->setRequestHeader('Content-Type', 'text/plain');
		$ajax->send(trim($article->Url));
		$result =stripslashes(json_encode($ajax->responseText));
		$remain = str_ireplace(':','',strstr(substr($result,0,strpos($result, ',"success"')),':'));
		if(strstr($result,'not')){
			$success = substr($result,0,strpos($result, ',"not_valid"'));
			$success = substr($success,strripos($success,':')+1);
			$t = "域名与推送接口不符\r\n剩余".$remain."条数据可提交";
		}else{
			if(strstr($result,'realtime')){
				$success = substr($result,0,strpos($result, ',"success_realtime'));
			}elseif(strstr($result,'batch')){
				$success = substr($result,0,strpos($result, ',"success_batch'));
			}else{
				$success = substr($result,0,strpos($result, '}'));
			}
			$success = substr($success,strripos($success,':')+1);
			$t = "成功提交".$success."条数据\r\n剩余".$remain."条数据可提交";
		}
		$zbp->SetHint('good','百度推送：'.$t);
	}
}

// 友好时间处理
function mo_App_TimeAgo( $ptime ) {
	$ptime = strtotime($ptime);
	$etime = time() - $ptime;
	if($etime < 1) return '刚刚';
	$interval = array (
		12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $ptime).')',
		30 * 24 * 60 * 60	   =>  '个月前 ('.date('m-d', $ptime).')',
		7 * 24 * 60 * 60		=>  '周前 ('.date('m-d', $ptime).')',
		24 * 60 * 60			=>  '天前',
		60 * 60				 =>  '小时前',
		60					  =>  '分钟前',
		1					   =>  '秒前'
	);
	foreach ($interval as $secs => $str) {
		$d = $etime / $secs;
		if ($d >= 1) {
			$r = round($d);
			return $r . $str;
		}
	};
}

// 判断是否为手机端
function mo_App_mobile() {
	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}
	return $is_mobile;
}

// 调用热门标签
function mo_App_Get_nbTags(){
	global $zbp,$str;
	$str = '';
	$array = $zbp->GetTagList('','',array('tag_Count'=>'DESC'),array(8),'');
	foreach ($array as $tag) {
		$str .= "<li><a href=\"{$tag->Url}\" title=\"{$tag->Name}\">{$tag->Name}<span>{$tag->Count}</span></a></li>";
	}
	return $str;
}


//清理未使用配置项
function mo_App_configClean_list() {
	global $zbp;
	$html = '';
	$configs_name = $configs_namevalue = array();
	foreach ($zbp->configs as $n => $c) {
		$configs_name[$n] = $n;
		$configs_namevalue[$n] = $c;
	}
	natcasesort($configs_name);
	$zbp->configs = array();
	foreach ($configs_name as $name) {
		$zbp->configs[$name] = $configs_namevalue[$name];
	}
	unset($configs_name, $configs_namevalue);
	foreach ($zbp->configs as $k => $v) {
		$isOn = $zbp->CheckPlugin($k) ? 1 : 0;
		$isSys = HasNameInString("cache|system|AppCentre", $k) ? 1 : 0;
		$delButton = "";
		$app = new App();
		if ($app->LoadInfoByXml('theme', $k) == true) {
			$isTheme = 1;
			$isPlugin = 0;
		} else if ($app->LoadInfoByXml('plugin', $k) == true) {
			$isTheme = 0;
			$isPlugin = 1;
		} else {
			$isTheme = 0;
			$isPlugin = 0;
		}
		if (!$isOn && !$isSys) {
			$delLink = BuildSafeURL("{$zbp->host}zb_users/plugin/mo_App/clear.php?act=del&appID={$k}");
			$delButton = "<a class=\"button\" href=\"{$delLink}\" title=\"删除该配置项\" onclick=\"return window.confirm(&quot;单击“确定”继续。单击“取消”停止。&quot;);\"><img height=\"16\" width=\"16\" src=\"{$zbp->host}zb_users/plugin/AppCentre/images/delete.png\"></a>";
		}
		$html .= "<tr><td>{$k}</td><td class='isSys-{$isSys}'><span class='isOn-{$isOn}'></span><span class='isTheme-{$isTheme} isPlugin-{$isPlugin}'></span></td><td>{$delButton}</td></tr>";
	}

	return $html;
}

//自定义CSS
function mo_App_ThemeCss(){
	global $zbp;
	if($zbp->CheckRights('root') && $zbp->CheckRights('PluginMng')){
		$strContent = @file_get_contents($zbp->usersdir . 'plugin/mo_App/css/zdy.css'); 
		$strContent = str_replace("{%SeoCss%}",$zbp->Config('mo_App')->SeoCss, $strContent);
		@file_put_contents($zbp->usersdir . 'plugin/mo_App/css/style.css', $strContent);
	}
}

//幻灯片
function mo_App_Huandeng_sz(&$template){
	global $zbp;
	$huandengArray = json_decode($zbp->Config('mo_App')->huandengArray,true);
	$template->SetTags('huandengArray', $huandengArray);
}
function mo_App_Huandeng_Build($huandengArray){
	global $zbp;
	$str = '<style>*{ margin:0;padding:0;}#zd{width: 980px;height: 300px;overflow: hidden;position: relative;margin:0 auto;}#zd ul{position: absolute;left:0;top:0;}#zd ul li{width: 980px;height: 300px;float: left; }#zd img{width: 980px;height: 300px;}</style><div id="zd"><ul>';
	foreach ($huandengArray as $key => $reg) {
		$str .= "<li><a href='".$reg['url']."' title='".$reg['title']."' target='_blank'><img alt='".$reg['title']."' src='".$reg['img']."' /></a></li>\n";
	}
	$str .='</ul></div><script>var oul=ytsides("zd").getElementsByTagName("ul")[0],oli=oul.getElementsByTagName("li"),timers=null,timer=null,i=0,oliW=oli[0].offsetWidth;oul.style.width=oli.length*oliW+"px";function ytsides(id){return document.getElementById(id)}function getClass(obj,name){if(obj.currentStyle){return obj.currentStyle[name]}else{return getComputedStyle(obj,false)[name]}}function Stratmove(obj,json,funEnd,callback){clearInterval(obj.timer);obj.timer=setInterval(function(){for(var attr in json){var bStop=true,cuur=parseFloat(getClass(obj,attr)),speed=0;if(attr=="opacity"){cuur=Math.round(parseFloat(getClass(obj,attr))*100)}else{cuur=parseFloat(getClass(obj,attr))}speed=(json[attr]-cuur)/8;speed=speed>0?Math.ceil(speed):Math.floor(speed);if(cuur!=json[attr]){bStop=false}if(attr=="opacity"){obj.style["opacity"]=(cuur+speed)/100;obj.style["filter"]="alpha(opacity="+cuur+speed+")"}else{obj.style[attr]=Math.round(cuur+speed)+"px"}if(bStop){clearInterval(obj.timer);callback()}if(funEnd){funEnd()}}},30)}var arr=[];timers=setInterval(function(){Stratmove(oul,{"left":-oliW},null,calls)},3000);function calls(){arr.push(oli[0]);oul.removeChild(oli[0]);oul.appendChild(arr[0]);arr.splice(0,arr.length);oul.style.left=0};</script>';
	@file_put_contents($zbp->usersdir . 'theme/'.$zbp->theme.'/include/mo_App_Huandeng.php', $str);
}


function InstallPlugin_mo_App() {
	global $zbp;
	if(!$zbp->Config('mo_App')->HasKey('Version')) {
		$zbp->Config('mo_App')->Version = '1.0';
		$zbp->Config('mo_App')->IndexKey='关键词1,关键词2,关键词3';
		$zbp->Config('mo_App')->IndexDes='请输入对首页的描述，一般不超过200个字符';
		$zbp->Config('mo_App')->CateZdyOn=1;
		$zbp->Config('mo_App')->CateFlOn=0;
		$zbp->Config('mo_App')->CateFlSx='1,2,3';
		$zbp->Config('mo_App')->ArticleIntromax=100;
		$zbp->Config('mo_App')->ArticleIntroless=30;
		$zbp->Config('mo_App')->ArticleDelimgs=1;
		$zbp->Config('mo_App')->ArticleTflSxpOn=1;
		$zbp->Config('mo_App')->ArticleBdts=1;
		$zbp->Config('mo_App')->huandengArray='[{"title":"这是标题","img":"'.$zbp->host . 'zb_users/plugin/mo_App/images/1.jpg","url":"'.$zbp->host . '","order":"1"}]';

		$zbp->SaveConfig('mo_App');
	}
}

function UninstallPlugin_mo_App() {
	global $zbp;
	$zbp->DelConfig('mo_App');
}