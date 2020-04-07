<?php
#注册插件
RegisterPlugin("Codemo","ActivePlugin_Codemo");

function ActivePlugin_Codemo() {
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','Codemo_main');
}

function Codemo_main() {
	global $zbp;
	$zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/Codemo/js/main.js"></script>' . "\r\n";
}

function InstallPlugin_Codemo() {

}

function UninstallPlugin_Codemo() {

}