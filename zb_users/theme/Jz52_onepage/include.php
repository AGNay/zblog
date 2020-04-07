<?php
#注册插件
RegisterPlugin("Jz52_onepage","ActivePlugin_Jz52_onepage");

function ActivePlugin_Jz52_onepage() {
	Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'Jz52_onepage_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_ViewList_Template', 'Jz52_onepage_Footerc');
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'Jz52_onepage_Footerc');
}
function Jz52_onepage_AddMenu(&$m) {
	global $zbp;
	$m[] = MakeTopMenu("root", '主题配置', $zbp->host . "zb_users/theme/Jz52_onepage/main.php?act=config", "", "topmenu_Jz52_onepage");
}
function Jz52_onepage_SubMenu($id){
	$arySubMenu = array(
		0 => array('基本设置', 'config', 'left', false),
		1 => array('说明', 'other', 'left', false),
	);
	foreach($arySubMenu as $k => $v){
		echo '<a href="?act='.$v[1].'" '.($v[3]==true?'target="_blank"':'').'><span class="m-'.$v[2].' '.($id==$v[1]?'m-now':'').'">'.$v[0].'</span></a>';
	}
}


#底部版权 前台
function Jz52_onepage_Footer(){
	global $zbp;
	if($zbp->Config('Jz52_onepage')->beian){
	echo '<a href="http://www.beian.miit.gov.cn" target="_blank">'.$zbp->Config('Jz52_onepage')->beian.'</a>';
	}
	echo $zbp->Config('Jz52_onepage')->footer;
}
function Jz52_onepage_Footerc(){
	global $zbp;
	if(strpos($zbp->Config('Jz52_onepage')->footer,'jz52.com') == false){ 
	$zbp->footer .='<script type="text/javascript">alert("保留版权是一种美德");</script>'; }	
}

function InstallPlugin_Jz52_onepage() {
	global $zbp;
	if(!$zbp->Config('Jz52_onepage')->HasKey('Version')){
		$zbp->Config('Jz52_onepage')->Version = '1.0';
		$zbp->Config('Jz52_onepage')->headtite = '隔壁老李';
		$zbp->Config('Jz52_onepage')->headimg = $zbp->host.'zb_users/theme/Jz52_onepage/style/images/screen.jpg';
		$zbp->Config('Jz52_onepage')->headp = '隔壁老李是一款由极致时空开发的邻里互助APP<br>只要人人都献出一点爱 世界将变成美好人间';
		$zbp->Config('Jz52_onepage')->headbut = '立即下载';	
		$zbp->Config('Jz52_onepage')->headbutu = '#';
		$zbp->Config('Jz52_onepage')->headbut1 = '了解更多';

		$zbp->Config('Jz52_onepage')->onet = '人间有真情 人间有真爱<br>老李出马 家庭和谐 邻里和睦 世界和平';
		$zbp->Config('Jz52_onepage')->oneico = '<li><span class="icon solid fa-camera-retro"><span class="label">图标</span></span></li>
<li><span class="icon solid fa-sync"><span class="label">图标</span></span></li>
<li><span class="icon solid fa-cloud"><span class="label">图标</span></span></li>';
		
		$zbp->Config('Jz52_onepage')->two1img = $zbp->host.'zb_users/theme/Jz52_onepage/style/images/pic01.jpg';
		$zbp->Config('Jz52_onepage')->two1t = '小区门房秦二爷';	
		$zbp->Config('Jz52_onepage')->two1p = '一天刷一次隔壁老李APP,腿不酸了,腰也不疼了,一口气上五楼还不费劲。隔壁老李APP，今年刷，明年刷，年年刷。今年过节不收礼，收礼就收隔壁老李APP';	
		$zbp->Config('Jz52_onepage')->two1u = '#';			
		$zbp->Config('Jz52_onepage')->two2img =  $zbp->host.'zb_users/theme/Jz52_onepage/style/images/pic02.jpg';
		$zbp->Config('Jz52_onepage')->two2t = '二楼美女白小洁';	
		$zbp->Config('Jz52_onepage')->two2p = '偶尔的机会，从高义哪里了解到了隔壁老李这款APP，从此一发不可收拾的爱上了它。半夜修仙为那般？只因一个APP。我已经把这个APP介绍给了我的好友张敏和美红了';	
		$zbp->Config('Jz52_onepage')->two2u = '#';			
		$zbp->Config('Jz52_onepage')->two3img =  $zbp->host.'zb_users/theme/Jz52_onepage/style/images/pic03.jpg';
		$zbp->Config('Jz52_onepage')->two3t = '租房少年王喔宾';	
		$zbp->Config('Jz52_onepage')->two3p = '编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了，编不下去了。';	
		$zbp->Config('Jz52_onepage')->two3u = '#';	
		$zbp->Config('Jz52_onepage')->twoico = '<li><span class="icon solid fa-camera-retro"><span class="label">这是一个功能</span></span></li>
<li><span class="icon solid fa-sync"><span class="label">这也是一个功能</span></span></li>
<li><span class="icon solid fa-cloud"><span class="label">这还是一个功能</span></span></li>
<li><span class="icon solid fa-code"><span class="label">这又是啥功能</span></span></li>
<li><span class="icon solid fa-desktop"><span class="label">你还想要啥功能</span></span></li>';
		
		$zbp->Config('Jz52_onepage')->threet = '邻里互助尽在隔壁老李';	
		$zbp->Config('Jz52_onepage')->threep = '隔壁老李是一款由极致时空开发的邻里互助APP<br>我们的目标是让邻里之间不再冷漠，只要人人都献出一点爱，世界将变成美好人间';	
		$zbp->Config('Jz52_onepage')->threebut = '温故知新';	
		$zbp->Config('Jz52_onepage')->threebutu = '#';	
		
		$zbp->Config('Jz52_onepage')->footico = '<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>';	
		
		$zbp->Config('Jz52_onepage')->beian = '';
		$zbp->Config('Jz52_onepage')->DelCon = '0';
		$zbp->Config('Jz52_onepage')->footer = 'Powered: <a href="http://www.zblogcn.com/" target="_blank">Z-BlogPHP</a>. Themes: <a href="https://www.jz52.com/" target="_blank">Jz52.com</a>.</p>
</footer>
<script src="'.$zbp->host.'zb_users/theme/Jz52_onepage/script/jquery.scrolly.min.js"></script>
<script src="'.$zbp->host.'zb_users/theme/Jz52_onepage/script/browser.min.js"></script>
<script src="'.$zbp->host.'zb_users/theme/Jz52_onepage/script/breakpoints.min.js"></script>
<script src="'.$zbp->host.'zb_users/theme/Jz52_onepage/script/util.js"></script>
<script src="'.$zbp->host.'zb_users/theme/Jz52_onepage/script/main.js"></script>';
		$zbp->SaveConfig('Jz52_onepage');
	}
}
function UninstallPlugin_Jz52_onepage() {
	global $zbp;
	if ($zbp->Config('Jz52_onepage')->DelCon){
		$zbp->DelConfig('Jz52_onepage');
	}
}